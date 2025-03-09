<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit();
}

require_once 'config/database.php';
require_once 'models/Commande.php';
require_once 'models/Produit.php';

// Vérifier si c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// Récupérer les données envoyées
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cart']) || empty($data['cart'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Panier vide']);
    exit();
}

try {
    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    // Créer une nouvelle commande
    $commande = new Commande($db);
    $commande->date = date('Y-m-d H:i:s');
    $commande->quantite = count($data['cart']);
    $commande->state = 'pending'; // État initial de la commande
    $commande->client_id = $_SESSION['user_id'];
    
    // Insérer la commande dans la base de données
    $commande_id = $commande->create();
    
    if (!$commande_id) {
        throw new Exception('Erreur lors de la création de la commande');
    }
    
    // Récupérer l'ID de la commande nouvellement créée
    $commande->id = $commande_id;
    
    // Pour chaque produit dans le panier, ajouter à la commande
    foreach ($data['cart'] as $item) {
        $produit_id = $item['id'];
        $quantite = $item['quantity'];
        
        // Ajouter le produit à la commande
        if (!$commande->addProduct($produit_id, $quantite)) {
            throw new Exception('Erreur lors de l\'ajout du produit à la commande');
        }
    }
    
    // Si tout s'est bien passé, renvoyer un succès
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'message' => 'Commande enregistrée avec succès',
        'order_id' => $commande_id
    ]);
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Une erreur est survenue: ' . $e->getMessage()
    ]);
}
?> 