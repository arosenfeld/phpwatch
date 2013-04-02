<?php
    require_once(PW2_PATH . '/src/DbObject.php');
    abstract class Channel implements DbObject
    {
        protected $id;
        protected $owner;
        protected $config;

        public function __construct($data = null)
        {
            if(is_int($data))
            {
                $this->loadById($data);
            }
            elseif(is_array($data))
            {
                $this->loadByRow($data);
            }
            else
            {
                $this->id = null;
                $this->owner = 0;
                $this->config = array();
            }
        }

        public function getId()
        {
            return $this->id;
        }

        public function getOwner()
        {
            return $this->owner;
        }

        public function setOwner($owner_id)
        {
            $this->owner = $owner_id;
        }

        public function loadById($id)
        {
            $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'channels', 'WHERE id=' . intval($id));
            $this->loadByRow($db_row);
        }

        public function loadByRow($db_row)
        {
            $this->id = intval($db_row['id']);
            $this->owner = intval($db_row['owner']);
            $this->config = unserialize($db_row['config']);
        }

        public function saveToDb()
        {
            $values = array(
                'type' => get_class($this),
                'owner' => $this->owner,
                'config' => serialize($this->config)
            );
            if($this->id === null)
                $this->id = $GLOBALS['PW_DB']->executeInsert($values, 'channels');
            else
                $GLOBALS['PW_DB']->executeUpdate($values, 'channels', 'WHERE id=' . intval($this->id));
        }

        public function processAddEdit($data)
        {
            $errors = array();
            $errors = $this->customProcessAddEdit($data, $errors);
            return $errors;
        }

        public function processDelete($data)
        {
            $this->customProcessDelete();
            $mons = $GLOBALS['PW_DB']->executeSelect('*', 'monitors', 'WHERE ' . intval($this->id) . ' IN
            (notification_channels)');
            foreach($mons as $mon)
            {
                $mhandle = Monitor::fetch($mon);
                $mhandle->deleteChannel($mhandle);
                $mhandle->saveToDb();
            }
            $GLOBALS['PW_DB']->executeDelete('channels', 'WHERE id=' . intval($this->id));
        }

        public abstract function doNotify($monitor);
        public abstract function customProcessAddEdit($data, $errors);
        public abstract function customProcessDelete();
        public abstract function getName();
        public abstract function getDescription();

        public function __toString()
        {
            return $this->getName();
        }

        public static function fetch($db_row)
        {
            if(is_int($db_row))
                $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'channels', 'WHERE id=' . intval($db_row));
            $channel_type = $db_row['type'];
            return new $channel_type($db_row);
        }
    }
?>
