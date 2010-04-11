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

        public static function get($series)
        {
            $total = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series));
            $week = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series, ' AND `key`>' . (time() - 7*60*60*24)));
            $day = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series, ' AND `key`>' . (time() - 60*60*24)));
            return array($total[0], $week[0], $day[0]);
        }

        private static function getSQL($series, $suffix = '')
        {
            return 'SELECT COUNT(*) AS total, (SELECT COUNT(*) FROM statistics WHERE series=\'' .  $series . '\' AND
            value=1' . $suffix . ') AS online FROM statistics WHERE series=\'' . $series . '\'' . $suffix;
        }
    }
?>
