<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_membres;charset=utf8;', 'root', ''); // Connexion à la base de donnée des membres
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', ''); // Connexion à la base de donnée des restaurants et avis

if(!isset($_SESSION['id'])){ // Vérifie si l'utilisateur est connecté
    $connect=false;
}
if(isset($_GET['id']) AND !empty($_GET['id'])){
    $reqRestau=$bddA->prepare('SELECT * FROM restaurant WHERE id = ?');
    $reqRestau->execute(array($_GET['id']));
    $restau=$reqRestau->fetch();
    $reqAvis=$bddA->prepare('SELECT * FROM avis WHERE restaurant = ?');
    $reqAvis-> execute(array($_GET['id']));
    $reqUser=$bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
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
    <link rel="stylesheet" href="styleP.css">
    <title>Page du restaurant</title>
</head>
<body>
    <?php
        require_once("../Nav.php");
    ?>
    <section class="main">
        <div class="titre">
            <h1>Restaurant <?php echo $restau['nom']; ?></h1>
        </div>
        <div class="informations">
            <div class="card">
                <div class="left">
                    <h2><?php echo $restau['nom']; ?></h2>
                    <p><?php echo $restau['lieu']; ?></p>
                    <p><?php echo $restau['adresse']; ?></p>
                    <p>Numéro de téléphone : <?php echo $restau['tel']; ?></p>
                    <p>Prix moyen du repas : <?php echo $restau['prix']; ?></p>
                </div>
                <div class="right">

                </div>
            </div>
        </div>

        <div class="avis">
            <div class="titre">
                <h1>Avis des utilisateurs</h1>
            </div>
            <table>
                <?php
                    while($avis=$reqAvis->fetch()){
                        $reqUser->execute(array($avis['user']));
                        
                    ?> 
                    <tr>
                        <td>
                            <?php
                                echo "<h1>".$reqUser->fetch()['pseudo']."</h1>";
                            ?><p>
                            <?php
                                echo $avis['avis'];
                            ?>
                            </p>
                        </td>
                    </tr> 
                    <?php
                    if(isset($connect)){ // Si l'utilisateur n'est pas connecté, s'arrête après avoir afficher le 1er avis sur le restaurant
                        break;
                    }
                    }
                ?>
            </table>
        </div>
    </section>
    
</body>
</html>