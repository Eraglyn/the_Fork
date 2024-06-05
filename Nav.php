
    <nav>
        <a href="../Accueil/accueil.php">
            <img src="../Images/logo.png"><h1>La Cuitochette</h1>
        </a>
        <ul>
            <?php if(isset($_SESSION['admin'])){ // Test si l'utilisateur connecté est un administrateur ?>

                    <li><a href="../Administration/Index.php">Administration</a></li>

            <?php } if(isset($_SESSION['id'])){ //Test si l'utilisateur est connecté ?>

                    <li><a href="../Utilisateur/Profil.php">Mon profil</a></li>
                    <li><a href="../Utilisateur/Deconnexion.php">Se déconnecter</a></li>

            <?php }else{//Si il ne l'est pas, affiche les liens de connexion et d'inscription ?>

                    <li><a href="../Utilisateur/Connexion.php">Connexion</a></li>
                    <li><a href="../Utilisateur/Inscription.php">Inscription</a></li>
            <?php } ?>

        </ul>
    </nav>