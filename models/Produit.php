<?php
class Produit {
    private $conn;
    private $table_name = "produits";

    public $id;
    public $nom;
    public $prix;
    public $description;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nom, prix, description, image) VALUES (:nom, :prix, :description, :image)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET nom = :nom, 
                    prix = :prix, 
                    description = :description, 
                    image = :image 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);
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

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
}
?> 