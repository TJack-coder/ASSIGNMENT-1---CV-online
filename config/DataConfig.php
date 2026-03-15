<?php

namespace config;

use PDO;
use PDOException;

class DataConfig
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db_name = 'cv_online';
    private $user = 'root';
    private $password = '';
    private $port = '3306';

    private function __construct()
    {
        try {

            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO(
                $dsn,
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

        } catch (PDOException $e) {

            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DataConfig();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}