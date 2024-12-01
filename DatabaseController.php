<?php

class DatabaseController
{
    private $pdo;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . '/config.ini');

        $host = $config['DB_HOST'];
        $dbname = $config['DB_NAME'];
        $username = $config['DB_USER'];
        $password = $config['DB_PASSWORD'];

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    //fetch one row
    public function fetch($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //fetch all rows
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //execute query
    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        return $stmt->execute();
    }

    //bind parameters to query
    private function bindParams($stmt, $params)
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }


    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function isConnected()
    {
        return $this->pdo !== null;
    }
}
