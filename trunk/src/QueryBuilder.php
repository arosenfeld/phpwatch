<?php
    abstract class QueryBuilder
    {
        protected $host;
        protected $db;
        protected $user;
        protected $pw;
        protected $link;

        public function __construct($host, $db, $user, $pw)
        {
            $this->host = $host;
            $this->db = $db;
            $this->user = $user;
            $this->pw = $pw;
        }

        protected abstract function connect();

        protected abstract function query($sql);
        public abstract function executeSelect($fields, $table, $suffix);
        public abstract function executeSelectOne($fields, $table, $suffix);
        public abstract function executeInsert($fields, $table);
        public abstract function executeUpdate($fields, $table, $suffix);
        public abstract function executeDelete($table, $suffix);
        public abstract function executeRaw($sql);
        public abstract function numRecords($result);
    }
?>
