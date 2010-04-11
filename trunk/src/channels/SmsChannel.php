<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');

    class SmsChannel extends Channel
    {
        public static $carriers = array(
            'Verizon' => 'vtext.com',
            'Cingular' => 'txt.att.net',
            'AT&amp;T' => 'txt.att.net'
        );

        public function getSubjectFormat()
        {
            return $this->config['subject'];
        }

        public function getMessageFormat()
        {
            return $this->config['message'];
        }

        public function getSubject($monitor)
        {
            return sprintf($this->config['subject'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function getMessage($monitor)
        {
            return sprintf($this->config['message'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function getNumber()
        {
            return $this->config['number'];
        }

        public function doNotify($monitor)
        {
            mail($this->config['number'] . '@' . SmsChannel::gatewayFromCarrier($this->config['carrier']), $this->getSubject($monitor), $this->getMessage($monitor));
        }

        public function getName()
        {
            return 'SMS Channel';
        }

        public function getDescription()
        {
            return 'Sends a text-message (through a free gateway) to notify of service outages.';
        }

        public function getCarrier()
        {
            return $this->config['carrier'];
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(strlen($data['subject']) == 0)
                $errors['subject'] = 'Subject cannot be blank.';
            $this->config['subject'] = $data['subject'];

            if(strlen($data['message']) == 0)
                $errors['message'] = 'Message cannot be blank.';
            $this->config['message'] = $data['message'];

            if(strlen($data['number']) < 7 || !is_numeric($data['number']) || intval($data['number']) < 0)
                $errors['number'] = 'Invalid number.  Must be numeric and at least 7 digits.';
            $this->config['number'] = $data['number'];

            if(!array_key_exists($data['carrier'], SmsChannel::$carriers))
                $errors['carrier'] = 'Invalid carrier.';
            $this->config['carrier'] = $data['carrier'];

            return $errors;
        }

        public function customProcessDelete()
        {
        }

        public function __toString()
        {
            return $this->config['number'] . ' (' . $this->config['carrier'] . ')';
        }

        public static function gatewayFromCarrier($carrier)
        {
            return SmsChannel::$carriers[$carrier];
        }
    }
?>
