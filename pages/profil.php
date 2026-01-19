<?php
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


?>

<main>
    <h1>Modification du profil</h1>
    <form action="" method="POST">
        <input type="text" name="login" placeholder="Entrer votre nouveau login" value="<?php echo htmlspecialchars($_SESSION['login']); ?>">
        <input type="text" name="prenom" placeholder="Entrer votre nouveau prénom" value="<?php echo htmlspecialchars($_SESSION['prenom']); ?>">
        <input type="text" name="nom" placeholder="Entrer votre nouveau nom" value="<?php echo htmlspecialchars($_SESSION['nom']); ?>">
        <input type="password" name="password" placeholder="Nouveau mot de passe">
        <input type="password" name="confirm_password" placeholder="Confirmation du mot de passe">
        <input type="submit" value="Modifier">
    </form>
    <section>
        <?php
        if (!empty($_POST)) {
            if (empty($_POST["login"]) || empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
                echo "<p>Veuillez remplir l'ensemble des champs</p>";
            } else {
                $result = verification_champs(trim($_POST['login']), trim($_POST['prenom']), trim($_POST['nom']), $_POST['password'], $_POST['confirm_password']);
                if ($result === true) {
                    $result = update_profile($pdo, $_SESSION['id'], trim($_POST['login']), trim($_POST['prenom']), trim($_POST['nom']), $_POST['password']);
                    if($result === true){
                        header("Location: profile.php");
                        exit;
                    } else {
                        echo "<p>" . $result . "</p>";
                    }
                } else {
                    echo "<p>" . $result . "</p>";
                }
            }
        }
        ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>