<?php
$pageStyle = 'admin.css';
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['id'])) {
    header("Location: page.php");
    exit;
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: page.php");
    exit;
}


function get_utilisateurs($pdo) {
    $sql = "SELECT * FROM utilisateurs";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function supprimer_utilisateur($pdo, $user_id) {
    if ($user_id == $_SESSION['id']) {
        return "Vous ne pouvez pas supprimer votre propre compte.";
    }
    $sql = "SELECT image FROM realisations WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->execute([':user_id' => $user_id]);
    $images = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($images as $img) {
        $filePath = '../images/' . $img['image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $sql = "DELETE FROM realisations WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->execute([':user_id' => $user_id]);

    $sql = "DELETE FROM utilisateurs WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([':id' => $user_id]);
    return true;
}


$error = "";

if (!empty($_POST['delete_id'])) {
    $result = supprimer_utilisateur($pdo, $_POST['delete_id']);
    if ($result === true) {
        header("Location: admin.php");
        exit;
    } else {
        $error = $result;
    }
}

$utilisateurs = get_utilisateurs($pdo);


?>

<main>
    <section class="admin-container">
        <section class="admin-header">
            <h1>Administration</h1>
            <p>Gestion des utilisateurs de l'Origami Space</p>
        </section>
        <section class="admin-summary">
            <?php echo count($utilisateurs) ?> utilisateur(s) enregistré(s)
        </section>
        <section class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <?php
                        if (!empty($utilisateurs)) {
                            foreach (array_keys($utilisateurs[0]) as $champ) {
                                echo "<th>" . $champ . "</th>";
                            }
                            echo "<th>Actions</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($utilisateurs)) {
                    foreach ($utilisateurs as $utilisateur) {
                        echo '<tr>';
                        foreach ($utilisateur as $valeur) {
                            echo '<td>' . htmlspecialchars($valeur) . '</td>';
                        }
                        echo '<td>';
                        if ($utilisateur['login'] === $_SESSION['login']) {
                            echo '<p>Vous</p>';
                        } else {
                            echo '
                                <form method="POST">
                                    <input type="hidden" name="delete_id" value="' . htmlspecialchars($utilisateur['id']) . '">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </section>
    </section>
</main>



<?php include '../includes/footer.php'; ?>