<?php
    require_once(dirname(__FILE__) . '/config.php');
    require_once(PW2_PATH . '/src/db_schemes/' . $PW2_CONFIG['db_scheme'] . '.php');
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Contact.php');
    require_once(PW2_PATH . '/src/Statistics.php');

    $mon_handle = opendir(PW2_PATH . '/src/monitors');
    while(false !== ($file = readdir($mon_handle)))
    {
        if(strpos($file, '.php') !== false)
        {
            require_once(PW2_PATH . '/src/monitors/' . $file);
        }
    }
    closedir($mon_handle);
    $chan_handle = opendir(PW2_PATH . '/src/channels');
    while(false !== ($file = readdir($chan_handle)))
    {
        if(strpos($file, '.php') !== false)
        {
            require_once(PW2_PATH . '/src/channels/' . $file);
        }
    }
    closedir($chan_handle);

    $GLOBALS['PW_DB'] = new MySQL($PW2_CONFIG['db_info']['host'], $PW2_CONFIG['db_info']['db'], $PW2_CONFIG['db_info']['user'], $PW2_CONFIG['db_info']['pass']);
    if($GLOBALS['PW_DB']->connect() === false)
        die('Unable to connect to database.');
?>
