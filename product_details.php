<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Produit.php';

// Vérifier si l'ID du produit est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$produit = new Produit($db);
$produit->id = $_GET['id'];
$product_details = $produit->readOne();

// Si le produit n'existe pas, rediriger vers la page des produits
if (!$product_details) {
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product_details['nom']; ?> - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="assets/css/product_details.css">
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
                <a href="products.php">
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Accueil</a> &gt;
            <a href="products.php">Produits</a> &gt;
            <span><?php echo $product_details['nom']; ?></span>
        </div>
    </div>

    <!-- Product Details Section -->
    <section class="product-details-section">
        <div class="container">
            <div class="product-details-container">
                <div class="product-gallery">
                    <div class="main-image">
                        <?php if (!empty($product_details['image'])): ?>
                        <img src="<?php echo $product_details['image']; ?>" alt="<?php echo $product_details['nom']; ?>" id="main-product-image">
                        <?php else: ?>
                        <img src="https://via.placeholder.com/600x600?text=Pas+d'image" alt="<?php echo $product_details['nom']; ?>" id="main-product-image">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="product-info">
                    <h1 class="product-title"><?php echo $product_details['nom']; ?></h1>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-count">(24 avis)</span>
                    </div>
                    
                    <div class="product-price">
                        <?php if (isset($product_details['old_price']) && $product_details['old_price'] > 0): ?>
                        <span class="old-price"><?php echo $product_details['old_price']; ?> DH</span>
                        <?php endif; ?>
                        <span class="current-price"><?php echo $product_details['prix']; ?> DH</span>
                    </div>
                    
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo !empty($product_details['description']) ? $product_details['description'] : 'Aucune description disponible pour ce produit.'; ?></p>
                    </div>
                    
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" value="1" min="1" class="quantity-input" id="product-quantity">
                            <button class="quantity-btn plus"><i class="fas fa-plus"></i></button>
                        </div>
                        
                        <button class="add-to-cart-btn" data-id="<?php echo $product_details['id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                        
                        <button class="wishlist-btn">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Disponibilité:</span>
                            <span class="meta-value in-stock">En stock</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Catégorie:</span>
                            <span class="meta-value">Vêtements</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="related-products">
                <h2>Produits similaires</h2>
                <div class="products-grid">
                    <!-- Ces produits seraient normalement chargés dynamiquement depuis la base de données -->
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="https://via.placeholder.com/300x300?text=Produit+Similaire" alt="Produit Similaire" class="product-image">
                            <div class="product-actions">
                                <button class="action-btn"><i class="fas fa-heart"></i></button>
                                <button class="action-btn add-to-cart-btn"><i class="fas fa-shopping-cart"></i></button>
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Produit Similaire 1</h3>
                            <div class="product-price">399 DH</div>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="https://via.placeholder.com/300x300?text=Produit+Similaire" alt="Produit Similaire" class="product-image">
                            <div class="product-actions">
                                <button class="action-btn"><i class="fas fa-heart"></i></button>
                                <button class="action-btn add-to-cart-btn"><i class="fas fa-shopping-cart"></i></button>
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Produit Similaire 2</h3>
                            <div class="product-price">499 DH</div>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="https://via.placeholder.com/300x300?text=Produit+Similaire" alt="Produit Similaire" class="product-image">
                            <div class="product-actions">
                                <button class="action-btn"><i class="fas fa-heart"></i></button>
                                <button class="action-btn add-to-cart-btn"><i class="fas fa-shopping-cart"></i></button>
                                <button class="action-btn"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Produit Similaire 3</h3>
                            <div class="product-price">599 DH</div>
                        </div>
                    </div>
                </div>
            </div>
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
    
    // Quantity selector
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.quantity-input');
    
    minusBtn.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    });
    
    plusBtn.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        quantityInput.value = value + 1;
    });
    
    // Add to cart
    document.querySelector('.add-to-cart-btn').addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const quantity = parseInt(document.getElementById('product-quantity').value);
        const cartCount = document.querySelector('.cart-count');
        
        // Récupérer les informations du produit
        const productName = document.querySelector('.product-title').textContent;
        const productPrice = document.querySelector('.current-price').textContent.replace(' DH', '');
        const productImage = document.getElementById('main-product-image').src;
        
        // Récupérer le panier actuel du localStorage ou créer un nouveau panier vide
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Vérifier si le produit existe déjà dans le panier
        const existingProductIndex = cart.findIndex(item => item.id === productId);
        
        if (existingProductIndex !== -1) {
            // Si le produit existe, incrémenter la quantité
            cart[existingProductIndex].quantity += quantity;
        } else {
            // Sinon, ajouter le nouveau produit au panier
            cart.push({
                id: productId,
                name: productName,
                price: parseFloat(productPrice),
                image: productImage,
                quantity: quantity
            });
        }
        
        // Sauvegarder le panier mis à jour dans localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Mettre à jour le compteur du panier
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;
        
        // Animation effect
        this.classList.add('added');
        setTimeout(() => {
            this.classList.remove('added');
        }, 1000);
        
        alert(`${quantity} produit(s) ajouté(s) au panier !`);
    });
    
    // Wishlist
    document.querySelector('.wishlist-btn').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.classList.add('active');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.classList.remove('active');
        }
    });
    </script>
</body>
</html> 