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

        public function customProcessAddEdit($data, $errors)
        {
            if(strlen($data['subject']) == 0)
                $errors['subject'] = 'Subject cannot be blank.';
            $this->config['subject'] = $data['subject'];

            if(strlen($data['message']) == 0)
                $errors['message'] = 'Message cannot be blank.';
            $this->config['message'] = $data['message'];

            if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $data['address']))
                $errors['address'] = 'E-mail address is invalid.';
            $this->config['address'] = $data['address'];

            return $errors;
        }

        public function customProcessDelete()
        {
        }
        
        public function __toString()
        {
            return $this->config['address'];
        }
    }
?>
