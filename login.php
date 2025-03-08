<?php
require_once 'config/database.php';
require_once 'models/Client.php';

session_start();

$database = new Database();
$db = $database->getConnection();

$client = new Client($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires";
    } else {
        $user_id = $client->login($email, $password);
        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Fashion Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h2 class="form-title">Connexion</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-register">Se connecter</button>
                
                <p style="text-align: center; margin-top: 20px;">
                    Pas encore de compte ? 
                    <a href="register.php" style="color: #4CAF50; text-decoration: none;">S'inscrire</a>
                </p>
            </form>
        </div>

        <div class="store-preview">
            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050" alt="Fashion 1" class="preview-image">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8" alt="Fashion 2" class="preview-image">
        </div>
    </div>
</body>
</html> 