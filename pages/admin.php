<?php
include 'config.php';
include '../includes/header.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../index.php");
    exit;
}


$sql = "SELECT * FROM utilisateurs";
$query = $pdo->prepare($sql);
$query->execute();
$utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);


?>

<main>
    <h1>Liste des utilisateurs</h1>
    <table>
        <thead>
            <?php
            if (!empty($utilisateurs)) {
                foreach (array_keys($utilisateurs[0]) as $champ) {
                    echo "<th>" . $champ . "</th>";
                }
            }
            ?>
        </thead>
        <tbody>
            <?php
            if (!empty($utilisateurs)) {
                foreach ($utilisateurs as $utilisateur) {
                    echo "<tr>";
                    foreach ($utilisateur as $valeur) {
                        echo "<th>" . $valeur . "</th>";
                    }
                }
            }
            ?>
        </tbody>
    </table>
</main>


<?php include '../includes/footer.php'; ?>