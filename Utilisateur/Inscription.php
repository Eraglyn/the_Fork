<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', ''); // Connexion à la base de donnée des membres
if(isset($_POST['Inscription'])){ // Vérifie que le formulaire d'inscription a été rempli

    if(!empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['email'])){ // Vérifie que tous les champs ont été remplis

        $pseudo = htmlspecialchars($_POST['pseudo']); // Sécurise le pseudo
        $mdp = sha1($_POST['mdp']); // Chiffrement du mot de passe
        $mdp2 = sha1($_POST['mdp2']);// Chiffrement de la confirmation du mot de passe
        $email = htmlspecialchars($_POST['email']); // Sécurise l'email

        if($mdp==$mdp2){ // Vérifie que le mot de passe et sa confirmation coincident

            //Vérification que l'email soit libre
            $recupUser = $bdd->prepare('SELECT * FROM users WHERE email = ?');  // Prépare la requête de recherche d'utilisateur en fonction de l'email
            $recupUser->execute(array($email)); // Execute la requête avec l'email renseigné par l'utilisateur

            if(!($recupUser->rowCount() > 0)){ // Si aucun utilisateur n'a cette email

                //Vérification que le pseudo soit libre
                $recupUser = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');// Prépare la requête de recherche d'utilisateur en fonction du pseudo
                $recupUser->execute(array($pseudo));// Execute la requête avec le pseudo renseigné par l'utilisateur

                if(!($recupUser->rowCount() > 0)){ // Si aucun utilisateur n'a ce pseudo

                    $insertUser = $bdd->prepare('INSERT INTO users(email, pseudo, mdp)VALUES(?, ?, ?)'); // Prépare la requête pour insérer un nouvel utilisateur
                    $insertUser->execute(array($email, $pseudo, $mdp)); // Execute la requête avec les données sécurisées remplis dans le formulaires

                    // Récupère l'ID de l'utilisateur créé dans la base de donnée
                    $recupID = $bdd->prepare('SELECT id FROM users WHERE email = ? AND pseudo = ? AND mdp = ?'); 
                    $recupID->execute(array($email,$pseudo,$mdp));

                    if($recupID->rowCount()>0){ // Test si un utilisateur correspond aux informations rentrés et enregistre les données dans la session

                        $_SESSION['pseudo'] = $pseudo;
                        $_SESSION['mdp'] = $mdp;
                        $_SESSION['email'] = $email;
                        $_SESSION['id']=$recupID->fetch()['id'];
                        header("Location:../Accueil/accueil.php"); // Redirige sur la page d'accueil
                    }

                }else{ // Met dans la variable erreur si le pseudo n'est pas disponible
                    $donneeInc=" Ce pseudo n'est pas disponible !";
                }

            }else{ // Met dans la variable erreur si l'email n'est pas disponible
                $donneeInc="Cette adresse mail n'est pas disponible !";
            }

        }else{ // Met dans la variable erreur si la confirmation ne correspond pas
            $donneeInc="Les mots de passes ne sont pas les mêmes !";
        }

    }else{ // Met dans la variable erreur si les champs ne sont pas tous complétés
        $donneeInc="Tous les champs doivent être complétés !";
    }

}
?>
<!DOCTYPE html>
<html lang="fr">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Inscription</title>
</head>
<body background="../Images/Inscription.png">
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        <div class="Liste">
            <div class="card" id="cardForm">
                <form method="POST" action="" align="center"> <!-- Formulaire pour inscrire l'utilisateur -->
                    <h1>Inscription</h1>
                    <input type="email" name="email" autocomplete="off" placeholder="Adresse email">
                    <input type="text" name="pseudo" autocomplete="off" placeholder="Pseudo">
                    <input type="password" name="mdp" autocomplete="off" placeholder="Mot de passe">
                    <input type="password" name="mdp2" autocomplete="off" placeholder="Confirmation du mot de passe">
                    <?php
                        if(isset($donneeInc)){ // Affiche l'erreur si elle a été définit plus haut
                            echo $donneeInc;
                        }
                    ?>
                    
                    <p class="inscription">Vous avez un <span >compte</span>? <a href="Connexion.php">Connectez-vous</a> !</p>
                    <button type="submit" name="Inscription">S'inscrire</button>
                </form>
            </div>
        </div>
        <br><br><br><br><br><br><br><br>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>

            