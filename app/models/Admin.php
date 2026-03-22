<?php

class AdminModel {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAll($table){
        $stmt = $this->conn->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($table, $name){
        $stmt = $this->conn->prepare(
            "INSERT INTO $table(name) VALUES (?)"
        );
        return $stmt->execute([$name]);
    }

    public function delete($table, $id){
        $stmt = $this->conn->prepare(
            "DELETE FROM $table WHERE id=?"
        );
        return $stmt->execute([$id]);
    }
}