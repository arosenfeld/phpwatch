<?php
    require_once(PW2_PATH . '/src/DbObject.php');
    // The monitor is online as of the last polling.
    define('STATUS_ONLINE', 0x01);
    // The monitor is offline as of the last polling.
    define('STATUS_OFFLINE', 0x02);
    // The monitor is paused.  Poll data is discarded.
    define('STATUS_PAUSED', 0x03);
    // The monitor is in a scheduled-downtime mode.
    define('STATUS_DOWNTIME', 0x04);
    // The monitor has no polling data available.
    define('STATUS_UNPOLLED', 0x05);

    abstract class Monitor implements DbObject
    {
        protected $id;
        protected $hostname;
        protected $port;
        protected $alias;
        protected $status;
        protected $downtime_start;
        protected $downtime_end;
        protected $notification_channels;
        protected $send_notifications;
        protected $last_query;
        protected $fail_count;
        protected $fail_threshold;
        protected $config;

        public function __construct($data = null)
        {
            if(is_int($data))
            {
                $this->loadById($id);
            }
            elseif(is_array($data))
            {
                $this->loadByRow($data);
            }
            else
            {
                $this->id = null;
                $this->hostname = $hostname;
                $this->port = $port;
                $this->alias = $alias;
                $this->status = STATUS_UNPOLLED;
                $this->downtime_start = 0;
                $this->downtime_end = 0;
                $this->notification_channels = array();
                $this->send_notifications = true;
                $this->last_query = 0;
                $this->fail_count = 0;
                $this->fail_threshold = 1;
                $this->config = array();
            }
        }

        public function getId()
        {
            return $this->id;
        }

        public function getHostname()
        {
            return $this->hostname;
        }

        public function getPort()
        {
            return $this->port;
        }

        public function getAlias()
        {
            return $this->alias;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function getLastQuery()
        {
            return $this->last_query;
        }

        public function getFailThreshold()
        {
            return $this->fail_threshold;
        }

        public function getDowntimeStart()
        {
            return $this->downtime_start;
        }

        public function getDowntimeEnd()
        {
            return $this->downtime_end;
        }

        public function loadById($id)
        {
            $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'monitors', 'WHERE id=' . intval($id));
            $this->loadByRow($db_row);
        }

        public function loadByRow($db_row)
        {
            $this->id = $db_row['id'];
            $this->hostname = $db_row['hostname'];
            $this->port = $db_row['port'];
            $this->alias = $db_row['alias'];
            $this->status = $db_row['status'];
            $this->downtime_start = $db_row['downtime_start'];
            $this->downtime_end = $db_row['downtime_end'];
            $this->notification_channels = array();
            $this->send_notifications = $db_row['send_notifications'];
            $this->last_query = $db_row['last_query'];
            $this->fail_count = $db_row['fail_count'];
            $this->fail_threshold = $db_row['fail_threshold'];
            $this->config = unserialize($db_row['config']);

            if(strlen($db_row['notification_channels']) > 0)
                foreach(explode(',', $db_row['notification_channels']) as $channel)
                    $this->notification_channels[] = Channel::fetch(intval($channel));
        }

        public function saveToDb()
        {
            $values = array(
                'type' => get_class($this),
                'hostname' => $this->hostname,
                'port' => $this->port,
                'alias' => $this->alias,
                'status' => $this->status,
                'downtime_start' => $this->downtime_start,
                'downtime_end' => $this->downtime_end,
                'notification_channels' => implode(',', $this->getChanIds()),
                'send_notifications' => $this->send_notifications,
                'last_query' => $this->last_query,
                'fail_count' => $this->fail_count,
                'fail_threshold' => $this->fail_threshold,
                'config' => serialize($this->config),
            );
            if($this->id === null)
                $this->id = $GLOBALS['PW_DB']->executeInsert($values, 'monitors');
            else
                $GLOBALS['PW_DB']->executeUpdate($values, 'monitors', 'WHERE id=' . intval($this->id));
        }

        public function addChannel($channel)
        {
            $index = array_search($channel, $this->notification_channels);
            if($index === false)
                $this->notification_channels[] = $channel;
        }

        public function deleteChannel($channel)
        {
            $index = array_search($channel, $this->notification_channels);
            if($index !== false)
                unset($this->notification_channels[$index]);
        }

        public function sendNotifications()
        {
            foreach($this->notification_channels as $channel)
            {
                $channel->doNotify($this);
            }
        }

        public function poll()
        {
            $up = $this->queryMonitor();
            $this->last_query = time();

            if($this->downtime_start != 0 && $this->downtime_start < time())
                $this->status = STATUS_DOWNTIME;

            if($this->status == STATUS_DOWNTIME && $this->downtime_end <= time())
            {
                $this->status = UNPOLLED;
                $this->downtime_start = 0;
                $this->downtime_end = 0;
            }

            switch($this->status)
            {
                case STATUS_UNPOLLED:
                case STATUS_ONLINE:
                case STATUS_OFFLINE:
                    if($this->id != 0)
                        Statistics::add('monitor' . $this->id, time(), $up ? 1 : 0);
                    if($up)
                    {
                        $this->status = STATUS_ONLINE;
                        $this->fail_count = 0;
                        $this->send_notifications = true;
                    }
                    else
                    {
                        $this->status = STATUS_OFFLINE;
                        $this->fail_count++;
                    }
                    break;
                case STATUS_PAUSED:
                    break;
            }
            if( $this->send_notifications && 
                $this->status == STATUS_OFFLINE && 
                $this->fail_count >= $this->fail_threshold)
            {
                $this->sendNotifications();
                $this->send_notifications = false;
            }
            return $up;
        }

        public function getChanIds()
        {
            $ids = array();
            foreach($this->notification_channels as $channel)
            {
                $ids[] = $channel->getId();
            }
            return $ids;
        }

        public function processAddEdit($data)
        {
            $errors = array();
            if(strlen($data['hostname']) == 0)
                $errors['hostname'] = 'Hostname cannot be blank.';
            $this->hostname = $data['hostname'];

            if(!is_numeric($data['port']))
                $errors['port'] = 'Port must be numeric.';
            $this->port = intval($data['port']);

            $this->alias = $data['alias'];

            if(!is_numeric($data['fail_threshold']) || intval($data['fail_threshold']) <= 0)
                $errors['fail_threshold'] = 'Failure threshold must be a positive integer.';
            $this->fail_threshold = intval($data['fail_threshold']);

            $this->notification_channels = array();
            if(is_array($data['notification_channels']))
            {
                foreach($data['notification_channels'] as $id)
                    $this->notification_channels[] = Channel::fetch(intval($id));
            }

            $this->status = intval($data['status']);
            if($this->status == STATUS_DOWNTIME)
            {
                if(!is_numeric($data['downtime_start_hours']) ||
                   !is_numeric($data['downtime_start_minutes']) ||
                   !is_numeric($data['downtime_end_hours']) ||
                   !is_numeric($data['downtime_end_minutes'])
                  )
                {
                    $errors['interval'] = 'Invalid time or interval.';
                }
                $this->downtime_start = time() + intval($data['downtime_start_hours']) * 60 +
                intval($data['downtime_start_minutes']);
                $this->downtime_end = time() + intval($data['downtime_end_hours']) * 60 +
                intval($data['downtime_end_minutes']);
            }
            else
            {
                $this->downtime_start = 0;
                $this->downtime_end = 0;
            }

            $errors = $this->customProcessAddEdit($data, $errors);
            return $errors;
        }

        public function processDelete($data)
        {
            $this->customProcessDelete($data);
            return $GLOBALS['PW_DB']->executeDelete('monitors', 'WHERE id=' . intval($this->id));
        }

        public abstract function queryMonitor();
        public abstract function customProcessAddEdit($data, $errors);
        public abstract function customProcessDelete();
        public abstract function getName();
        public abstract function getDescription();

        public static function fetch($db_row)
        {
            if(is_int($db_row))
                $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'monitors', 'WHERE id=' . intval($db_row));
            $monitor_type = $db_row['type'];
            return new $monitor_type($db_row);
        }
    }
?>
