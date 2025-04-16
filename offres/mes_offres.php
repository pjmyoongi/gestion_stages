<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entreprise") {
    header("Location: ../login.php");
    exit();
}

$entreprise_id = $_SESSION["user_id"];

// Supprimer une offre
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_offer_id"])) {
    $offer_id = intval($_POST["delete_offer_id"]);

    // Vérifie si l’offre appartient à cette entreprise
    $check = $pdo->prepare("SELECT id FROM offres WHERE id = ? AND entreprise_id = ?");
    $check->execute([$offer_id, $entreprise_id]);

    if ($check->rowCount() > 0) {
        // Supprime l’offre
        $delete = $pdo->prepare("DELETE FROM offres WHERE id = ?");
        $delete->execute([$offer_id]);
        header("Location: mes_offres.php");
        exit();
    }
}

// Récupérer les offres
$stmt = $pdo->prepare("SELECT * FROM offres WHERE entreprise_id = ? ORDER BY date_publication DESC");
$stmt->execute([$entreprise_id]);
$offres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Offres</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        form.delete-form {
            display: inline;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Mes Offres Publiées</h2>

    <?php if (count($offres) > 0): ?>
        <?php foreach ($offres as $o): ?>
            <div style="margin-bottom: 20px;">
                <h3><?= htmlspecialchars($o["titre"]) ?> (<?= $o["type"] ?>)</h3>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($o["localisation"]) ?></p>
                <p><strong>Date :</strong> <?= date("d/m/Y", strtotime($o["date_publication"])) ?></p>
                <p><?= nl2br(htmlspecialchars($o["description"])) ?></p>

                <a href="consultation.php?id=<?= $o['id'] ?>">Voir détails</a>

                <!-- Bouton Supprimer -->
                <form method="post" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                    <input type="hidden" name="delete_offer_id" value="<?= $o['id'] ?>">
                    <button type="submit" class="delete-btn">Supprimer</button>
                </form>

                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune offre publiée pour le moment.</p>
    <?php endif; ?>
</div>
</body>
</html>
