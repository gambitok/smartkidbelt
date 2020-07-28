<?php

require_once __DIR__ . '/mysql_class.php';
require_once __DIR__ . '/mysql_class_toko.php';

class DbSingleton
{
    private static $instanceDb;
    private static $instanceTokoDb;

    public static function getDbm()
    {
        if (self::$instanceDb === null) {
            self::$instanceDb = new dbm;
            self::$instanceDb->connect();
        }

        return self::$instanceDb;
    }

    public static function getTokoDb()
    {
        if (self::$instanceTokoDb === null) {
            self::$instanceTokoDb = new dbt;
            self::$instanceTokoDb->connect();
        }

        return self::$instanceTokoDb;
    }
}