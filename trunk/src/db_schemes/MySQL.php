<?php
    require_once(PW2_PATH . '/src/QueryBuilder.php');
    class MySQL extends QueryBuilder
    {
        public function connect()
        {
            $this->link = @mysql_connect($this->host, $this->user, $this->pw);
            if(!$this->link)
                return false;
            mysql_select_db($this->db);
            return true;
        }

        private function fetchAssoc($result)
        {
            return mysql_fetch_assoc($result);
        }

        public function query($sql)
        {
            return mysql_query($sql, $this->link);
        }

        public function executeSelectOne($fields, $table, $suffix)
        {
            return $this->fetchAssoc($this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix));
        }

        public function executeSelect($fields, $table, $suffix)
        {
            $rows = array();
            $result = $this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix);
            while($row = $this->fetchAssoc($result))
                $rows[] = $row;
            return $rows;
        }

        public function executeInsert($fields, $table)
        {
            $this->query('INSERT INTO `' . $table . '` (`' . implode('`,`', array_keys($fields)) . '`) VALUES (\'' .
                implode('\',\'', array_values($fields)) . '\')');
            return mysql_insert_id($this->link);
        }

        public function executeUpdate($fields, $table, $suffix)
        {
            $updates = array();
            foreach($fields as $k => $v)
                $updates[] = $k . '=\'' . $v . '\'';
            return $this->query('UPDATE `' . $table . '` SET ' . implode(',', $updates) . ' ' . $suffix);
        }

        public function executeDelete($table, $suffix)
        {
            return $this->query('DELETE FROM `' . $table . '` ' . $suffix);
        }

        public function executeRaw($query)
        {
            $result = $this->query($query);
            while($row = $this->fetchAssoc($result))
                $rows[] = $row;
            return $rows;
        }

        public function numRecords($result)
        {
            return mysql_num_rows($result);
        }
    }
?>
