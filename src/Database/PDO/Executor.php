<?php
namespace Database\PDO;

use \Database\Exceptions\QueryFailedException as QueryFailedException;
use \PDOException;

class Executor
{
    private $connection;
    private static $querys_done = [];
    private static $querys_failed = [];

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
            throw new QueryFailedException("The query just failed", 1);
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
            throw new QueryFailedException("The insert, update or delete just failed", 1);
        }
    }

    private function addToDone($sql, $data) {
        self::$querys_done[] = [
            "sql" => $sql,
            "data" => $data
        ];
    }

    private function addToFailed($sql, $data, $e) {
        self::$querys_failed[] = [
            "sql" => $sql,
            "data" => $data,
            "error" => $e->getMessage()
        ];
    }
}
