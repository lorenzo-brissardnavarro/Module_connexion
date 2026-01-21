<?php
$pageStyle = 'inscription.css';
include '../includes/config.php';
include '../includes/header.php';
include '../includes/verification.php';

function enregistrement($pdo, $login, $prenom, $nom, $password) {
    $sql = "SELECT id FROM utilisateurs WHERE login = :login";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result !== false) {
        return "Ce nom d'utilisateur est déjà utilisé";
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (:login, :prenom, :nom, :password)";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login, ':prenom' => $prenom, ':nom' => $nom, ':password' => $hash]);
    return true;
}

$error = "";

if (!empty($_POST)) {
    if (empty($_POST["login"]) || empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
        $error = "Veuillez remplir l'ensemble des champs.";
    } else {
        $result = verification_champs(trim($_POST["login"]), trim($_POST["prenom"]), trim($_POST["nom"]), $_POST["password"], $_POST["confirm_password"]);
        if ($result === true) {
            $result = enregistrement($pdo, trim($_POST["login"]), trim($_POST["prenom"]), trim($_POST["nom"]), $_POST["password"]);
            if ($result === true) {
                header("Location: connexion.php");
                exit;
            } else {
                $error = $result;
            }
        } else {
            $error = $result;
        }
    }
}


?>

<main class="auth-page">
    <section class="auth-card">
        <span class="losange losange_1"></span>
        <span class="losange losange_2"></span>
        <article class="auth-header">
            <i class="auth-icon fa-solid fa-user-plus"></i>
            <h1>Inscription</h1>
            <p class="subtitle">Créez votre compte Origami Space</p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <form action="" method="POST">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" placeholder="Votre identifiant">
            <div class="row">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" placeholder="Votre prénom">
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" placeholder="Votre nom">
                </div>
            </div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Min. 6 caractères">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez votre mot de passe">
            <input type="submit" value="S'inscrire">
        </form>
        <p class="footer-link">
            Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a>
        </p>
    </section>
</main>


<?php include '../includes/footer.php'; ?>