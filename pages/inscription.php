<?php
include '../includes/config.php';
include '../includes/header.php';

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
    $query->execute([':login' => htmlspecialchars($login), ':prenom' => htmlspecialchars($prenom), ':nom' => htmlspecialchars($nom), ':password' => $hash]);
    return true;
}

function verification_champs($pdo, $login, $prenom, $nom, $password, $confirm_password){
    if (strlen($login) < 3 || strlen($login) > 255) {
        return "Le login doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($prenom) < 3 || strlen($prenom) > 255) {
        return "Le prenom doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($nom) < 3 || strlen($nom) > 255) {
        return "Le nom doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($password) < 6 || !preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins 6 caractères et au moins un chiffre";
    }
    elseif ($password != $confirm_password) {
        return "Les deux mots de passe doivent être égaux";
    }
    enregistrement($pdo, $login, $prenom, $nom, $password);
    return;
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
                $result = verification_champs($pdo, trim($_POST["login"]), trim($_POST["prenom"]), trim($_POST["nom"]), $_POST["password"], $_POST["confirm_password"]);
                if ($result === true) {
                    header("Location: connexion.php");
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