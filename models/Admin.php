<?php
require_once 'Utilisateur.php';

class Admin extends Utilisateur {
    private $conn;
    private $table_name = "admins";

    public $salaire;

    public function __construct($db) {
        parent::__construct($db);
        $this->conn = $db;
    }

    public function create() {
        // First create the user
        $user_id = parent::create();
        
        if($user_id) {
            // Then create the admin
            $query = "INSERT INTO " . $this->table_name . " (id, salaire) VALUES (:id, :salaire)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id", $user_id);
            $stmt->bindParam(":salaire", $this->salaire);
            
            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function read() {
        $query = "SELECT u.*, a.salaire 
                FROM " . parent::$table_name . " u 
                INNER JOIN " . $this->table_name . " a ON u.id = a.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        // First update the user info
        if(parent::update()) {
            // Then update the admin info
            $query = "UPDATE " . $this->table_name . " SET salaire = :salaire WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":salaire", $this->salaire);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function delete() {
        // First delete from admin table
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            // Then delete from users table
            return parent::delete();
        }
        return false;
    }
}
?> 