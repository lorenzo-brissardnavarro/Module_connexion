<?php
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


?>

<main>
    <h1>Page d'inscription</h1>
    <form action="" method="POST">
        <input type="text" name="login" placeholder="Entrer votre login">
        <input type="text" name="prenom" placeholder="Entrer votre prénom">
        <input type="text" name="nom" placeholder="Entrer votre nom">
        <input type="password" name="password" placeholder="Choisissez votre mot de passe">
        <input type="password" name="confirm_password" placeholder="Confirmation de votre mot de passe">
        <input type="submit" value="S'inscrire">
    </form>

    <section>
        <?php
        if (!empty($_POST)) {
            if (empty($_POST["login"]) || empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
                echo "<p>Veuillez remplir l'ensemble des champs</p>";
            } else {
                $result = verification_champs(trim($_POST["login"]), trim($_POST["prenom"]), trim($_POST["nom"]), $_POST["password"], $_POST["confirm_password"]);
                if ($result === true) {
                    $result = enregistrement($pdo, trim($_POST["login"]), trim($_POST["prenom"]), trim($_POST["nom"]), $_POST["password"]);
                    if($result === true) {
                        header("Location: connexion.php");
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

<?php include '../includes/footer.php'; ?>