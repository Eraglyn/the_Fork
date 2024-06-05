<?php
session_start();
$_SESSION=array(); // Vide la session
session_destroy(); // Detruit la session
header('Location:Connexion.php') // Redirige sur la page de connexion
?>