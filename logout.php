<?php
session_start();
// Détruire toutes les variables de session
$_SESSION = array();
// Détruire la session
session_destroy();
header("Location: login.php");
exit();
?> 