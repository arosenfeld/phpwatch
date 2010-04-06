<?php
    require_once(PW2_PATH . '/src/DbObject.php');
    abstract class Channel implements DbObject
    {
        protected $id;
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
                $this->config = array();
            }
        }

        public function getId()
        {
            return $this->id;
        }

        public function loadById($id)
        {
            $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'channels', 'WHERE id=' . intval($id));
            $this->loadByRow($db_row);
        }

        public function loadByRow($db_row)
        {
            $this->id = $db_row['id'];
            $this->config = unserialize($db_row['config']);
        }

        public function saveToDb()
        {
            $values = array(
                'type' => get_class($this),
                'config' => serialize($this->config)
            );
            if($this->id === null)
                $this->id = $GLOBALS['PW_DB']->executeInsert($values, 'channels');
            else
                $GLOBALS['PW_DB']->executeUpdate($values, 'channels', 'WHERE id=' . intval($this->id));
        }

        public abstract function doNotify($monitor);

        public static function fetch($db_row)
        {
            if(is_int($db_row))
                $db_row = $GLOBALS['PW_DB']->executeSelectOne('*', 'channels', 'WHERE id=' . intval($db_row));
            $channel_type = $db_row['type'];
            return new $channel_type($db_row);
        }
    }
?>
