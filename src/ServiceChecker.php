<?php
    require_once(PW2_PATH . '/src/DbObject.php');
    class ServiceChecker
    {
        public static function checkAll()
        {
            $monitors = $GLOBALS['PW_DB']->executeSelect('*', 'monitors', '');
            foreach($monitors as $mrow)
            {
                $monitor = Monitor::fetch($mrow);
                $up = $monitor->poll();
                echo $monitor->getAlias(), $up;
                $monitor->saveToDb();
            }
        }
    }
?>
