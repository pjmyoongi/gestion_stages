<?php
session_start();
include("../includes/db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Offre invalide.";
    exit();
}

$id = $_GET['id'];

// Récupérer les infos de l'offre
$stmt = $pdo->prepare("SELECT o.*, u.fullname AS nom_entreprise FROM offres o JOIN users u ON o.entreprise_id = u.id WHERE o.id = ?");
$stmt->execute([$id]);
$offre = $stmt->fetch();

if (!$offre) {
    echo "Offre non trouvée.";
    exit();
}

// Vérification spécifique pour les entreprises
if (isset($_SESSION['role']) && $_SESSION['role'] === 'entreprise' && $offre['entreprise_id'] !== $_SESSION['user_id']) {
    echo "Vous ne pouvez consulter que vos propres offres.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de l'offre</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2><?= htmlspecialchars($offre["titre"]) ?></h2>

    <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre["nom_entreprise"]) ?></p>
    <p><strong>Type :</strong> <?= htmlspecialchars($offre["type"]) ?></p>
    <p><strong>Localisation :</strong> <?= htmlspecialchars($offre["localisation"]) ?></p>
    <p><strong>Date de publication :</strong> <?= date("d/m/Y", strtotime($offre["date_publication"])) ?></p>
    <hr>
    <p><?= nl2br(htmlspecialchars($offre["description"])) ?></p>

    <?php if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "candidat"): ?>
        <form method="post" action="../candidatures/postuler.php">
            <input type="hidden" name="offre_id" value="<?= $offre["id"] ?>">
            <button type="submit">Postuler</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
