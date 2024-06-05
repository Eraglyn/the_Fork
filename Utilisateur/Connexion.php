<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', ''); // Connexion à la base de donnée des membres

if(isset($_POST['Connexion'])){ // Vérifie que le formulaire de connexion a été rempli

    if(!empty($_POST['pseudo']) AND !empty($_POST['mdp'])){ // Vérifie que tous les champs ont été remplis

        $pseudo = htmlspecialchars($_POST['pseudo']); // Sécurise le pseudo
        $mdp = sha1($_POST['mdp']); // Chiffrement du mot de passe

        $recupUser = $bdd->prepare('SELECT * FROM users WHERE pseudo = ? AND mdp = ?'); // Prépare la requête de recherche d'utilisateur en fonction de son pseudo et du mot de passe
        $recupUser->execute(array($pseudo, $mdp)); // Execute la requête avec les informations renseignées
        $user=$recupUser->fetch(); // Récupère les données de l'utilisateur sous la forme d'une liste array

        // Récupère l'ID de l'utilisateur et son statut d'administrateur ou non
        $recupID = $bdd->prepare('SELECT id,admin FROM users WHERE pseudo = ? AND mdp = ?');
        $recupID->execute(array($pseudo,$mdp));
        $id=$recupID->fetch(); // Recupère l'id et le boolean du statut sous la forme d'une liste array

        if($recupID->rowCount()>0){ // Si un utilisateur correspond dans la base de donnée enregistre son id dans la session
            $_SESSION['id']=$id['id'];
        }

        if($recupUser->rowCount() > 0){ // Si un utilisateur correspond dans la base de donnée enregistre ses données dans la session
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['mdp'] = $mdp;
            $_SESSION['email'] = $user['email']; // Récupère l'email depuis la base de donnée

            if($id['admin']==1){ // Test si l'utilisateur a le statut d'administrateur
                $_SESSION['admin']=1; // Enregistre son statut dans la session
                header("Location:../Administration/Index.php"); // Le redirige vers la partie administration
    
            }else{ // Sinon, le redirige vers l'accueil
                header("Location:../Accueil/accueil.php");
                
            }
            
        }else{ // Met dans la variable erreur si aucun utilisateur ne correspond
            $donneeInc="Les informations rentrées sont incorrects.";
        }

        

    }else{ // Met dans la variable erreur si les champs ne sont pas tous complets
        $donneeInc="Tous les champs doivent être complétés !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style backgrou></style>
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <title>Connexion</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body background="../Images/Inscription.png">
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        <div class="Liste">
            <div class="card" id="cardForm">
                <form action="" method="post" align="center"><!-- Formulaire de connexion -->

                    <h1>Connexion</h1>
                    
                    <input type="text" name="pseudo" autocomplete="off" placeholder="Pseudo">
                    <input type="password" name="mdp" autocomplete="off" placeholder="Mot de passe">
                    
                    <?php if(isset($donneeInc)){ // Affiche l'erreur si elle a été définit plus haut
                            echo $donneeInc;
                        } ?>
                    <p class="inscription">Vous n'avez pas de <span>compte</span> ? <a href='Inscription.php'>Inscrivez-vous</a>!</p>
                    <button type="submit" name="Connexion" value="Envoyer">Connexion</button>
                </form>  
            </div>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>