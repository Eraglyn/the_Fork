<?php
session_start();
// Connexion aux bases de données membre et restaurant
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', '');
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bddA->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$nom=htmlspecialchars($_GET['nom']);// On sécurise la variable

if(isset($nom) AND !empty($nom)){// On vérifie que le nom du restaurant existe et est non vide
    
    
    // On récupère le restaurant grâce à son nom
    $reqRestau=$bddA->prepare('SELECT * FROM restaurant WHERE nom = ?');
    $reqRestau->execute(array($nom));
    $restau=$reqRestau->fetch();
    if($reqRestau->rowCount()>0){ // Si un restaurant correspond dans la base de donnée
        // On récupère tous les avis sur ce restaurant
        $reqAvis=$bddA->prepare('SELECT * FROM avis_restaurant WHERE nom_restaurant = ?');
        $reqAvis-> execute(array($nom));
    }else{
        echo "Le restaurant n'est pas dans la base de donnée.";
    }
    
    
}else{
    echo "L'identifiant n'as pas été trouvé.";
}

// On récupère les avis du restaurants
$RecupererAvis=$bddA->prepare('SELECT * FROM avis_restaurant WHERE nom_restaurant = ?');
$RecupererAvis->execute(array($nom));
$ListeAvis = $RecupererAvis->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['AvisRecu'])) { // On vérifie si le formulaire pour ajouter un avis a été utiliser
    if(!empty($_POST['avis'])) {
        $avis = htmlspecialchars($_POST['avis']);

        // On rentre l'avis dans la base de donnée
        $insertAvis = $bddA->prepare('INSERT INTO avis_restaurant(nom_restaurant, nom_client, avis)VALUES(?, ?, ?)');
        $insertAvis->execute(array($nom, $_SESSION['pseudo'], $avis));
        header("Location:../Accueil/Restaurant.php?nom=$nom"); // On réactualise la page pour afficher le nouvel avis
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <section class="main">
        <!-- Barre de navigation du fichier Nav.php -->
        <?php require_once("../Nav.php"); ?>


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
                <?php 
                    $image=intval($restau['id']); // La photo des établissements dans la base de donnée d'origine sont récupérable par leur id respectif
                    if(intval($restau['id'])>6){ // Si l'établissement a été rajouté par l'administrateur, sa photo est celle par défault
                        $image="default";
                    } 
                ?>
                <div class="right">
                    <img src="../Images/<?php echo $image; ?>.jpg" alt="Image du restaurant">
                </div>
            </div>
        </div>

        <!-- Affichage des avis du restaurant -->
        <div class="titre">
            <h2>Avis sur le restaurant</h2>
        </div>
        <div class="Liste"> 
            <?php if($reqAvis->rowCount()==0){
                echo "<h4>Il semblerait qu'il n'y ait pas encore d'avis sur ce restaurant...</h4>";
            }
                while($avis=$reqAvis->fetch()){ 
                ?>
                <div class="card">
                    <h1><?php echo $avis['nom_client']; ?></h1>
                    <h4>Avis : </h4>
                    <p><?php echo $avis['avis']; ?></p>
                </div>
            <?php if(!isset($_SESSION['id'])){ // N'affiche qu'un seul commentaire si l'utilisateur n'est pas connecté
                break;
            }
            } ?>
        </div>
        <?php if(!isset($_SESSION['id'])){ // Demande à l'utilisateur de se connecter pour voir plus d'avis (Après la div pour des soucis de mise en page)
                echo "<h2>Pour voir plus d'avis ou donner votre propre avis, veuillez vous <a href='../Utilisateur/Connexion.php'>connecter.</a></h2>";
            }
            
            if(isset($_SESSION['id'])){ // Seulement si l'utilisateur est connecté ?>

        <!-- Formulaire pour soumettre un avis -->
        <div class="titre">
            <h2>Donner votre avis :</h2>
        </div>
        <div class="Liste">
            <div class="card" id="cardForm">
                <form method="POST" action="" align="center">

                    <label for="nom">Votre pseudo : <?php echo $_SESSION['pseudo']; ?> </label>

                    <label for="nom">Votre avis sur votre repas :</label>
                    <textarea name="avis" cols="60" rows="2" placeholder="Votre avis..."></textarea>

                    <button type="submit" name="AvisRecu" value="Envoyer">Envoyer l'avis</button>
                </form>
            </div>
        </div>
        <?php } ?>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>