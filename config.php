<?php
    $PW2_CONFIG = array(
        'db_scheme' => 'MySQL',
        'db_info' => array(
            'host' => '',
            'user' => '',
            'pass' => '',
            'db' => ''
        ),
        'path' => dirname(__FILE__),
    );

    define('PW2_VERSION', '2.0.4 Beta');
    define('PW2_PATH', $PW2_CONFIG['path']);
?>
