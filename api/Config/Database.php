<?php

namespace api\Config;

use PDO;
use PDOException;

class Database {

    private string $host;
    private string $username;
    private string $password;
    private string $db_Name;

    public function __construct(string $host = 'localhost', string $username = 'root', string $password = '', string $db_Name = 'product_management')
    {
        $this->username = $username;
        $this->host = $host;
        $this->db_Name = $db_Name;
        $this->password = $password;
    }

    public function connect(): PDO
    {
        $pdo = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_Name . ";charset=utf8";
            $pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            return null;
        }
        return $pdo;
    }
}
