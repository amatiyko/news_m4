<?php
/**
 * Created by PhpStorm.
 * User: andrewmatiyko
 * Date: 05.11.2017
 * Time: 18:44
 */

class Config
{
    protected static $setting = array();

    public static function get($key) {
        return isset(self::$setting[$key]) ? self::$setting[$key] : null;
    }

    public static function set($key, $value) {
        self::$setting[$key] = $value;
    }
}