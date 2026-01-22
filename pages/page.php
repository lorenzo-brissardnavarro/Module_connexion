<?php
$pageStyle = 'page.css';
include '../includes/config.php';
include '../includes/header.php';


function recuperation($pdo){
    $sql = "SELECT image, titre FROM realisations";
    $query = $pdo->prepare($sql);
    $query->execute();
    $images = $query->fetchAll(PDO::FETCH_ASSOC);
    return $images;
}


?>

<main>
    <section class="banner">
        <article class="banner_text">
            <div>
                <h1>Bienvenue sur Origami Space</h1>
                <p>Laissez parler votre créativité et découvrez des réalisations du monde entier.</p>
            </div>
            <?php
            if(!isset($_SESSION['id'])){
                echo '<div class="banner_btn">
                <a href="inscription.php">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Créer un compte</p>
                </a>
                <a href="connexion.php">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <p>Se connecter</p>
                </a>
            </div>';
            } else{
                echo '<div class="banner_btn">
                <a href="ajout.php">
                    <i class="fa-solid fa-images"></i>
                    <p>Ajouter une création</p>
                </a>';
            }
            ?>
        </article>
        <article class="banner_img">
            <img src="../images/banner_img.jpg" alt="Image d'un oiseau origami suspendu au plafond par un fil">
            <span class="losange_1"></span>
            <span class="losange_2"></span>
        </article>
    </section>
    <section class="origami-gallery">
        <h2>Œuvres en Origami</h2>
        <p class="gallery_subtitle">
            Découvrez une sélection de créations en origami, mêlant précision, poésie et géométrie.
        </p>
        <div class="gallery_grid">
            <?php 
            foreach (recuperation($pdo) as $realisations) {
                echo '
                <article class="gallery_card">
                    <img src="../images/' . $realisations['image'] . '" alt="' . $realisations['titre'] . '">
                    <h3>' . $realisations['titre'] . '</h3>
                </article>';
            }
            ?>
        </div>
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