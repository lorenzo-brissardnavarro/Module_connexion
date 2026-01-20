<?php
$pageStyle = 'profil.css';
include 'config.php';
include 'includes/header.php';
include '../includes/verification.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

function update_profile($pdo, $id, $login, $prenom, $nom, $password) {
    $sql = "SELECT id FROM utilisateurs WHERE login = :login AND id != :id";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login, ':id' => $id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result !== false) {
        return "Ce nom d'utilisateur est déjà utilisé";
    }
    $sql = "UPDATE utilisateurs SET login = :login, prenom = :prenom, nom = :nom, password = :password WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login, ':prenom' => $prenom, ':nom' => $nom, ':password' => password_hash($password, PASSWORD_DEFAULT), ':id' => $id]);
    $_SESSION['login'] = $login;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['nom'] = $nom;
    return true;
}

$error = "";

if (!empty($_POST)) {
    if (empty($_POST["login"]) || empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
        $error = "Veuillez remplir l'ensemble des champs.";
    } else {
        $result = verification_champs(trim($_POST['login']), trim($_POST['prenom']), trim($_POST['nom']), $_POST['password'], $_POST['confirm_password']);
        if ($result === true) {
            $result = update_profile($pdo, $_SESSION['id'], trim($_POST['login']), trim($_POST['prenom']), trim($_POST['nom']), $_POST['password']);
            if ($result === true) {
                $_SESSION['login']  = trim($_POST['login']);
                $_SESSION['prenom'] = trim($_POST['prenom']);
                $_SESSION['nom']    = trim($_POST['nom']);
                header("Location: profil.php");
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
        <article class="auth-header">
            <img src="icon-profile.svg" alt="" class="auth-icon">
            <h1 class="title-profile">Mon Profil</h1>
            <p class="subtitle">Gérez vos informations personnelles</p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <article class="status-box">
            <p class="status-label">Connecté en tant que</p>
            <div class="status-user">
                <span class="status-name">
                    <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?>
                </span>
                <?php 
                if(!empty($_SESSION['id'])){
                    if(!empty($_SESSION['admin']) && $_SESSION['admin'] === true){
                        echo '<span class="status-role">Admin</span>'
                    }
                }
                ?>
            </div>
        </article>
        <form action="" method="POST">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo htmlspecialchars($_SESSION['login']); ?>">
            <div class="row">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($_SESSION['prenom']); ?>">
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($_SESSION['nom']); ?>">
                </div>
            </div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Nouveau mot de passe">
            <label for="confirm_password">Confirmation du mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmation du mot de passe">
            <input type="submit" value="Modifier">
        </form>

    </section>
</main>

<?php include 'includes/footer.php'; ?>