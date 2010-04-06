<?php
    class Statistics
    {
        public static function add($series, $key, $value)
        {
            $fields = array(
                'series' => $series,
                'key' => $key,
                'value' => $value
            );
            $GLOBALS['PW_DB']->executeInsert($fields, 'statistics');
        }
    }
?>
