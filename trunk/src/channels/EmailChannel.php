<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');

    class EmailChannel extends Channel
    {
        private function getSubject($monitor)
        {
            return sprintf($this->config['subject'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        private function getMessage($monitor)
        {
            return sprintf($this->config['message'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function setSubject($subject)
        {
            $this->config['subject'] = $subject;
        }

        public function setMessage($message)
        {
            $this->config['message'] = $message;
        }

        public function setAddress($address)
        {
            $this->config['address'] = $address;
        }

        public function doNotify($monitor)
        {
            mail($this->config['address'], $this->getSubject($monitor), $this->getMessage($monitor));
        }

        public function getName()
        {
            return 'Email Channel';
        }

        public function getDescription()
        {
            return 'Sends an e-mail to notify of service outages.';
        }

        public function __toString()
        {
            return $this->config['address'];
        }
    }
?>
