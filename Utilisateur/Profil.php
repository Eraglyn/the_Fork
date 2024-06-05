<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', ''); // Connexion à la base de donnée des membres
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');// Connexion à la base de donnée des restaurants et avis
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bddA->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!isset($_SESSION['id'])){ // Vérifie si l'utilisateur est connecté, si non le redirige vers la page de connexion
    header('Location:Connexion.php');
}

// Partie informations du profil
$reqUser= $bdd->prepare('SELECT * FROM users WHERE id = ?'); // Prépare la connexion avec la requête SQL
$reqUser-> execute(array($_SESSION['id'])); // Execute la requête avec la variable id de l'utilisateur
$user=$reqUser->fetch(); // Récupère les données de l'utilisateur sous la forme d'une liste array
$pseudo = $user['pseudo']; // Sauvegarde le pseudo dans une variable pour en faciliter l'accès

if(isset($_POST['Modifier'])){ // Vérifie que le formulaire de modification d'informations a été utilisé

    if(isset($_POST['pseudo']) AND !empty($_POST['pseudo']) AND $_POST['pseudo']!=$user['pseudo']){// Vérifie que le champs pseudo est définit, remplit et différent du pseudo d'origine

        $newpseudo = htmlspecialchars($_POST['pseudo']); // Sécurise le pseudo dans une nouvelle variable pour éviter les caractères html
        $updatePseudo = $bdd->prepare('UPDATE users SET pseudo = ? WHERE id = ?'); //Prépare la requête pour mettre à jour le pseudo
        $updatePseudo->execute(array($newpseudo, $_SESSION['id'])); // Execute la requête avec le nouveau pseudo pour l'utilisateur avec l'id correspondant
        $_SESSION['pseudo']=$newpseudo; // Met à jour le pseudo dans la session
        header("Location:Profil.php"); // Redirige vers le profil de l'utilisateur

    }else{ //  Met dans la variable erreur si le pseudo n'a pas pu être changé
        $donneeInc="Le changement de pseudo n'as pas pu s'opérer."; 
    }

    if(isset($_POST['email']) AND !empty($_POST['email']) AND $_POST['email']!=$user['email']){ // Même vérification avec l'email qu'avec le pseudo

        $newemail = htmlspecialchars($_POST['email']); // Sécurisation de l'email
        $updateemail = $bdd->prepare('UPDATE users SET email = ? WHERE id = ?'); // Prépare la requête pour mettre à jour l'email
        $updateemail->execute(array($newemail, $_SESSION['id'])); // Execution avec le nouvel email et l'id de l'utilisateur
        $_SESSION['email']=$newemail; // Met à jour l'email dans la session
        header("Location:Profil.php"); // Redirige vers le profil de l'utilisateur

    }else{ //  Met dans la variable erreur si l'email n'a pas pu être changé
        $donneeInc="Le changement d'adresse email n'as pas pu s'opérer.";
    }
    
    

}
// Partie mot de passe
if(isset($_POST['ModifierMDP'])){ // Vérifie que le formulaire de modification de mot de passe a été utilisé

    if(isset($_POST['mdp']) AND !empty($_POST['mdp']) AND $_POST['mdp']!=$_POST['mdp2'] AND isset($_POST['mdp2']) AND !empty($_POST['mdp2']) AND isset($_POST['mdp3']) AND !empty($_POST['mdp3'])){ 
    // Vérifie que les trois champs sont remplis et définit. Vérifie que l'ancien mot de passe et le nouveau ne sont pas les mêmes
        // Chriffrement en sha1 des mots de passe
        $mdp=sha1($_POST['mdp']); 
        $mdp2=sha1($_POST['mdp2']);
        $mdp3=sha1($_POST['mdp3']);

        if($mdp2==$mdp3){ // Vérifie que le nouveau et la confirmation du mot de passe coincident

            if($user['mdp']==$mdp){ // Vérifie que l'ancien mot de passe correspond à celui de la base de donnée

                $updateUser = $bdd->prepare('UPDATE users SET mdp = ? WHERE id = ?'); // Prépare la requête pour mettre à jour le mot de passe
                $updateUser->execute(array($mdp2, $_SESSION['id'])); // Execution avec le nouveau mot de passe et l'id de l'utilisateur
                $_SESSION['mdp'] = $mdp2; // Met à jour le mot de passe dans la session

            }else{ // Met dans la variable erreur si l'ancien mot de passe ne correspond pas avec celui de la base de donnée
                $mdpInc="L'ancien mot de passe ne correspond pas !";
            }

        }else{ // Met dans la variable erreur si les mots de passes ne sont pas les mêmes
            $mdpInc="Les mots de passes ne sont pas les mêmes !";
        }

    }
    
}

// Partie avis

$reqAvis=$bddA->prepare('SELECT * FROM avis_restaurant WHERE nom_client = ?'); // Prépare la requête pour récupérer les avis de l'utilisateur
$reqAvis->execute(array($pseudo));
$listeavis = $reqAvis->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Mon profil</title>
</head>
<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">

        <!-- Partie Informations -->
        <div class="profil">
            <h1>Votre profil</h1>
        </div>
        
        <div class="Liste">
            <div class="card" id="cardForm">
                 <!-- Affichage des informations de l'utilisateur -->
                    <h1>Bonjour <?php echo $user['pseudo']; ?></h1>
                    <h2>Vos informations</h2>

                    <form action="" method="post"> <!-- Formulaire pour modifier les informations de l'utilisateur -->
                    
                        <label for="pseudo">Pseudo</label>
                        <input type="text" name="pseudo" autocomplete="off" value=<?php echo $user['pseudo']; ?> >
                        <label for="email">Adresse email</label>
                        <input type="email" name="email" autocomplete="off" value=<?php echo $user['email']; ?>>
                        <?php
                            if(isset($donneeInc)){ // Affiche l'erreur si elle a été définit plus haut
                                echo $donneeInc;
                            }
                        ?>                        
                        <button type="submit" name="Modifier" value="Mettre à jour">Mettre à jour</button>
                        
                        
                    </form>

                    <form action="" method="post"><!-- Formulaire pour modifier le mot de passe de l'utilisateur -->
                        
                        <label for="mdp">Ancien mot de passe</label>
                        <input type="password" name="mdp" autocomplete="off" placeholder="Votre ancien mot de passe">
                        <label for="mdp">Nouveau mot de passe</label>
                        <input type="password" name="mdp2" autocomplete="off" placeholder="Votre nouveau mot de passe">
                        <label for="mdp">Confirmation du mot de passe</label>
                        <input type="password" name="mdp3" autocomplete="off" placeholder="Confirmation du mot de passe">
                        <?php
                            if(isset($mdpInc)){ // Affiche l'erreur si elle a été définit plus haut
                                echo $mdpInc;
                            }
                        ?>
                        
                        <button type="submit" name="ModifierMDP" value="Mettre à jour">Mettre à jour</button>
                        
                    </form>
                </div>

            </div>
        </div>

        <!-- Partie Avis -->
        <div class="avis">

            <div class="profil">
                <h1>Vos avis</h1>
            </div>

            <?php foreach ($listeavis as $InfosAvis) { ?>
                <div class="card">
                    <div class="container">
                        <h1><?php echo "{$InfosAvis['nom_restaurant']}"; ?></h1><br>
                        <h4>Utilisateur : <br><br> <?php echo "{$InfosAvis['nom_client']}"; ?></h4><br>
                        <h4>Avis : <br><br> <?php echo "{$InfosAvis['avis']}"; ?></h4><br>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>