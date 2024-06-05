<?php 
session_start();
// Connexion à la base de donnée de restaurants
$bddA = new PDO('mysql:host=localhost;dbname=espace_restaurant;charset=utf8;', 'root', '');
if(isset($_POST['Inscription'])){ // On vérifie que l'utilisateur a rempli tous les champs
    if(!empty($_POST['nom']) AND !empty($_POST['lieu']) AND !empty($_POST['adresse']) AND !empty($_POST['tel']) AND !empty($_POST['prix'])){
        //On sécurise les informations rentrées
        $nom = htmlspecialchars($_POST['nom']);
        $cuisine = htmlspecialchars($_POST['cuisine']);
        $lieu = htmlspecialchars($_POST['lieu']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $tel = htmlspecialchars($_POST['tel']);
        $prix = htmlspecialchars($_POST['prix']);

        
        //Vérification du nom
        $recupRestau = $bddA->prepare('SELECT * FROM restaurant WHERE nom = ?');
        $recupRestau->execute(array($nom));
        if(!($recupRestau->rowCount() > 0)){
            //Vérification du pseudo
            $recupRestau = $bddA->prepare('SELECT * FROM restaurant WHERE adresse = ?');
            $recupRestau->execute(array($adresse));
            if(!($recupRestau->rowCount() > 0)){
                //Vérification du téléphone
                $recupRestau = $bddA->prepare('SELECT * FROM restaurant WHERE tel = ?');
                $recupRestau->execute(array($tel));
                if(!($recupRestau->rowCount() > 0)){
                    // On insère le nouveau restaurant dans la base de donnée
                    $insertRestau = $bddA->prepare('INSERT INTO restaurant(nom, cuisine, lieu, adresse, tel, prix)VALUES(?, ?, ?, ?, ?, ?)');
                    $insertRestau->execute(array($nom, $cuisine, $lieu, $adresse, $tel, $prix));
                    header("Location:../Administration/Index.php");
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
    <script src="https://kit.fontawesome.com/97f200c6ab.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Ajoutez votre restaurant.</title>
</head>
<body>
    <!-- Barre de navigation du fichier Nav.php -->
    <?php require_once("../Nav.php"); ?>
    <section class="main">
        <div class="Liste">
            <div class="card" id="cardForm">
                <form method="POST" action="" align="center">
                    <h1>Ajouter son restaurant</h1>
                    
                    <input type="text" name="nom" autocomplete="off" placeholder="Nom du restaurant">
                    <input type="text" name="cuisine" autocomplete="off" placeholder="Type de cuisine">
                    <input type="text" name="lieu" autocomplete="off" placeholder="Ville">
                    <input type="text" name="adresse" autocomplete="off" placeholder="Adresse">
                    <input type="tel" name="tel" autocomplete="off" placeholder="Numéro de téléphone">
                    <input type="text" name="prix" autocomplete="off" placeholder="Prix moyen d'un repas">
                    <?php if(isset($donneeInc)){
                            echo $donneeInc;
                        } ?>
                    <button id="AjoutRestauButton" type="submit" name="Inscription" value="Envoyer">Ajouter</button>
                    
                </form>
            </div>
        </div>
    </section>
    <?php require_once("../Footer.php"); ?> <!--Barre d'informations du Footer.php -->
</body>
</html>