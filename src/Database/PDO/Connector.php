<?php
namespace Database\PDO;

use \Database\Exceptions\ConnectionFailedException as ConnectionFailedException;
use \PDOException;
use \PDO;

class Connector
{
    protected static $conn;

    private static $host;
    private static $db;
    private static $user;
    private static $pass;

    static function setup() {
        self::$host = getenv("sql_host");
        self::$db = getenv("sql_db");
        self::$user = getenv("sql_user");
        self::$pass = getenv("sql_pass");
    }

    static function getInstance() {
        if(!self::$conn) {
            try {
                self::setup();
                self::$conn = new PDO('mysql:host='.self::$host.';dbname='.self::$db.';charset=UTF8', self::$user, self::$pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e) {
                throw new ConnectionFailedException('Unable to connect to the database', null, $e);
            }
        }

        return self::$conn;
    }

}
