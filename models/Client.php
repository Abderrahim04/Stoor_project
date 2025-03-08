<?php
require_once 'Utilisateur.php';

class Client extends Utilisateur {
    private $conn;
    private $table_name = "clients";

    public $teleohon;
    public $adress;

    public function __construct($db) {
        parent::__construct($db);
        $this->conn = $db;
    }

    public function create() {
        // First create the user
        $user_id = parent::create();
        
        if($user_id) {
            // Then create the client
            $query = "INSERT INTO " . $this->table_name . " (id, teleohon, adress) VALUES (:id, :teleohon, :adress)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id", $user_id);
            $stmt->bindParam(":teleohon", $this->teleohon);
            $stmt->bindParam(":adress", $this->adress);
            
            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function read() {
        $query = "SELECT u.*, c.teleohon, c.adress 
                FROM " . parent::$table_name . " u 
                INNER JOIN " . $this->table_name . " c ON u.id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        // First update the user info
        if(parent::update()) {
            // Then update the client info
            $query = "UPDATE " . $this->table_name . " 
                    SET teleohon = :teleohon, 
                        adress = :adress 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":teleohon", $this->teleohon);
            $stmt->bindParam(":adress", $this->adress);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function delete() {
        // First delete from client table
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