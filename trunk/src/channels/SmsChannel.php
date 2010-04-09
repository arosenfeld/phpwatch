<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');

    class SmsChannel extends Channel
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

        public function setNumber($number)
        {
            $this->config['number'] = $number;
        }

        public function setCarrier($carrier)
        {
            $this->config['carrier'] = $carrier;
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

        public function __toString()
        {
            return $this->config['number'];
        }

        public static function gatewayFromCarrier($carrier)
        {
            $gateways = array(
                'Verizon' => 'vtext.com',
                'Cingular' => 'txt.att.net',
                'AT&amp;T' => 'txt.att.net'
            );

            return $gateways[$carrier];
        }
    }
?>
