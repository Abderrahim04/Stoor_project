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
                    
                    <div class="empty-cart" id="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Votre panier est vide</h3>
                        <p>Vous n'avez pas encore ajouté d'articles à votre panier.</p>
                        <a href="products.php" class="continue-shopping-btn">
                            <i class="fas fa-arrow-left"></i> Commencer vos achats
                        </a>
                    </div>
                    
                    <div id="cart-items-container" style="display: none;">
                        <div class="cart-header">
                            <span class="header-product">Produit</span>
                            <span class="header-price">Prix</span>
                            <span class="header-quantity">Quantité</span>
                            <span class="header-total">Total</span>
                            <span class="header-action"></span>
                        </div>
                        
                        <div id="cart-items-list">
                            <!-- Les produits du panier seront ajoutés ici dynamiquement -->
                        </div>
                        
                        <div class="cart-actions">
                            <a href="products.php" class="continue-shopping-btn">
                                <i class="fas fa-arrow-left"></i> Continuer vos achats
                            </a>
                            <button class="clear-cart" id="clear-cart-btn">
                                <i class="fas fa-trash"></i> Vider le panier
                            </button>
                        </div>
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
    
    // Fonction pour charger le panier depuis localStorage
    function loadCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsList = document.getElementById('cart-items-list');
        const emptyCart = document.getElementById('empty-cart');
        const cartItemsContainer = document.getElementById('cart-items-container');
        const checkoutBtn = document.querySelector('.checkout-btn');
        const promoCodeInput = document.querySelector('.promo-code input');
        const promoCodeBtn = document.querySelector('.promo-code button');
        
        // Mettre à jour le compteur du panier
        const cartCount = document.querySelector('.cart-count');
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;
        
        // Mettre à jour le titre du panier
        document.querySelector('.cart-items h2').textContent = `Articles dans votre panier (${totalItems})`;
        
        // Vider la liste des produits
        cartItemsList.innerHTML = '';
        
        if (cart.length === 0) {
            // Si le panier est vide, afficher le message approprié
            emptyCart.style.display = 'block';
            cartItemsContainer.style.display = 'none';
            checkoutBtn.disabled = true;
            promoCodeInput.disabled = true;
            promoCodeBtn.disabled = true;
        } else {
            // Si le panier a des produits, les afficher
            emptyCart.style.display = 'none';
            cartItemsContainer.style.display = 'block';
            checkoutBtn.disabled = false;
            promoCodeInput.disabled = false;
            promoCodeBtn.disabled = false;
            
            // Ajouter chaque produit à la liste
            cart.forEach((item, index) => {
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.dataset.id = item.id;
                cartItem.dataset.index = index;
                
                cartItem.innerHTML = `
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="item-details">
                        <h3>${item.name}</h3>
                    </div>
                    <div class="item-price">${item.price} DH</div>
                    <div class="quantity-selector">
                        <button class="quantity-btn minus"><i class="fas fa-minus"></i></button>
                        <input type="number" value="${item.quantity}" min="1" class="quantity-input">
                        <button class="quantity-btn plus"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="item-total">${(item.price * item.quantity).toFixed(2)} DH</div>
                    <div class="item-actions">
                        <button class="remove-item"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                
                cartItemsList.appendChild(cartItem);
            });
            
            // Ajouter les event listeners pour les boutons de quantité et de suppression
            addCartItemEventListeners();
        }
        
        // Mettre à jour le total du panier
        updateCartTotal();
    }
    
    // Fonction pour ajouter des event listeners aux éléments du panier
    function addCartItemEventListeners() {
        // Boutons pour augmenter la quantité
        document.querySelectorAll('.quantity-btn.plus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const cartItem = this.closest('.cart-item');
                const index = parseInt(cartItem.dataset.index);
                
                input.value = parseInt(input.value) + 1;
                updateCartItem(index, parseInt(input.value));
            });
        });
        
        // Boutons pour diminuer la quantité
        document.querySelectorAll('.quantity-btn.minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const cartItem = this.closest('.cart-item');
                const index = parseInt(cartItem.dataset.index);
                
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateCartItem(index, parseInt(input.value));
                }
            });
        });
        
        // Champ de quantité qui change manuellement
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartItem = this.closest('.cart-item');
                const index = parseInt(cartItem.dataset.index);
                
                // S'assurer que la quantité est au moins 1
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                
                updateCartItem(index, parseInt(this.value));
            });
        });
        
        // Boutons pour supprimer un produit
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const cartItem = this.closest('.cart-item');
                const index = parseInt(cartItem.dataset.index);
                
                removeCartItem(index);
            });
        });
    }
    
    // Fonction pour mettre à jour un produit dans le panier
    function updateCartItem(index, quantity) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (index >= 0 && index < cart.length) {
            cart[index].quantity = quantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Mettre à jour le total du produit
            const itemPrice = cart[index].price;
            const itemTotal = (itemPrice * quantity).toFixed(2);
            document.querySelector(`.cart-item[data-index="${index}"] .item-total`).textContent = `${itemTotal} DH`;
            
            // Mettre à jour le total du panier
            updateCartTotal();
            
            // Mettre à jour le compteur du panier
            updateCartCount();
        }
    }
    
    // Fonction pour supprimer un produit du panier
    function removeCartItem(index) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (index >= 0 && index < cart.length) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Recharger le panier
            loadCart();
        }
    }
    
    // Fonction pour vider le panier
    function clearCart() {
        localStorage.removeItem('cart');
        loadCart();
    }
    
    // Fonction pour mettre à jour le total du panier
    function updateCartTotal() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        document.querySelector('.summary-row.total span:last-child').textContent = `${total.toFixed(2)} DH`;
        document.querySelector('.summary-row:first-child span:last-child').textContent = `${total.toFixed(2)} DH`;
    }
    
    // Fonction pour mettre à jour le compteur de produits
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        
        document.querySelector('.cart-count').textContent = totalItems;
        document.querySelector('.cart-items h2').textContent = `Articles dans votre panier (${totalItems})`;
    }
    
    // Event listener pour le bouton "Vider le panier"
    document.getElementById('clear-cart-btn').addEventListener('click', clearCart);
    
    // Event listener pour le bouton "Procéder au paiement"
    document.querySelector('.checkout-btn').addEventListener('click', function() {
        window.location.href = 'checkout.php';
    });
    
    // Charger le panier au chargement de la page
    document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html> 