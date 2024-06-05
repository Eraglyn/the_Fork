<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', '');// Connexion à la base de donnée des membres
if(!isset($_SESSION['admin'])){ // On vérifie que l'utilisateur connecté est bien administrateur sinon on le rediriges vers connexion
    header("Location:../Utilisateur/Connexion.php");
}
if(isset($_GET['id']) AND !empty($_GET['id'])){ // On vérifie que l'id de l'utilisateur est dans l'url
    $id=$_GET['id'];
    $recupUser= $bdd->prepare('SELECT * FROM users WHERE id = ?'); // On vérifie qu'il correspond à un utilisateur dans la base de donnée
    $recupUser->execute(array($id));
    if($recupUser->rowCount()>0){ // S'il il correspond, on le passe à l'état d'administrateur de la base de donnée
        $updateUser = $bdd->prepare('UPDATE users SET admin=1 WHERE id = ?');
        $updateUser->execute(array($id));
        header("Location:Index.php"); // On redirige vers la liste des restaurants et membres

    }else{// Affiche l'erreur s'il y en a une
        echo "Cet identifiant n'appartiens à aucun membre.";
    }
}else{
    echo "L'identifiant n'as pas été trouvé.";
}
?>