<?php
$pageStyle = 'page.css';
include '../includes/config.php';
include '../includes/header.php';

?>

<main>
    <section class="banner">
        <article class="banner_text">
            <div>
                <h1>Bienvenue sur Origami Space</h1>
                <p>Laissez parler votre créativité et découvrez des réalisations du monde entier.</p>
            </div>
            <div class="banner_btn">
                <a href="inscription.php">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Créer un compte</p>
                </a>
                <a href="connexion.php">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <p>Se connecter</p>
                </a>
            </div>
        </article>
        <article class="banner_img">
            <img src="../images/banner_img.jpg" alt="Image d'un oiseau origami suspendu au plafond par un fil">
            <span class="losange_1"></span>
            <span class="losange_2"></span>
        </article>
    </section>
    <section class="content">
        <h2>Créations de la communauté</h2>
    </section>
    <section class="tags">
        <span class="end_losange"></span>
        <span class="inside_losange"></span>
        <span class="center_losange"></span>
        <span class="inside_losange"></span>
        <span class="end_losange"></span>
    </section>
</main>





<?php include '../includes/footer.php'; ?>