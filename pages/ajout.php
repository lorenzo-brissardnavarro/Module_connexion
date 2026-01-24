<?php
$pageStyle = 'ajout.css';
include '../includes/config.php';
include '../includes/header.php';


if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

function champs_vides_ajout($post, $files) {
    if (empty($files["image"]["name"]) || empty($post["titre"])) {
        return true;
    }
    return false;
}

function enregistrement($pdo, $image, $titre) {
    $sql = "INSERT INTO realisations (image, titre, user_id) VALUES (:image, :titre, :user_id)";
    $query = $pdo->prepare($sql);
    $query->execute([':image'   => $image, ':titre'   => $titre, ':user_id' => $_SESSION["id"]]);
    return true;
}

function traiter_ajout_image($pdo, $post, $files) {
    if (champs_vides_ajout($post, $files)) {
        return "Veuillez remplir l'ensemble des champs.";
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (empty($files["image"]["tmp_name"])) {
            return "Aucun fichier image pris en compte";
        }
        $file_basename  = pathinfo($files["image"]["name"], PATHINFO_FILENAME);
        $file_extension = pathinfo($files["image"]["name"], PATHINFO_EXTENSION);
        $new_image_name = $file_basename . '_' . date('Ymd_His') . '.' . $file_extension;
        move_uploaded_file($files["image"]["tmp_name"], '../images/' . $new_image_name);
        return enregistrement($pdo, $new_image_name, $post["titre"]);
    }
    return false;
}


$error = "";

if (!empty($_POST)) {
    $result = traiter_ajout_image($pdo, $_POST, $_FILES);
    if ($result === true) {
        header("Location: ajout.php");
        exit;
    } else {
        $error = $result;
    }
}


?>

<main class="auth-page">
    <section class="auth-card">
        <span class="losange losange_1"></span>
        <span class="losange losange_2"></span>
        <article class="auth-header">
            <i class="auth-icon fa-solid fa-images"></i>
            <h1 class="title-login">Ajout</h1>
            <p class="subtitle">Partagez votre création personnelle</p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="image">Déposez votre image</label>
            <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/webp">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" placeholder="Titre de l'image">
            <input type="submit" value="Enregistrer l'image">
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>