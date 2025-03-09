<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Produit.php';

$database = new Database();
$db = $database->getConnection();

$produit = new Produit($db);
$result = $produit->read();
$products = $result->fetchAll(PDO::FETCH_ASSOC);

// Filtrer par catégorie si spécifié
$category = isset($_GET['category']) ? $_GET['category'] : '';
$category_title = '';

if ($category) {
    $category_title = ucfirst($category);
}

// Recherche si spécifiée
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_title ? $category_title : 'Tous les produits'; ?> - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-bar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-tshirt"></i>
                Fashion Store
            </a>
            <div class="menu-icon">
                <i class="fas fa-bars"></i>
            </div>
            <div class="nav-links">
                <a href="products.php" class="active">
                    <i class="fas fa-tags"></i>
                    Produits
                </a>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    Panier
                    <span class="cart-count">0</span>
                </a>
                <a href="profile.php" class="profile-link">
                    <i class="fas fa-user"></i>
                    Mon Profil
                </a>
                <a href="logout.php" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><?php echo $category_title ? 'Collection ' . $category_title : 'Tous nos produits'; ?></h1>
            <p>Découvrez notre sélection de vêtements et accessoires</p>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <!-- Search Bar -->
            <div class="search-container">
                <form action="products.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Rechercher un produit..." class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label>Trier par:</label>
                    <select class="filter-select" id="sort-select">
                        <option value="newest">Nouveautés</option>
                        <option value="price-asc">Prix croissant</option>
                        <option value="price-desc">Prix décroissant</option>
                        <option value="popular">Popularité</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Catégorie:</label>
                    <select class="filter-select" onchange="window.location.href=this.value">
                        <option value="products.php" <?php echo !$category ? 'selected' : ''; ?>>Toutes les catégories</option>
                        <option value="products.php?category=hommes" <?php echo $category == 'hommes' ? 'selected' : ''; ?>>Hommes</option>
                        <option value="products.php?category=femmes" <?php echo $category == 'femmes' ? 'selected' : ''; ?>>Femmes</option>
                        <option value="products.php?category=accessoires" <?php echo $category == 'accessoires' ? 'selected' : ''; ?>>Accessoires</option>
                        <option value="products.php?category=chaussures" <?php echo $category == 'chaussures' ? 'selected' : ''; ?>>Chaussures</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (count($products) > 0): ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card" data-id="<?php echo $product['id']; ?>">
                    <div class="product-image-container">
                        <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" class="product-image">
                        <?php else: ?>
                        <img src="https://via.placeholder.com/300x300?text=Pas+d'image" alt="<?php echo $product['nom']; ?>" class="product-image">
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product['nom']; ?></h3>
                        <div class="product-price"><?php echo $product['prix']; ?> DH</div>
                        <div class="product-buttons">
                            <button class="add-to-cart-btn">
                                <i class="fas fa-cart-plus"></i> Panier
                            </button>
                            <a href="checkout.php?product_id=<?php echo $product['id']; ?>" class="buy-now-btn">
                                <i class="fas fa-bolt"></i> Acheter
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Empty Products State -->
            <div class="empty-products">
                <div class="empty-products-icon">
                    <?php if ($search): ?>
                        <i class="fas fa-search"></i>
                    <?php else: ?>
                        <i class="fas fa-box-open"></i>
                    <?php endif; ?>
                </div>
                <h3>
                    <?php if ($search): ?>
                        Aucun résultat trouvé pour "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        Aucun produit disponible
                    <?php endif; ?>
                </h3>
                <p>
                    <?php if ($search): ?>
                        Essayez avec d'autres mots-clés ou parcourez nos catégories.
                    <?php else: ?>
                        Nous n'avons pas encore de produits dans cette catégorie. Veuillez revenir plus tard.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if (count($products) > 12): ?>
            <div class="pagination">
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>À propos</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-building"></i>Notre Histoire</a></li>
                    <li><a href="#"><i class="fas fa-store"></i>Nos Magasins</a></li>
                    <li><a href="#"><i class="fas fa-briefcase"></i>Carrières</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Service Client</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-envelope"></i>Contact</a></li>
                    <li><a href="#"><i class="fas fa-truck"></i>Livraison</a></li>
                    <li><a href="#"><i class="fas fa-undo"></i>Retours</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Légal</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-file-alt"></i>Conditions d'utilisation</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i>Confidentialité</a></li>
                    <li><a href="#"><i class="fas fa-cookie-bite"></i>Cookies</a></li>
                </ul>
            </div>
            <div class="footer-section social-links">
                <h3>Suivez-nous</h3>
                <ul>
                    <li><a href="#"><i class="fab fa-facebook"></i>Facebook</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i>Instagram</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i>Twitter</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
    document.querySelector('.menu-icon').addEventListener('click', function() {
        document.querySelector('.nav-links').classList.toggle('active');
    });
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const productId = productCard.getAttribute('data-id');
            const productName = productCard.querySelector('.product-title').textContent;
            const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace(' DH', ''));
            const productImage = productCard.querySelector('.product-image').src;
            const cartCount = document.querySelector('.cart-count');
            
            // Récupérer le panier actuel du localStorage ou créer un nouveau panier vide
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Vérifier si le produit existe déjà dans le panier
            const existingProductIndex = cart.findIndex(item => item.id === productId);
            
            if (existingProductIndex !== -1) {
                // Si le produit existe, incrémenter la quantité
                cart[existingProductIndex].quantity += 1;
            } else {
                // Sinon, ajouter le nouveau produit au panier
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: 1
                });
            }
            
            // Sauvegarder le panier mis à jour dans localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Mettre à jour le compteur du panier
            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            // Animation effect
            button.classList.add('added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
            
            alert('Produit ajouté au panier !');
        });
    });
    
    // Mettre à jour le compteur du panier au chargement de la page
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        document.querySelector('.cart-count').textContent = totalItems;
    }
    
    document.addEventListener('DOMContentLoaded', updateCartCount);
    
    // Sorting functionality
    document.getElementById('sort-select').addEventListener('change', function() {
        const sortValue = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        window.location.href = currentUrl.toString();
    });
    </script>
</body>
</html> 