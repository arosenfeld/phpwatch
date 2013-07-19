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
        switch ($field) {
            case 'monitor_count':
                $result = $GLOBALS['PW_DB']->executeRaw('SELECT COUNT(*) AS monitor_count FROM monitors');
                if (isset($result[0]['monitor_count'])) {
                    return $result[0]['monitor_count'];
                }
                return 0;
                break;
            
            case 'contact_count':
                $result = $GLOBALS['PW_DB']->executeRaw('SELECT COUNT(*) AS contact_count FROM contacts');
                if (isset($result[0]['contact_count'])) {
                    return $result[0]['contact_count'];
                }
                return 0;
                break;
            
            case 'log_count':
                $result = $GLOBALS['PW_DB']->executeRaw('SELECT COUNT(*) AS log_count FROM statistics');
                if (isset($result[0]['log_count'])) {
                    return $result[0]['log_count'];
                }
                return 0;
                break;
            
            case 'last_offline':
                $result = $GLOBALS['PW_DB']->executeRaw('SELECT MAX(`key`) AS last_offline FROM `statistics` WHERE series LIKE \'monitor%\' AND value = 0');
                if (isset($result[0]['last_offline'])) {
                    return $result[0]['last_offline'];
                }
                return 0;
                break;
        }
    }

    public static function formatDateLong($timestamp)
    {
        if($timestamp == 0)
            return 'N/A';
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

    public static function isExpanded($id)
    {
        if(!isset($_GET['expand']) || $_GET['expand'] == $id || $_GET['expand'] == 'all')
            return true;
        return false;
    }

    public static function checkVersion()
    {
        $sock = @fsockopen('phpwatch.net', 80, $errno, $errstr, 5);
        if(!$sock)
        {
            return array(false, '<p class="version-notice-bad">Request timed out.  Check <a
            href="http://phpwatch.net" target="_new">here</a> for updates.');
        }
        $req  = "GET /version HTTP/1.1\r\n";
        $req .= "Host: phpwatch.net\r\n";
        $req .= "Connection: Close\r\n\r\n";    fwrite($sock, $req);

        $resp = '';
        while(!feof($sock))
            $resp .= fread($sock, 1024);
        fclose($sock);
        $resp = explode("\r\n", $resp);
        $resp = trim($resp[sizeof($resp) - 1]);

        list($version, $date, $url) = explode('|', $resp);
        if(strtolower(trim($version)) != strtolower(trim(PW2_VERSION)))
            return array(false, '<p class="version-notice-bad">New version available <a href="' . $url . '"
            target="_new">here</a></p>');
        return array(true, 'Up to date');
    }
}
?>
