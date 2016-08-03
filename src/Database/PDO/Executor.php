<?php
namespace Database\PDO;

use \Database\Exceptions\QueryFailedException as QueryFailedException;
use \PDOException;

class Executor
{
    private $connection;
    private static $querys_done = [];
    private static $querys_failed = [];

    private $ERROR_QUERY = "The query just failed.";
    private $ERROR_EXEC = "The insert, update or delete just failed.";
    private $ERROR_COUNT = "The count just failed.";
    
    public $show_exception_message = DEBUG_MODE;

    public function __construct($connector) {
        $this->connection = $connector;
    }

    public function query($sql, $data) {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($data);
            $rows = $statement->fetchAll();
            $this->addToDone($sql, $data);
            return $rows;
        } catch (PDOException $e) {
            $this->addToFailed($sql, $data, $e);
            $error_msg = $this->show_exception_message ? $this->ERROR_QUERY." TRACE: ".$e->getMessage() : $this->ERROR_QUERY;
            throw new QueryFailedException($error_msg);
        }
    }

    public function exec($sql, $data) {
        try {
            $statement = $this->connection->prepare($sql);
            $result = $statement->execute($data);
            $this->addToDone($sql, $data);
            return $result;
        } catch (PDOException $e) {
            $this->addToFailed($sql, $data, $e);
            $error_msg = $this->show_exception_message ? $this->ERROR_EXEC." TRACE: ".$e->getMessage() : $this->ERROR_EXEC;
            throw new QueryFailedException($error_msg);
        }
    }

    public function count($sql) {
        try {
            $result = $this->connection->query($sql)->fetchColumn();
            $this->addToDone($sql);
            return (int) $result;
        } catch (PDOException $e) {
            $this->addToFailed($sql, $e);
            $error_msg = $this->show_exception_message ? $this->ERROR_COUNT." TRACE: ".$e->getMessage() : $this->ERROR_COUNT;
            throw new QueryFailedException($error_msg);
        }
    }

    private function addToDone($sql, $data = null) {
        self::$querys_done[] = [
            "sql" => $sql,
            "data" => $data
        ];
    }

    private function addToFailed($sql, $data = null, $e) {
        self::$querys_failed[] = [
            "sql" => $sql,
            "data" => $data,
            "error" => $e->getMessage()
        ];
    }
}
