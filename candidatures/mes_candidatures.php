<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

$candidat_id = $_SESSION["user_id"];

// Supprimer une candidature
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["candidature_id"])) {
    $candidature_id = intval($_POST["candidature_id"]);
    $stmt = $pdo->prepare("DELETE FROM candidatures WHERE id = ? AND candidat_id = ?");
    $stmt->execute([$candidature_id, $candidat_id]);
    header("Location: mes_candidatures.php");
    exit();
}

// Récupérer les candidatures
$stmt = $pdo->prepare("
    SELECT o.*, c.id AS candidature_id, c.date_postulation, c.statut, u.fullname AS entreprise
    FROM candidatures c
    JOIN offres o ON c.offre_id = o.id
    JOIN users u ON o.entreprise_id = u.id
    WHERE c.candidat_id = ?
    ORDER BY c.date_postulation DESC
");
$stmt->execute([$candidat_id]);
$candidatures = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Candidatures</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Mes Candidatures</h2>

    <?php if (count($candidatures) > 0): ?>
        <?php foreach ($candidatures as $c): ?>
            <div style="margin-bottom: 20px;">
                <p><strong>Offre :</strong> <?= htmlspecialchars($c["titre"]) ?> (<?= $c["type"] ?>)</p>
                <p><strong>Entreprise :</strong> <?= htmlspecialchars($c["entreprise"]) ?></p>
                <p><strong>Localisation :</strong> <?= htmlspecialchars($c["localisation"]) ?></p>
                <p><strong>Date de publication :</strong> <?= date("d/m/Y", strtotime($c["date_publication"])) ?></p>
                <p><strong>Date de postulation :</strong> <?= date("d/m/Y", strtotime($c["date_postulation"])) ?></p>
                <p><strong>Statut :</strong>
                    <?php
                    switch ($c["statut"]) {
                        case "accepte":
                            echo "<span style='color: green;'>✅ Acceptée</span>";
                            break;
                        case "rejete":
                            echo "<span style='color: red;'>❌ Rejetée</span>";
                            break;
                        default:
                            echo "<span style='color: orange;'>⏳ En attente</span>";
                    }
                    ?>
                </p>

                <!-- Bouton se retirer -->
                <?php if ($c["statut"] === "en_attente"): ?>
                    <form method="post" style="margin-top: 10px;">
                        <input type="hidden" name="candidature_id" value="<?= $c["candidature_id"] ?>">
                        <button type="submit" style="background-color: #dc3545;">Se retirer</button>
                    </form>
                <?php endif; ?>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez postulé à aucune offre pour le moment.</p>
    <?php endif; ?>
</div>
</body>
</html>
