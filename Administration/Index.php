<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', ''); // Connexion à la base de donnée des membres
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', ''); // Connexion à la base de donnée des restaurants
if(!isset($_SESSION['admin'])){ // On vérifie que l'utilisateur connecté est bien administrateur sinon on le rediriges vers connexion
    header("Location:../Utilisateur/Connexion.php");
}
$recupUsers = $bdd->query('SELECT * FROM users'); // On récupère la liste de tous les utilisateurs dans la base de données
$recupRestau = $bddA->query('SELECT * FROM restaurant'); // On récupère la liste de tous les restaurants dans la base de données
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Administration</title>
</head>
<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        <div class="titre">
            <h1>Liste des utilisateurs</h1>
        </div>
    
        <!-- Partie utilisateurs -->
        <section class="Liste">
            <?php while($user=$recupUsers->fetch()){ ?>
                <a href="../Administration/AvisUser.php?id=<?php echo $user['id']; ?>"><!-- Lien vers la page du profil et les avis de l'utilisateur -->
                    <div class="card">
                        <h1><?php echo $user['pseudo']; ?></h1>
                        <h4> Adresse email : <?php echo $user['email']; ?></h4>
                        <?php if($user['admin']==1){ ?> <!-- Si l'utilisateur est un administrateur, on affiche le bouton pour le rétrograder en membre -->
                                <form action="Retrograder.php?id=" method="get">
                                    <h4>Statut : Administrateur</h4>
                                    <button type="submit" name="id" value=<?php echo $user['id']; ?>>Rétrograder en utilisateur</button>
                                </form>
                        <?php }else{ ?>  <!-- Si l'utilisateur est un membre, on affiche le bouton pour le promouvoir en administrateur -->
                                <form action="Promouvoir.php?id=" method="get">
                                    <h4>Statut : Utilisateur</h4>
                                    <button type="submit" name="id" value=<?php echo $user['id']; ?>>Promouvoir en administrateur</button>
                                </form>
                        <?php } ?>
                        <form action="Bannir.php" method="get"><!-- Lien pour supprimer l'utilisateur de la base de donnée -->
                            <button id="ban" type="submit" name="id" value=<?php echo $user['id']; ?>>Bannir</button>
                        </form>
                        
                    </div>
                </a>
            <?php } ?>
        </section>

        <!-- Partie restaurants -->
        <div class="titre">
            <h1>Liste des restaurants</h1>
        </div>
        <section class="Liste">
            <a href="../Administration/AjoutRestau.php">
                <div class="card" id="ajoutRestau">
                    <h1>Ajouter un restaurant</h1>
                </div>
            </a>
            
            <?php while($restau=$recupRestau->fetch()){ //Affiche chaque restaurant dans une card ?>
                <a href="../Administration/AvisRestau.php?id=<?php echo $restau['id']; ?>">
                    <div class="card">
                        <h1><?php echo $restau['nom']; ?></h1>
                        <h4>Spécialité : <?php echo $restau['cuisine']; ?></h4>
                        <h4>Ville : <?php echo $restau['lieu']; ?></h4>
                        <h4>Adresse : <?php echo $restau['adresse']; ?></h4>
                        <h4>Numéro de téléphone : <?php echo $restau['tel']; ?></h4>
                        <h4>Prix moyen d'un repas : <?php echo $restau['prix']; ?></h4>
                        <form action="SupprRestau.php" method="get">
                            <button id="ban" type="submit" name="id" value=<?php echo $restau['id']; ?>>Supprimer le restaurant</button>
                        </form>
                    </div>
                </a>
            <?php } ?>
        </section>
        
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>