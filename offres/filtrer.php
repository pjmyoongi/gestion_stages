<?php
session_start();
include("../includes/db.php");

// Récupération des filtres
$type = $_GET["type"] ?? "";
$localisation = $_GET["localisation"] ?? "";

// Requête avec filtres dynamiques
$query = "SELECT o.*, u.fullname AS nom_entreprise FROM offres o 
          JOIN users u ON o.entreprise_id = u.id 
          WHERE 1=1";
$params = [];

if (!empty($type)) {
    $query .= " AND o.type = ?";
    $params[] = $type;
}
if (!empty($localisation)) {
    $query .= " AND o.localisation LIKE ?";
    $params[] = "%" . $localisation . "%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$offres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter les Offres</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
<a href="../welcome.php" style="color: #6495ED;">← Retour à l'Accueil</a>
    <h2>Offres disponibles</h2>

    <!-- Formulaire de filtre -->
    <form method="get">
        <select name="type">
            <option value="">Tous les types</option>
            <option value="stage" <?= $type == "stage" ? "selected" : "" ?>>Stage</option>
            <option value="emploi" <?= $type == "emploi" ? "selected" : "" ?>>Emploi</option>
        </select>

        <input type="text" name="localisation" placeholder="Localisation" value="<?= htmlspecialchars($localisation) ?>">
        <button type="submit">Filtrer</button>
    </form>

    <!-- Liste des offres -->
    <?php if (count($offres) > 0): ?>
        <?php foreach ($offres as $offre): ?>
            <div style="margin-top: 20px; padding: 10px; border-bottom: 1px solid #ccc;">
                <h3><?= htmlspecialchars($offre["titre"]) ?> (<?= $offre["type"] ?>)</h3>
                <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre["nom_entreprise"]) ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($offre["localisation"]) ?></p>
                <p><?= nl2br(htmlspecialchars($offre["description"])) ?></p>
                <a href="consultation.php?id=<?= $offre['id'] ?>">Voir l'offre</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune offre ne correspond à vos critères.</p>
    <?php endif; ?>
</div>
</body>
</html>
