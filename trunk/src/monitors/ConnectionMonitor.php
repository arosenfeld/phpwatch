<?php
    class ConnectionMonitor extends Monitor
    {
        public function setTimeout($seconds)
        {
            $this->config['timeout'] = $seconds;
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
    }
?>
