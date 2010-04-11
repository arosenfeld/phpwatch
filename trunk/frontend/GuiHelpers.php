<?php
function p($str)
{
    echo $str;
}

class GuiHelpers
{
    private static $allowed_pages = array ('monitors', 'monitor', 'monitor-delete', 'contacts', 'contact',
    'contact-delete', 'channel', 'channel-delete');

    public static function getPage($requested)
    {
        if(in_array($requested, GuiHelpers::$allowed_pages))
            return $requested;
        return 'monitors';
    }

    public static function getMonitors()
    {
        $monitors = array();
        $mrows = $GLOBALS['PW_DB']->executeSelect('*', 'monitors', '');
        foreach($mrows as $m)
            $monitors[] = Monitor::fetch($m);
        return $monitors;
    }

    public static function getContactsByMonitor($monitor)
    {
        if(sizeof($monitor->getChanIds()) == 0)
            return null;
        return $GLOBALS['PW_DB']->executeRaw('SELECT DISTINCT contacts.id, contacts.name FROM contacts, channels WHERE channels.owner =
        contacts.id AND channels.id IN (' . implode(',', $monitor->getChanIds()) . ')');
    }
    public static function getStatistic($field)
    {
        $r = $GLOBALS['PW_DB']->executeRaw('SELECT COUNT(monitors.id) AS monitor_count, COUNT(contacts.id) AS contact_count
        FROM monitors, contacts');
        $r = $r[0];
        $lcount = $GLOBALS['PW_DB']->executeRaw('SELECT COUNT(*) AS log_count FROM statistics');
        $r['log_count'] = $lcount[0]['log_count'];
        $offline = $GLOBALS['PW_DB']->executeRaw('SELECT MAX(`key`) AS last_offline FROM `statistics` WHERE series LIKE \'monitor%\' AND
        value = 0');
        $r['last_offline'] = $offline[0]['last_offline'];
        return $r[$field];
    }

    public static function formatDateLong($timestamp)
    {
        return date('D, M j, Y G:i:s T', $timestamp);
    }

    public static function getAllChannels($id = null)
    {
        $arr = array();
        $contacts = $GLOBALS['PW_DB']->executeSelect('*', 'contacts', ($id == null ? '' : 'WHERE id=' . intval($id)));
        foreach($contacts as $c)
        {
                $arr[$c['id']] = array(
                    'name' => $c['name'],
                    'channels' => $GLOBALS['PW_DB']->executeSelect('id', 'channels', 'WHERE owner=' . $c['id'])
            );
        }
        return $arr;
    }

    public static function getHoursMinutes($target_time)
    {
        if($target_time == 0)
            return array(1, 0);
        else if($target_time > time())
            return array(floor(($target_time - time()) / 60), ($target_time - time()) % 60);
        else
            return array(0, 0);
    }

    public static function checkVersion()
    {
    }
}
?>
