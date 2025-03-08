<?php
require_once 'config/database.php';
require_once 'models/Client.php';

$database = new Database();
$db = $database->getConnection();

$client = new Client($db);
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set client values
    $client->nom = $_POST['nom'] ?? '';
    $client->prenom = $_POST['prenom'] ?? '';
    $client->email = $_POST['email'] ?? '';
    $client->password = $_POST['password'] ?? '';
    $client->teleohon = $_POST['telephone'] ?? '';
    $client->adress = $_POST['adresse'] ?? '';

    // Validate inputs
    if (empty($client->nom) || empty($client->prenom) || empty($client->email) || 
        empty($client->password) || empty($client->teleohon) || empty($client->adress)) {
        $error = "Tous les champs sont obligatoires";
    } elseif (!filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide";
    } else {
        // Try to create the client
        if ($client->create()) {
            $message = "Compte créé avec succès!";
            header("Location: login.php");
            exit();
        } else {
            $error = "Une erreur s'est produite lors de la création du compte";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h2 class="form-title">Créer un compte</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($message): ?>
                <div class="success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $_POST['nom'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $_POST['prenom'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo $_POST['telephone'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="<?php echo $_POST['adresse'] ?? ''; ?>" required>
                </div>

                <button type="submit" class="btn-register">S'inscrire</button>
            </form>
        </div>

        <div class="store-preview">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8" alt="Fashion 1" class="preview-image">
            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050" alt="Fashion 2" class="preview-image">
            <img src="https://images.unsplash.com/photo-1462392246754-28dfa2df8e6b" alt="Fashion 3" class="preview-image">
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04" alt="Fashion 4" class="preview-image">
        </div>
    </div>

    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const telephone = document.getElementById('telephone').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères');
            }
            
            if (!/^\d+$/.test(telephone)) {
                e.preventDefault();
                alert('Le numéro de téléphone doit contenir uniquement des chiffres');
            }
        });
    </script>
</body>
</html> 