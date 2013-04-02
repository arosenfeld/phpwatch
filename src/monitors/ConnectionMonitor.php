<?php
    class ConnectionMonitor extends Monitor
    {
        public function getName()
        {
            return 'Connection Monitor';
        }

        public function getDescription()
        {
            return 'This type of monitor attempts to establish a socket connection to the desired endpoint.  If
            connection is unsuccessful, the service is considered "offline."';
        }

        public function getTimeout()
        {
            return $this->config['timeout'];
        }

        public function queryMonitor()
        {
            $sock = @fsockopen($this->hostname, $this->port, $errno, $errstr, $this->config['timeout']);
            if($sock)
            {
                fclose($sock);
                return true;
            }
            return false;
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(!is_numeric($data['timeout']) || intval($data['timeout']) <= 0)
                $errors['timeout'] = 'Timeout must be a positive integer.';
            $this->config['timeout'] = intval($data['timeout']);
            return $errors;
        }

        public function customProcessDelete()
        {
        }
    }
?>
