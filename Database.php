<?php

class Database
{
    private static $istance;
    private $db;

    private function __construct()
    {
        $this -> db = new PDO("mysql:host=localhost;dbname=registrazione_xample", "root", "",[
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function getInstance()
    {
        if(self::$istance == null)
        {
            self::$istance = new self();
            return self::$istance;
        }
        return self::$istance;
    }

    public function getConnection()
    {
        return $this -> db;
    }
}
