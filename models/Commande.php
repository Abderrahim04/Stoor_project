<?php
class Commande {
    private $conn;
    private $table_name = "commandes";

    public $id;
    public $date;
    public $quantite;
    public $state;
    public $client_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (date, quantite, state, client_id) 
                VALUES 
                (:date, :quantite, :state, :client_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":quantite", $this->quantite);
        $stmt->bindParam(":state", $this->state);
        $stmt->bindParam(":client_id", $this->client_id);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function read() {
        $query = "SELECT c.*, u.nom as client_nom, u.prenom as client_prenom 
                FROM " . $this->table_name . " c
                INNER JOIN clients cl ON c.client_id = cl.id
                INNER JOIN utilisateurs u ON cl.id = u.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET date = :date,
                    quantite = :quantite,
                    state = :state,
                    client_id = :client_id
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":quantite", $this->quantite);
        $stmt->bindParam(":state", $this->state);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // First delete related records in commande_produit
        $query = "DELETE FROM commande_produit WHERE commande_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        // Then delete the commande
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function addProduct($produit_id, $quantite) {
        $query = "INSERT INTO commande_produit (commande_id, produit_id, quantite) 
                VALUES (:commande_id, :produit_id, :quantite)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":commande_id", $this->id);
        $stmt->bindParam(":produit_id", $produit_id);
        $stmt->bindParam(":quantite", $quantite);
        
        return $stmt->execute();
    }

    public function getProducts() {
        $query = "SELECT p.*, cp.quantite, (p.prix * cp.quantite) as total_prix
                FROM produits p
                INNER JOIN commande_produit cp ON p.id = cp.produit_id
                WHERE cp.commande_id = :commande_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":commande_id", $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT c.*, u.nom as client_nom, u.prenom as client_prenom 
                FROM " . $this->table_name . " c
                INNER JOIN clients cl ON c.client_id = cl.id
                INNER JOIN utilisateurs u ON cl.id = u.id
                WHERE c.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getTotalAmount() {
        $total = 0;
        $products = $this->getProducts();
        
        while ($product = $products->fetch(PDO::FETCH_ASSOC)) {
            $total += $product['total_prix'];
        }
        
        return $total;
    }
}
?> 