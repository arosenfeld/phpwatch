<?php
    require_once(dirname(__FILE__) . '/config.php');

    if($PW2_CONFIG['db_info']['host'] == '')
        header('Location: ./install/index.php');
    else
        header('Location: ./frontend/index.php');
?>
