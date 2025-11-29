<?php

class Database {
    private $db;
    private $dbPath;
    
    public function __construct($dbPath = null) {
        if ($dbPath === null) {
            $dbPath = __DIR__ . '/../papernest.sql';
        }
        $this->dbPath = $dbPath;
        $this->connect();
    }
    
    private function connect() {
        try {
            $this->db = new PDO('sqlite:' . $this->dbPath);
            // Set error mode to exceptions
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Return associative arrays by default
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public function fetchOne($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public function close() {
        $this->db = null;
    }
}