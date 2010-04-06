<?php
    require_once(PW2_PATH . '/src/DbObject.php');
    class Contact implements DbObject
    {
        protected $id;
        protected $name;
        protected $notification_channels;

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
                $this->name = '';
                $this->notification_channels = array();
            }
        }

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }
        
        public function loadById($id)
        {
            $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'contacts', 'WHERE id=' . intval($id));
            $this->loadByRow($db_row);
        }

        public function loadByRow($db_row)
        {
            $this->id = $db_row['id'];
            $this->name = $db_row['name'];
            $this->notification_channels = array();
            if(strlen($db_row['notification_channels']) > 0)
                foreach(explode(',', $db_row['notification_channels']) as $channel)
                    $this->notification_channels[] = Channel::fetch(intval($channel));
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

        public function saveToDb()
        {
            $values = array(
                'name' => $this->name,
                'notification_channels' => implode(',', $this->getChanIds()),
            );
            if($this->id === null)
                $this->id = $GLOBALS['PW_DB']->executeInsert($values, 'contacts');
            else
                $GLOBALS['PW_DB']->executeUpdate($values, 'contacts', 'WHERE id=' . intval($this->id));
        }

        private function getChanIds()
        {
            $ids = array();
            foreach($this->notification_channels as $channel)
            {
                $ids[] = $channel->getId();
            }
            return $ids;
        }
    }
?>
