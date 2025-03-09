<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Produit.php';

$database = new Database();
$db = $database->getConnection();

$produit = new Produit($db);
$products = $produit->read();

// Vérifier si on est en mode ajout
$is_add_mode = isset($_GET['action']) && $_GET['action'] === 'add';

// Vérifier si on est en mode suppression
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $produit->id = $_GET['id'];
    if ($produit->delete()) {
        $message = "Produit supprimé avec succès";
        header("Refresh: 2; URL=admin_products.php");
    } else {
        $error = "Une erreur s'est produite lors de la suppression du produit";
    }
}

// Message de succès ou d'erreur
$message = '';
$error = '';

// Traitement du formulaire d'ajout de produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_add_mode) {
    // Récupérer les données du formulaire
    $produit->nom = $_POST['nom'] ?? '';
    $produit->prix = $_POST['prix'] ?? 0;
    $produit->description = $_POST['description'] ?? '';
    
    // Validation des données
    if (empty($produit->nom)) {
        $error = "Le nom du produit est obligatoire";
    } elseif (empty($produit->prix) || !is_numeric($produit->prix)) {
        $error = "Le prix doit être un nombre valide";
    } else {
        // Traitement de l'image si elle est fournie
        $produit->image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/products/';
            
            // Créer le répertoire s'il n'existe pas
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Générer un nom de fichier unique
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_file = $upload_dir . $file_name;
            
            // Déplacer le fichier téléchargé
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $produit->image = $target_file;
            } else {
                $error = "Erreur lors du téléchargement de l'image";
            }
        }
        
        // Si pas d'erreur, ajouter le produit
        if (empty($error)) {
            if ($produit->create()) {
                $message = "Produit ajouté avec succès";
                // Rediriger vers la liste des produits après 2 secondes
                header("Refresh: 2; URL=admin_products.php");
            } else {
                $error = "Une erreur s'est produite lors de l'ajout du produit";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Administration</title>
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
                    <li class="active">
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
                        <input type="text" placeholder="Rechercher un produit...">
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
                        <h1 class="page-title"><?php echo $is_add_mode ? 'Ajouter un produit' : 'Gestion des produits'; ?></h1>
                        <?php if ($is_add_mode): ?>
                        <p class="dashboard-subtitle">Créez un nouveau produit pour votre boutique</p>
                        <?php else: ?>
                        <p class="dashboard-subtitle">Gérez tous vos produits en un seul endroit</p>
                        <?php endif; ?>
                    </div>
                    <?php if (!$is_add_mode): ?>
                    <a href="admin_products.php?action=add" class="add-btn">
                        <i class="fas fa-plus"></i> Ajouter un produit
                    </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($is_add_mode): ?>
                <!-- Formulaire d'ajout de produit -->
                <div class="form-container">
                    <form action="admin_products.php?action=add" method="POST" enctype="multipart/form-data" class="admin-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom du produit</label>
                                <input type="text" id="nom" name="nom" placeholder="Entrez le nom du produit" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="prix">Prix (DH)</label>
                                <input type="number" id="prix" name="prix" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="5" placeholder="Décrivez votre produit..."></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <div class="file-input-container">
                                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                                    <label for="image" class="file-input-label">
                                        <i class="fas fa-cloud-upload-alt"></i> Choisir une image
                                    </label>
                                    <span class="file-name">Aucun fichier choisi</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="admin_products.php" class="cancel-btn">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <!-- Liste des produits -->
                <?php
                $products_data = $products->fetchAll(PDO::FETCH_ASSOC);
                if (count($products_data) > 0): 
                ?>
                <div class="products-table-container">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_data as $product): ?>
                            <tr>
                                <td>#<?php echo $product['id']; ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="product-thumbnail">
                                    <?php else: ?>
                                    <div class="no-image">Pas d'image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $product['nom']; ?></td>
                                <td><?php echo $product['prix']; ?> DH</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="admin_products.php?action=edit&id=<?php echo $product['id']; ?>" class="action-btn edit-btn" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="action-btn delete-btn" data-id="<?php echo $product['id']; ?>" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Aucun produit disponible</h3>
                    <p>Vous n'avez pas encore ajouté de produits. Cliquez sur "Ajouter un produit" pour commencer.</p>
                </div>
                <?php endif; ?>
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
    
    // File input handling
    const fileInput = document.getElementById('image');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Aucun fichier choisi';
            const fileNameElement = this.parentElement.querySelector('.file-name');
            fileNameElement.textContent = fileName;
        });
    }

    // Confirmation de suppression
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                    window.location.href = 'admin_products.php?action=delete&id=' + productId;
                }
            });
        });
    }
    </script>
</body>
</html> 