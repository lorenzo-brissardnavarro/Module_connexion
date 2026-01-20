<?php
include 'config.php';
include '../includes/header.php';

if (!isset($_SESSION['id'])) {
    header("Location: page.php");
    exit;
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: page.php");
    exit;
}


$sql = "SELECT * FROM utilisateurs";
$query = $pdo->prepare($sql);
$query->execute();
$utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);


if (!empty($_POST['delete_id'])) {
    if ($delete_id === $_SESSION['id']) {
        echo "<p>Vous ne pouvez pas supprimer votre propre compte.</p>";
    } else {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->execute([':id' => $delete_id]);
        header("Location: admin.php");
        exit;
    }
}


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
                echo "<th>Action</th>";
            }
            ?>
        </thead>
        <tbody>
        <?php
        if (!empty($utilisateurs)) {
            foreach ($utilisateurs as $utilisateur) {
                echo "<tr>";
                foreach ($utilisateur as $valeur) {
                    echo "<td>" . htmlspecialchars($valeur) . "</td>";
                }
                echo "<td>";
                ?>
                <form method="POST"">
                    <input type="hidden" name="delete_id" value="<?php $utilisateur['id'] ?>">
                    <button type="submit">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                <?php
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
</main>


<?php include '../includes/footer.php'; ?>