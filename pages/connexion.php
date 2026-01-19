<?php
include '../includes/config.php';
include '../includes/header.php';

function login_management($pdo, $login, $password) {
    $sql = "SELECT * FROM users WHERE login = :login";
    $query = $pdo->prepare($sql);
    $query->execute([':login' => $login]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if ($user === false) {
        return "Identifiant ou mot de passe incorrect";
    }
    if (!password_verify($password, $user['password'])) {
        return "Identifiant ou mot de passe incorrect";
    }
    $_SESSION['id'] = [$user['id']];
    return true;
}


?>


<main>
    <h1>Page de connexion</h1>
    <form action="" method="POST">
        <input type="text" name="login" placeholder="Entrer votre login">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="submit" value="Se connecter">
    </form>
    <section>
        <?php
        if (!empty($_POST)) {
            if (empty($_POST["login"]) || empty($_POST["password"])) {
                echo "<p>Veuillez remplir l'ensemble des champs</p>";
            } else {
                $result = login_management($pdo, trim($_POST["login"]), $_POST["password"]);
                if ($result === true) {
                    header("Location: profile.php");
                    exit;
                } else {
                    echo "<p>" . $result . "</p>";
                }
            }
        }
        ?>
    </section>
</main>

<?php include '../includes/footer.php'; ?>