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
            $hostname = $this->hostname;
            
            // Handle https-Requests (Port 443)
            if (443 == $this->port) {
                $hostname = ($this->port == 443 ? 'ssl://' : '') . $hostname;
                
                $context = stream_context_create();
                stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
                stream_context_set_option($context, 'ssl', 'verify_peer', false);
            }
            
            $sock = @fsockopen($hostname, $this->port, $errno, $errstr, $this->config['timeout']);
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
