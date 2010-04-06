<?php
require_once('../common.php');

function p($str)
{
    echo $str;
}

class GuiHelpers
{
    public static function getMonitors()
    {
        $monitors = array();
        $mrows = $GLOBALS['PW_DB']->executeSelect('*', 'monitors', '');
        foreach($mrows as $m)
            $monitors[] = Monitor::fetch($m);
        return $monitors;
    }
}
?>
