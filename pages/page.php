<?php
$pageStyle = 'page.css';
include '../includes/config.php';
include '../includes/header.php';


function recuperation($pdo){
    $sql = "SELECT id, image, titre, user_id FROM realisations";
    $query = $pdo->prepare($sql);
    $query->execute();
    $images = $query->fetchAll(PDO::FETCH_ASSOC);
    return $images;
}

if (!empty($_POST['delete_id'])) {
    if ($_POST['delete_id'] === $_SESSION['id']) {
        echo "<p>Vous ne pouvez pas supprimer votre propre compte.</p>";
    } else {
        $sql = "SELECT image FROM realisations WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->execute([':id' => $_POST['delete_id']]);
        $images = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($images as $img) {
            $filePath = '../images/' . $img['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $sql = "DELETE FROM realisations WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->execute([':id' => $_POST['delete_id']]);
        header("Location: page.php");
        exit;
    }
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
                    <h3>' . $realisations['titre'] . '</h3>';
                    if(isset($_SESSION['id']) && $_SESSION['id'] === $realisations['user_id']){
                        echo '
                            <form method="POST" class="delete-form">
                                <input type="hidden" name="delete_id" value="' . htmlspecialchars($realisations['id']) . '">
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>';
                    }
                echo '</article>';
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