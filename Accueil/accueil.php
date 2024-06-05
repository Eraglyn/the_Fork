<?php
session_start();
// Connexion à la base de donnée restaurant
$bdd = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$RecupererRestaurant = 'SELECT * FROM restaurant';
// envoi de la requête et récupération du résultat
$ListeRestaurant = $bdd->query($RecupererRestaurant)->fetchAll(PDO::FETCH_ASSOC);

// Fonction recherche
$allrest = $bdd->query('SELECT * FROM restaurant ORDER BY id DESC');
if (isset($_GET['s']) AND !empty($_GET['s'])) {
    $recherche = htmlspecialchars($_GET['s']);
    $allrest = $bdd->query('SELECT * FROM restaurant WHERE nom OR cuisine OR lieu LIKE "%' . $recherche . '%" ORDER BY id DESC'); // concaténation %%
}
?>

<!DOCTYPE html>
<html>
<html lang="fr">
<head>
    <title>Accueil</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        
        <div class="banner">
            <img src="https://www.ileauxcanards.nc/wp-content/uploads/2019/08/banniere-restaurant.jpg">
        </div>
        
        <header>
            <form id="barre"method="GET">
                <input type="text" name="s" placeholder="Nom du restaurant" autocomplete="off">
                <button id="recherche" type="submit" name="valider" value="Rechercher">Rechercher</button>
            </form>
        </header>
        <div class="titre">
            <h1>Restaurants</h1>
        </div>

        <div class="affichage"> 
            <?php
            if ($allrest->rowCount() > 0) {
                while ($nom = $allrest->fetch()) {

                    $image=intval($nom['id']); // La photo des établissements dans la base de donnée d'origine sont récupérable par leur id respectif
                    if(intval($nom['id'])>6){ // Si l'établissement a été rajouté par l'administrateur, sa photo est celle par défault
                        $image="default";
                    }
            ?>
                <a href="../Accueil/Restaurant.php?nom=<?php echo $nom['nom']; ?>">
                    <div class="card">
                        <img src="../Images/<?php echo $image; ?>.jpg" alt="Image du restaurant">
                        <div class="container">
                            <h1><b><?= $nom['nom']; ?></b></h1>
                            <h4><?php echo $nom['cuisine']; ?></h4>
                            <h4><?php echo $nom['lieu']; ?></h4>
                            <h4><?php echo $nom['adresse']; ?></h4>
                            <h4><?php echo $nom['tel']; ?></h4>
                            <h4>Prix moyen : <?php echo $nom['prix']; ?></h4>
                        </div>
                    </div>
                </a>
                <?php
                }
            } else {
                ?>
                <p>Aucun restaurants trouvé</p>
            <?php
            }
            ?>
        </div>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>