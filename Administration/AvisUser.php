<?php 
session_start();
// Connexions aux bases de données
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', '');
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
if(!isset($_SESSION['admin'])){ // On vérifie que l'utilisateur connecté est bien administrateur sinon on le rediriges vers connexion
    header("Location:../Utilisateur/Connexion.php");
}

if(isset($_GET['id']) AND !empty($_GET['id'])){ // On vérifie que l'id existe et non vide
    // On récupère l'utilisateur grâce à son id
    $reqUser= $bdd->prepare('SELECT * FROM users WHERE id = ?'); 
    $reqUser-> execute(array($_GET['id']));
    $user=$reqUser->fetch();

    // On récupère tous ses avis grâce à son pseudo
    $reqAvis=$bddA->prepare('SELECT * FROM avis_restaurant WHERE nom_client = ?');
    $reqAvis-> execute(array($user['pseudo']));
    $avis = $reqAvis->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Profil de l'utilisateur</title>
</head>
<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>

    <section class="main">
        <div class="titre">
            <h1>Profil de l'utilisateur</h1>
        </div>

        <!-- Affichage des informations de l'utilisateur -->
        <div class="Liste">
            <div class="card" id="info">
                <h2><?php echo $user['pseudo']; ?></h2>
                <p><?php echo $user['email']; ?></p>
            </div>            
        </div>

        <!-- Affichage des avis postés par l'utilisateur -->
        <div class="titre">
            <h2>Avis de l'utilisateur</h2>
        </div>
        <div class="Liste">
            <?php foreach ($avis as $InfosAvis) { ?>
                <div class="card">
                    <h1><?php echo $InfosAvis['nom_restaurant']; ?></h1>
                    <h4>Utilisateur : </h4><h4><?php echo $InfosAvis['nom_client']; ?></h4>
                    <h4>Avis : </h4>
                    <p><?php echo $InfosAvis['avis']; ?></p>
                    <form id="bot" action="SupprAvis.php" method="get"><!-- Lien pour supprimer l'avis de la base de donnée -->
                        <button id="ban" type="submit" name="id" value=<?php echo $InfosAvis['id']; ?>>Supprimer l'avis</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>