<?php
class Ville {
    private $conn;
    private $table_name = "villes";

    public $id;
    public $libelle;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (libelle) VALUES (:libelle)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":libelle", $this->libelle);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT id, libelle FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET libelle = :libelle WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":libelle", $this->libelle);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 