<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
if(isset($_POST['Inscription'])){
    if(!empty($_POST['nom']) AND !empty($_POST['lieu']) AND !empty($_POST['adresse']) AND !empty($_POST['tel']) AND !empty($_POST['prix'])){
        $nom = htmlspecialchars($_POST['nom']);
        $lieu = htmlspecialchars($_POST['lieu']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $tel = htmlspecialchars($_POST['tel']);
        $prix = htmlspecialchars($_POST['prix']);

        
        //Vérification du nom
        $recupRestau = $bdd->prepare('SELECT * FROM restaurant WHERE nom = ?');
        $recupRestau->execute(array($nom));
        if(!($recupRestau->rowCount() > 0)){
            //Vérification du pseudo
            $recupRestau = $bdd->prepare('SELECT * FROM restaurant WHERE adresse = ?');
            $recupRestau->execute(array($adresse));
            if(!($recupRestau->rowCount() > 0)){
                //Vérification du téléphone
                $recupRestau = $bdd->prepare('SELECT * FROM restaurant WHERE tel = ?');
                $recupRestau->execute(array($tel));
                if(!($recupRestau->rowCount() > 0)){
                    $insertRestau = $bdd->prepare('INSERT INTO restaurant(nom, lieu, adresse, tel, prix)VALUES(?, ?, ?, ?, ?)');
                    $insertRestau->execute(array($nom, $lieu, $adresse, $tel, $prix));
                }else{
                    $donneeInc="Ce numéro de téléphone est déjà prit !";
                }
            }else{
                $donneeInc=" Il y a déjà un restaurant enregistré à cette adresse !";
            }            
        }else{
            $donneeInc="Ce nom de restaurant n'est pas disponible !";
        }        
    }else{
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
    <link rel="stylesheet" href="styleC.css">
    <title>Ajoutez votre restaurant.</title>
</head>
<body>
    <?php
        require_once("../Nav.php");
    ?>
    <form method="POST" action="" align="center">
        <h1>Ajouter son restaurant</h1>
        <div class="inputs">
            <input type="text" name="nom" autocomplete="off" placeholder="Nom du restaurant">
            <input type="text" name="lieu" autocomplete="off" placeholder="Ville">
            <input type="text" name="adresse" autocomplete="off" placeholder="Adresse">
            <input type="tel" name="tel" autocomplete="off" placeholder="Numéro de téléphone">
            <input type="text" name="prix" autocomplete="off" placeholder="Prix moyen d'un repas">
            <?php
                if(isset($donneeInc)){
                    echo $donneeInc;
                }
            ?>
        </div>
        <div align="center">
            <input type="submit" name="Inscription">
        </div>
    </form>
</body>
</html>