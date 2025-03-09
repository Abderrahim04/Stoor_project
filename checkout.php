<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';
require_once 'models/Client.php';

$database = new Database();
$db = $database->getConnection();

// Récupérer les informations du client
$client = new Client($db);
$client->id = $_SESSION['user_id'];
// Ici, vous devriez avoir une méthode pour récupérer les informations du client
// $client_info = $client->readOne();

// Message de succès ou d'erreur
$message = '';
$error = '';

// Traitement du formulaire de paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici, vous ajouteriez le code pour traiter le paiement
    // Pour cet exemple, nous allons simplement afficher un message de succès
    $message = "Commande effectuée avec succès ! Merci pour votre achat.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
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

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="container">
            <h1 class="page-title">Finaliser votre achat</h1>
            
            <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
                <div class="alert-actions">
                    <a href="products.php" class="btn">Continuer vos achats</a>
                </div>
            </div>
            <?php else: ?>
            
            <div class="checkout-container">
                <div class="product-summary">
                    <h2>Résumé de la commande</h2>
                    <div id="checkout-products">
                        <!-- Les produits seront ajoutés ici dynamiquement depuis localStorage -->
                    </div>
                    
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span id="checkout-subtotal">0 DH</span>
                        </div>
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="checkout-total">0 DH</span>
                        </div>
                    </div>
                </div>
                
                <div class="payment-form">
                    <h2>Informations de paiement</h2>
                    <form method="POST" action="" id="payment-form">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Adresse de livraison</label>
                            <textarea id="address" name="address" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">Ville</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="zip">Code postal</label>
                                <input type="text" id="zip" name="zip" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-method">Méthode de paiement</label>
                            <select id="payment-method" name="payment-method" required>
                                <option value="">Sélectionnez une méthode</option>
                                <option value="card">Carte bancaire</option>
                                <option value="paypal">PayPal</option>
                                <option value="cash">Paiement à la livraison</option>
                            </select>
                        </div>
                        
                        <div id="card-payment-section">
                            <div class="form-group">
                                <label for="card">Numéro de carte</label>
                                <input type="text" id="card" name="card" placeholder="1234 5678 9012 3456">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry">Date d'expiration</label>
                                    <input type="text" id="expiry" name="expiry" placeholder="MM/AA">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="cart.php" class="cancel-btn">Retour au panier</a>
                            <button type="submit" class="submit-btn">Payer maintenant</button>
                        </div>
                    </form>
                </div>
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
    
    // Fonction pour charger les produits du panier
    function loadCartItems() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const checkoutProducts = document.getElementById('checkout-products');
        const cartCount = document.querySelector('.cart-count');
        
        // Mettre à jour le compteur de produits dans le panier
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;
        
        // Vider le conteneur de produits
        checkoutProducts.innerHTML = '';
        
        // Si le panier est vide, rediriger vers la page du panier
        if (cart.length === 0) {
            window.location.href = 'cart.php';
            return;
        }
        
        // Ajouter chaque produit à la liste
        cart.forEach(item => {
            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            
            productItem.innerHTML = `
                <div class="product-image">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="product-details">
                    <h3>${item.name}</h3>
                    <p class="product-price">${item.price} DH</p>
                    <div class="product-quantity">
                        <span>Quantité: ${item.quantity}</span>
                    </div>
                </div>
            `;
            
            checkoutProducts.appendChild(productItem);
        });
        
        // Mettre à jour les totaux
        updateTotals();
    }
    
    // Fonction pour mettre à jour les totaux
    function updateTotals() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = subtotal; // Pas de frais de livraison pour l'instant
        
        document.getElementById('checkout-subtotal').textContent = `${subtotal.toFixed(2)} DH`;
        document.getElementById('checkout-total').textContent = `${total.toFixed(2)} DH`;
    }
    
    // Fonction pour gérer l'affichage des sections de paiement
    function handlePaymentMethodChange() {
        const paymentMethod = document.getElementById('payment-method').value;
        const cardSection = document.getElementById('card-payment-section');
        const cardInputs = cardSection.querySelectorAll('input');
        
        if (paymentMethod === 'card') {
            cardSection.style.display = 'block';
            cardInputs.forEach(input => {
                input.setAttribute('required', '');
            });
        } else {
            cardSection.style.display = 'none';
            cardInputs.forEach(input => {
                input.removeAttribute('required');
            });
        }
    }
    
    // Événement de changement de méthode de paiement
    document.getElementById('payment-method').addEventListener('change', handlePaymentMethodChange);
    
    // Événement de soumission du formulaire
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const paymentMethod = document.getElementById('payment-method').value;
        let message = '';
        
        switch (paymentMethod) {
            case 'card':
                message = 'Votre paiement par carte bancaire a été traité avec succès.';
                break;
            case 'paypal':
                message = 'Votre paiement via PayPal a été traité avec succès.';
                break;
            case 'cash':
                message = 'Votre commande a été enregistrée. Vous paierez à la livraison.';
                break;
            default:
                alert('Veuillez sélectionner une méthode de paiement.');
                return;
        }
        
        // Récupérer les données du panier
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (cart.length === 0) {
            alert('Votre panier est vide.');
            return;
        }
        
        // Ajouter l'état de chargement au bouton
        const submitButton = this.querySelector('.submit-btn');
        submitButton.classList.add('loading');
        submitButton.innerHTML = 'Traitement en cours...';
        submitButton.disabled = true;
        
        // Envoyer les données au serveur
        fetch('process_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart: cart })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Vider le panier après paiement réussi
                localStorage.removeItem('cart');
                
                // Afficher un message de succès
                alert(message + ' Merci pour votre achat!');
                
                // Rediriger vers la page d'accueil
                window.location.href = 'index.php';
            } else {
                // Afficher un message d'erreur
                alert('Une erreur est survenue lors du traitement de votre commande : ' + data.message);
                
                // Rétablir le bouton
                submitButton.classList.remove('loading');
                submitButton.innerHTML = 'Payer maintenant';
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la communication avec le serveur.');
            
            // Rétablir le bouton
            submitButton.classList.remove('loading');
            submitButton.innerHTML = 'Payer maintenant';
            submitButton.disabled = false;
        });
    });
    
    // Initialiser la page
    document.addEventListener('DOMContentLoaded', function() {
        loadCartItems();
        handlePaymentMethodChange();
    });
    </script>
</body>
</html> 