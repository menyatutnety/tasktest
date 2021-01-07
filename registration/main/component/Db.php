<?php

class Db
{
    public static function getTablePatch()
    {
        $paramsPath = ROOT . '/config/db.php';
        return include($paramsPath);
    }
    public static function getUsersTable()
    {
        $db = simplexml_load_file(self::getTablePatch()['Users']);
        return $db;
    }

}