<?php 
session_start();
// Connexions aux bases de données
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', '');
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bddA->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!isset($_SESSION['admin'])){ // On vérifie que l'utilisateur connecté est bien administrateur sinon on le rediriges vers connexion
    header("Location:../Utilisateur/Connexion.php");
}


if(isset($_GET['id']) AND !empty($_GET['id'])){// On vérifie que l'id existe et est non vide
    // On récupère le restaurant grâce à son id
    $reqRestau=$bddA->prepare('SELECT * FROM restaurant WHERE id = ?');
    $reqRestau->execute(array($_GET['id']));
    $restau=$reqRestau->fetch();

    // On récupère tous les avis sur ce restaurant
    $reqAvis=$bddA->prepare('SELECT * FROM avis_restaurant WHERE nom_restaurant = ?');
    $reqAvis-> execute(array($restau['nom']));
    
}else{
    echo "L'identifiant n'as pas été trouvé.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Page du restaurant</title>
</head>
<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        <!-- Affichage des informations du restaurant -->
        <div class="titre">
            <h1>Restaurant <?php echo $restau['nom']; ?></h1>
        </div>
        <div class="List">
            <div class="card" id="restau">
                <div class="left">
                    <h1><?php echo $restau['nom']; ?></h1>
                    <h4><?php echo $restau['cuisine']; ?></h4>
                    <h4><?php echo $restau['lieu']; ?></h4>
                    <h4><?php echo $restau['adresse']; ?></h4>
                    <h4>Numéro de téléphone : <?php echo $restau['tel']; ?></h4>
                    <h4>Prix moyen du repas : <?php echo $restau['prix']; ?></h4>
                </div>
                <div class="right">
                    <img src="../Images/<?php echo $restau['id']; ?>.jpg" alt="Image du restaurant">
                </div>
            </div>
        </div>

        <!-- Affichage des avis du restaurant -->
        <div class="titre">
            <h2>Avis sur le restaurant</h2>
        </div>
        <div class="Liste">
            <?php while($avis=$reqAvis->fetch()){ ?>
                <div class="card">
                    <h1><a href="../Administration/AvisUser.php?id=<?php echo $avis['id']; ?>"><?php echo $avis['nom_client']; ?></a></h1>
                    <h4>Avis : </h4>
                    <p><?php echo $avis['avis']; ?></p>
                    <form action="SupprAvis.php" method="get"><!-- Lien pour supprimer l'avis de la base de donnée -->
                        <button id="ban" type="submit" name="id" value=<?php echo $avis['id']; ?>>Supprimer l'avis</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>