<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Commande.php';
require_once 'models/Produit.php';

$database = new Database();
$db = $database->getConnection();

$commande = new Commande($db);
$orders_result = $commande->read();
$orders = $orders_result->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si on est en mode détail
$is_detail_mode = isset($_GET['id']) && !empty($_GET['id']);
$order_id = $is_detail_mode ? intval($_GET['id']) : 0;

// Si on est en mode détail, récupérer les informations de la commande
$order_details = null;
$order_products = null;
if ($is_detail_mode) {
    $commande->id = $order_id;
    $order_details = $commande->readOne();
    
    if ($order_details) {
        $order_products = $commande->getProducts();
    } else {
        // Rediriger si la commande n'existe pas
        header("Location: admin_orders.php");
        exit();
    }
}

// Vérifier si on a une action à effectuer
$action = isset($_GET['action']) ? $_GET['action'] : '';
$message = '';

if ($action === 'update_status' && isset($_POST['status']) && isset($_POST['order_id'])) {
    $commande->id = $_POST['order_id'];
    $commande_info = $commande->readOne();
    
    if ($commande_info) {
        $commande->date = $commande_info['date'];
        $commande->quantite = $commande_info['quantite'];
        $commande->state = $_POST['status'];
        $commande->client_id = $commande_info['client_id'];
        
        if ($commande->update()) {
            $message = "Le statut de la commande a été mis à jour avec succès.";
        } else {
            $message = "Une erreur est survenue lors de la mise à jour du statut.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Administration</title>
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
                    <li>
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
                    <li class="active">
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
                        <input type="text" placeholder="Rechercher une commande...">
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
                <div class="page-header">
                    <div>
                        <h1 class="page-title"><?php echo $is_detail_mode ? 'Détails de la commande #' . $order_id : 'Gestion des commandes'; ?></h1>
                        <p class="dashboard-subtitle"><?php echo $is_detail_mode ? 'Consultez et modifiez les détails de cette commande' : 'Consultez et gérez toutes les commandes'; ?></p>
                    </div>
                    
                    <?php if ($is_detail_mode): ?>
                    <a href="admin_orders.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Retour aux commandes
                    </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($is_detail_mode && $order_details): ?>
                <!-- Détails de la commande -->
                <div class="order-details">
                    <div class="order-info-card">
                        <div class="order-header">
                            <h2>Informations de la commande</h2>
                            <span class="order-status <?php echo strtolower($order_details['state']); ?>">
                                <?php echo $order_details['state']; ?>
                            </span>
                        </div>
                        
                        <div class="order-info-grid">
                            <div class="info-group">
                                <p class="info-label">Numéro de commande</p>
                                <p class="info-value">#<?php echo $order_details['id']; ?></p>
                            </div>
                            
                            <div class="info-group">
                                <p class="info-label">Date</p>
                                <p class="info-value"><?php echo date('d/m/Y H:i', strtotime($order_details['date'])); ?></p>
                            </div>
                            
                            <div class="info-group">
                                <p class="info-label">Client</p>
                                <p class="info-value"><?php echo $order_details['client_nom'] . ' ' . $order_details['client_prenom']; ?></p>
                            </div>
                            
                            <div class="info-group">
                                <p class="info-label">Total</p>
                                <p class="info-value"><?php echo number_format($commande->getTotalAmount(), 2); ?> DH</p>
                            </div>
                        </div>
                        
                        <div class="order-status-update">
                            <h3>Mettre à jour le statut</h3>
                            <form method="POST" action="admin_orders.php?action=update_status">
                                <input type="hidden" name="order_id" value="<?php echo $order_details['id']; ?>">
                                
                                <div class="status-select-group">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?php echo $order_details['state'] == 'pending' ? 'selected' : ''; ?>>En attente</option>
                                        <option value="processing" <?php echo $order_details['state'] == 'processing' ? 'selected' : ''; ?>>En traitement</option>
                                        <option value="shipped" <?php echo $order_details['state'] == 'shipped' ? 'selected' : ''; ?>>Expédiée</option>
                                        <option value="delivered" <?php echo $order_details['state'] == 'delivered' ? 'selected' : ''; ?>>Livrée</option>
                                        <option value="cancelled" <?php echo $order_details['state'] == 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                                    </select>
                                    
                                    <button type="submit" class="update-status-btn">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="order-products">
                        <h2>Produits commandés</h2>
                        
                        <div class="order-products-list">
                            <?php if ($order_products && $order_products->rowCount() > 0): ?>
                                <?php while ($product = $order_products->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="order-product-item">
                                    <div class="product-image">
                                        <?php if (!empty($product['image'])): ?>
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>">
                                        <?php else: ?>
                                        <div class="no-image">Pas d'image</div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="product-details">
                                        <h3><?php echo $product['nom']; ?></h3>
                                        <p class="product-price"><?php echo $product['prix']; ?> DH</p>
                                    </div>
                                    
                                    <div class="product-quantity">
                                        Quantité: <?php echo $product['quantite']; ?>
                                    </div>
                                    
                                    <div class="product-total">
                                        Total: <?php echo number_format($product['prix'] * $product['quantite'], 2); ?> DH
                                    </div>
                                </div>
                                <?php endwhile; ?>
                                
                                <div class="order-summary">
                                    <div class="summary-row total">
                                        <span>Total</span>
                                        <span><?php echo number_format($commande->getTotalAmount(), 2); ?> DH</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Aucun produit trouvé pour cette commande.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php elseif (!$is_detail_mode): ?>
                <!-- Liste des commandes -->
                <div class="orders-list-container">
                    <div class="orders-filters">
                        <div class="filter-group">
                            <label>Filtrer par statut:</label>
                            <select class="filter-select">
                                <option value="all">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="processing">En traitement</option>
                                <option value="shipped">Expédiée</option>
                                <option value="delivered">Livrée</option>
                                <option value="cancelled">Annulée</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Trier par:</label>
                            <select class="filter-select">
                                <option value="newest">Plus récentes</option>
                                <option value="oldest">Plus anciennes</option>
                                <option value="amount-high">Montant (élevé-bas)</option>
                                <option value="amount-low">Montant (bas-élevé)</option>
                            </select>
                        </div>
                    </div>
                    
                    <?php if (count($orders) > 0): ?>
                    <div class="orders-table-container">
                        <table class="orders-table">
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
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['client_nom'] . ' ' . $order['client_prenom']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['date'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($order['state']); ?>">
                                            <?php echo $order['state']; ?>
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="admin_orders.php?id=<?php echo $order['id']; ?>" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <!-- État vide -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Aucune commande disponible</h3>
                        <p>Les commandes apparaîtront ici une fois que les clients commenceront à passer des commandes.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
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
    
    // Confirmation de suppression
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
                    // Action de suppression
                    alert('Commande supprimée avec succès');
                }
            });
        });
    }
    </script>
</body>
</html> 