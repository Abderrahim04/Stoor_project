<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/cart.css">
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
                <a href="cart.php" class="cart-link active">
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
            <h1>Votre Panier</h1>
            <p>Vérifiez vos articles et procédez au paiement</p>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <div class="cart-container">
                <!-- Cart Items -->
                <div class="cart-items">
                    <h2>Articles dans votre panier (0)</h2>
                    
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Votre panier est vide</h3>
                        <p>Vous n'avez pas encore ajouté d'articles à votre panier.</p>
                        <a href="products.php" class="continue-shopping-btn">
                            <i class="fas fa-arrow-left"></i> Commencer vos achats
                        </a>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2>Récapitulatif</h2>
                    
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span>0 DH</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span>Gratuite</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>0 DH</span>
                    </div>
                    
                    <div class="promo-code">
                        <input type="text" placeholder="Code promo" disabled>
                        <button disabled>Appliquer</button>
                    </div>
                    
                    <button class="checkout-btn" disabled>
                        Procéder au paiement <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <div class="payment-methods">
                        <p>Nous acceptons</p>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fas fa-money-bill-wave"></i>
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
    
    // Cart functionality
    document.querySelectorAll('.quantity-btn.plus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
            updateItemTotal(this);
            updateCartTotal();
        });
    });
    
    document.querySelectorAll('.quantity-btn.minus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateItemTotal(this);
                updateCartTotal();
            }
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.cart-item').remove();
            updateCartTotal();
            updateCartCount();
        });
    });
    
    document.querySelector('.clear-cart').addEventListener('click', function() {
        document.querySelectorAll('.cart-item').forEach(item => {
            item.remove();
        });
        updateCartTotal();
        updateCartCount();
    });
    
    function updateItemTotal(element) {
        const item = element.closest('.cart-item');
        const price = parseInt(item.querySelector('.item-price').textContent);
        const quantity = parseInt(item.querySelector('.quantity-input').value);
        item.querySelector('.item-total').textContent = (price * quantity) + ' DH';
    }
    
    function updateCartTotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(element => {
            total += parseInt(element.textContent);
        });
        
        document.querySelector('.summary-row.total span:last-child').textContent = total + ' DH';
        document.querySelector('.summary-row:first-child span:last-child').textContent = total + ' DH';
    }
    
    function updateCartCount() {
        const count = document.querySelectorAll('.cart-item').length;
        document.querySelector('.cart-count').textContent = count;
        document.querySelector('.cart-items h2').textContent = `Articles dans votre panier (${count})`;
    }
    </script>
</body>
</html> 