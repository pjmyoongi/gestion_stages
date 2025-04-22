<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entreprise") {
    header("Location: ../login.php");
    exit();
}

$entreprise_id = $_SESSION["user_id"];

// Traitement des actions accepter / rejeter
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["candidature_id"], $_POST["action"])) {
    $candidature_id = intval($_POST["candidature_id"]);
    $action = $_POST["action"];

    if (in_array($action, ["accepte", "rejete"])) {
        // Mettre à jour le statut de la candidature
        $stmt = $pdo->prepare("
            UPDATE candidatures c
            JOIN offres o ON c.offre_id = o.id
            SET c.statut = ?
            WHERE c.id = ? AND o.entreprise_id = ?
        ");
        $stmt->execute([$action, $candidature_id, $entreprise_id]);

        // Récupérer l'ID du candidat
        $userStmt = $pdo->prepare("SELECT candidat_id FROM candidatures WHERE id = ?");
        $userStmt->execute([$candidature_id]);
        $candidat = $userStmt->fetchColumn();

        if ($candidat) {
            $message = $action === "accepte"
                ? "🎉 Votre candidature a été acceptée !"
                : "❌ Votre candidature a été rejetée.";

            $notif = $pdo->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)");
            $notif->execute([$candidat, $message, $action === "accepte" ? "success" : "error"]);
        }
    }
}

// Récupérer toutes les candidatures liées à l'entreprise
$query = $pdo->prepare("
    SELECT c.id, u.fullname AS nom_candidat, u.email, u.cv_file, o.titre, c.date_postulation, c.statut
    FROM candidatures c
    JOIN users u ON c.candidat_id = u.id
    JOIN offres o ON c.offre_id = o.id
    WHERE o.entreprise_id = ?
    ORDER BY c.date_postulation DESC
");
$query->execute([$entreprise_id]);
$candidatures = $query->fetchAll();

// Grouper par offre
$grouped = [];
foreach ($candidatures as $c) {
    $grouped[$c["titre"]][] = $c;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Candidatures</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Candidatures reçues</h2>

    <?php if (count($grouped) > 0): ?>
        <?php foreach ($grouped as $titre => $candidats): ?>
            <h3><?= htmlspecialchars($titre) ?></h3>

            <?php foreach ($candidats as $c): ?>
                <div style="margin-bottom: 15px;">
                    <p><strong>Candidat :</strong> <?= htmlspecialchars($c["nom_candidat"]) ?> (<?= $c["email"] ?>)</p>
                    <p><strong>Date :</strong> <?= date("d/m/Y", strtotime($c["date_postulation"])) ?></p>
                    <p><strong>CV :</strong>
                        <?php if (!empty($c["cv_file"])): ?>
                            <a href="../assets/uploads/<?= htmlspecialchars($c["cv_file"]) ?>" target="_blank">Télécharger</a>
                        <?php else: ?>
                            Non fourni
                        <?php endif; ?>
                    </p>

                    <!-- Actions sur la candidature -->
                    <?php if ($c["statut"] === "en_attente"): ?>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="candidature_id" value="<?= $c["id"] ?>">
                            <input type="hidden" name="action" value="accepte">
                            <button type="submit" style="background-color: #28a745; color: white;">Accepter</button>
                        </form>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="candidature_id" value="<?= $c["id"] ?>">
                            <input type="hidden" name="action" value="rejete">
                            <button type="submit" style="background-color: #dc3545; color: white;">Rejeter</button>
                        </form>
                    <?php else: ?>
                        <p><strong>Statut :</strong>
                            <?= $c["statut"] === "accepte" ? "✅ Accepté" : "❌ Rejeté" ?>
                        </p>
                    <?php endif; ?>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune candidature reçue.</p>
    <?php endif; ?>
</div>
</body>
</html>
