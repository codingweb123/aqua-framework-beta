<?php
namespace Database;
class DatabaseError
{
    private static string|null $type, $content;
    public function __construct($type, $content = null)
    {
        self::$type = $type;
        self::$content = $content;
    }
    public static function getType()
    {
        return self::$type;
    }
    public static function getContent()
    {
        return self::$content;
    }
}
class Database {
    private static $db,$init;
    public function __construct()
    {
        global $config;
        self::$init = $this->__init();
    }
    public function __init(){
        $conn = new \SQLite3(__DIR__ . '/db.sqlite');
        if ($conn){
            self::$db = $conn;
            return $conn;
        }else
            return new DatabaseError("connection", "Connection to mysql server failed!");
    }
    public static function checkForErrorsAndThrow()
    {
        if (get_class(self::$init) == "Database\\DatabaseError")
            ddd("Database Error: \n" . self::$init::getContent());
    }
    public static function query($ql, $assoc = false){
        $query = self::$db->query($ql);
        if ($query)
            return $assoc ? mysqli_fetch_assoc($query) : $query;
        else
            return new DatabaseError("query", "Database Query failed!\nQuery: $ql\nError: " . self::$db->error);
    }
}