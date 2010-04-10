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
            $ch_rows = $GLOBALS['PW_DB']->executeSelect('*', 'channels', 'WHERE owner=' . intval($this->id));
            foreach($ch_rows as $row)
                $this->notification_channels[] = Channel::fetch($row);
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
            $values = array( 'name' => $this->name );
            if($this->id === null)
                $this->id = $GLOBALS['PW_DB']->executeInsert($values, 'contacts');
            else
                $GLOBALS['PW_DB']->executeUpdate($values, 'contacts', 'WHERE id=' . intval($this->id));
            $values = array( 'owner' => 0 );
            $GLOBALS['PW_DB']->executeUpdate($values, 'channels', 'WHERE owner=' . intval($this->id));
            $values = array( 'owner' => $this->id );
            $GLOBALS['PW_DB']->executeUpdate($values, 'channels', 'WHERE id IN (' . implode(',', $this->getChanIds()) . ')');
            $GLOBALS['PW_DB']->executeDelete('channels', 'WHERE id=0');
        }

        public function processAddEdit($data)
        {
            $errors = array();
            if(strlen($data['name']) == 0)
                $errors['name'] = 'Name cannot be blank.';
            $this->name = $data['name'];
            return $errors;
        }

        public function processDelete($data)
        {
            $chans = $GLOBALS['PW_DB']->executeSelect('id', 'channels', 'WHERE owner=' . intval($data['id']));
            foreach($chans as $chan)
            {
                $channel = Channel::fetch($chan);
                $channel->processDelete();
            }
            $GLOBALS['PW_DB']->executeDelete('contacts', 'WHERE id=' . intval($data['id']));
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
