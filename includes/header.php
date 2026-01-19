<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Module de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">
                <img src="" alt="">
            </a>
            <ul>
                <?php 
                if(!empty($_SESSION['id'])){
                    echo '
                    <li>
                        <a href="pages/profil.php">Modification</a>
                    </li>
                    <li>
                        <a href="#">Deconnexion</a>
                    </li>';
                } else{
                    echo '
                    <li>
                        <a href="pages/inscription.php">Inscription</a>
                    </li>
                    <li>
                        <a href="pages/connexion.php">Connexion</a>
                    </li>
                    <li>
                        <a href="pages/profil.php">Modification</a>
                    </li>';
                }
                ?>
            </ul>
        </nav>
    </header>