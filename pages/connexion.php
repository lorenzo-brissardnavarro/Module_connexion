<?php
$pageStyle = 'connexion.css';
include '../includes/config.php';
include '../includes/header.php';

function champs_vides_connexion($post) {
    if (empty($post["login"]) || empty($post["password"])) {
        return true;
    }
    return false;
}

function connexion($pdo, $login, $password) {
    $sql = "SELECT * FROM utilisateurs WHERE login = :login";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if ($user === false) {
        return "Identifiant ou mot de passe incorrect";
    }
    if (!password_verify($password, $user['password'])) {
        return "Identifiant ou mot de passe incorrect";
    }
    $_SESSION['id'] = $user['id'];
    $_SESSION['login'] = $user['login'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['nom'] = $user['nom'];
    if ($user['login'] === 'admin') {
        $_SESSION['admin'] = true;
    } else {
        $_SESSION['admin'] = false;
    }
    return true;
}

function traiter_connexion($pdo, $post) {
    if (champs_vides_connexion($post)) {
        return "Veuillez renseigner votre login et votre mot de passe.";
    }
    return connexion($pdo, trim($post["login"]), $post["password"]);
}


$error = "";

if (!empty($_POST)) {
    $result = traiter_connexion($pdo, $_POST);
    if ($result === true) {
        header("Location: profil.php");
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
            <i class="auth-icon fa-solid fa-arrow-right-to-bracket"></i>
            <h1 class="title-login">Connexion</h1>
            <p class="subtitle">Accédez à votre espace personnel</p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <article class="demo-box">
            <p><strong>Comptes de démonstration :</strong></p>
            <p><strong>Admin :</strong> admin / admin123</p>
            <p><strong>User :</strong> utilisateur / user123</p>
        </article>
        <form action="" method="POST">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" placeholder="Votre identifiant">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Votre mot de passe">
            <input type="submit" value="Se connecter">
        </form>
        <p class="footer-link">
            Pas encore de compte ? <a href="inscription.php">S'inscrire</a>
        </p>
    </section>
</main>


<?php include '../includes/footer.php'; ?>