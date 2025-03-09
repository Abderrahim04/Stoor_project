<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Produit.php';
require_once 'models/Commande.php';
require_once 'models/Client.php';

// Créer une instance de la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer les statistiques
// 1. Nombre de produits
$produit = new Produit($db);
$produit_result = $produit->read();
$total_produits = $produit_result->rowCount();

// 2. Nombre de commandes
$commande = new Commande($db);
$commande_result = $commande->read();
$total_commandes = $commande_result->rowCount();

// 3. Revenus totaux (calculés à partir des commandes)
$revenus_totaux = 0;
$commandes = $commande_result->fetchAll(PDO::FETCH_ASSOC);
foreach ($commandes as $cmd) {
    // Pour chaque commande, récupérer ses produits et calculer le total
    $commande->id = $cmd['id'];
    $produits_commande = $commande->getProducts();
    
    if ($produits_commande) {
        while ($p = $produits_commande->fetch(PDO::FETCH_ASSOC)) {
            $revenus_totaux += ($p['prix'] * $p['quantite']);
        }
    }
}

// 4. Commandes récentes (5 dernières)
$query = "SELECT c.*, u.nom as client_nom, u.prenom as client_prenom 
          FROM commandes c
          INNER JOIN clients cl ON c.client_id = cl.id
          INNER JOIN utilisateurs u ON cl.id = u.id
          ORDER BY c.date DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$commandes_recentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-tshirt"></i>
                    <span>Fashion Store</span>
                </div>
                <button class="close-sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="admin-profile">
                <div class="admin-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="admin-info">
                    <h3>Admin</h3>
                    <p>Administrateur</p>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="indexadmin.php">
                            <i class="fas fa-home"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_products.php">
                            <i class="fas fa-box"></i>
                            <span>Produits</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_orders.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Commandes</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Rechercher...">
                        <i class="fas fa-search"></i>
                    </div>
                    
                    <div class="notifications">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <span class="badge">3</span>
                        </button>
                    </div>
                </div>
            </header>
            
            <div class="dashboard">
                <!-- Hero Section -->
                <div class="dashboard-header">
                    <h1 class="page-title">Tableau de bord</h1>
                    <p class="dashboard-subtitle">Bienvenue dans votre espace d'administration</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon orders">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Commandes</h3>
                            <p class="stat-number"><?php echo $total_commandes; ?></p>
                            <p class="stat-info">Gestion des commandes</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon revenue">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Revenus</h3>
                            <p class="stat-number"><?php echo number_format($revenus_totaux, 2); ?> DH</p>
                            <p class="stat-info">Revenus totaux</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon products">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Produits</h3>
                            <p class="stat-number"><?php echo $total_produits; ?></p>
                            <p class="stat-info">Gestion des produits</p>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Recent Orders -->
                <div class="recent-orders">
                    <div class="section-header">
                        <h2>Commandes récentes</h2>
                        <a href="admin_orders.php" class="view-all">Voir tout</a>
                    </div>
                    
                    <?php if (count($commandes_recentes) > 0): ?>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commandes_recentes as $cmd): ?>
                                <tr>
                                    <td>#<?php echo $cmd['id']; ?></td>
                                    <td><?php echo $cmd['client_nom'] . ' ' . $cmd['client_prenom']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($cmd['date'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($cmd['state']); ?>">
                                            <?php echo $cmd['state']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="admin_orders.php?id=<?php echo $cmd['id']; ?>" class="view-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Aucune commande récente</h3>
                        <p>Les commandes récentes apparaîtront ici une fois que les clients commenceront à passer des commandes.</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-header">
                        <h2>Actions rapides</h2>
                    </div>
                    
                    <div class="actions-grid">
                        <a href="admin_products.php?action=add" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <h3>Ajouter un produit</h3>
                        </a>
                        
                        <a href="admin_orders.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3>Gérer les commandes</h3>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Toggle sidebar on mobile
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });
    
    document.querySelector('.close-sidebar').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.remove('active');
    });
    
    // Fonctionnalité de recherche
    document.querySelector('.search-box input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                window.location.href = `admin_products.php?search=${encodeURIComponent(searchTerm)}`;
            }
        }
    });
    
    // Animation des cartes statistiques
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
    </script>
</body>
</html> 