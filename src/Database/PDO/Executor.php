<?php
namespace Database\PDO;

use \Database\Exceptions\QueryFailedException as QueryFailedException;
use \PDOException;

class Executor
{
    private $connection;
    private static $querys_done = [];
    private static $querys_failed = [];

    public $show_exception_message = DEBUG_MODE;

    private $current_statement;

    public function __construct($connector) {
        $this->connection = $connector;
        $this->current_statement = null;
    }

    public function prepare($sql, $data = null) {
        $this->current_statement = null;

        try {
            $this->current_statement = $this->connection->prepare($sql);
            $result = $this->current_statement->execute($data);
            $this->addToDone($sql, $data);
            return $result;
        } catch (PDOException $e) {
            $this->addToFailed($sql, $data, $e);
            $error_msg = $this->show_exception_message ? "DEBUG TRACE: ".$e->getMessage() : "The query just failed.";
            throw new QueryFailedException($error_msg);
        }
    }

    public function count() {
        return (int) $this->current_statement->fetchColumn();
    }

    public function exec() {
        return $this->current_statement;
    }

    public function fetch() {
        return $this->current_statement->fetch();
    }

    public function fetchAll() {
        return $this->current_statement->fetchAll();
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
