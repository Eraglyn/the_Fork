<?php
session_start();
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', ''); // Connexion à la base de donnée des restaurants
if(!isset($_SESSION['admin'])){ // On vérifie que l'utilisateur connecté est bien administrateur sinon on le rediriges vers connexion
    header("Location:../Utilisateur/Connexion.php");
}
if(isset($_GET['id']) AND !empty($_GET['id'])){ // On vérifie que l'id du restaurant est dans l'url
    $id=$_GET['id'];
    $recupUser= $bddA->prepare('SELECT * FROM restaurant WHERE id = ?'); // On vérifie qu'il correspond à un restaurant dans la base de donnée
    $recupUser->execute(array($id));
    if($recupUser->rowCount()>0){ // S'il il correspond, on le supprime de la base de donnée
        $bannirUser=$bddA->prepare('DELETE FROM restaurant WHERE id=?');
        $bannirUser->execute(array($id));
        header("Location:Index.php"); // On redirige vers la liste des restaurants et membres

    }else{ // Affiche l'erreur s'il y en a une
        echo "Cet identifiant n'appartiens à aucun restaurant.";
    }
}else{
    echo "L'identifiant n'as pas été trouvé.";
}
?>