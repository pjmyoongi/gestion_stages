<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Marquer comme lues
$pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);

// Récupérer les notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Notifications</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <a href="../welcome.php" style="color: #6495ED;">← Retour à l'Accueil</a>

    <h2>Mes Notifications</h2>
    <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $n): ?>
            <div class="alert <?= $n['type'] ?>">
                <?= htmlspecialchars($n['message']) ?>
                <div style="font-size: 12px; color: #666;"><?= date("d/m/Y H:i", strtotime($n["created_at"])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune notification pour l’instant.</p>
    <?php endif; ?>
</div>
</body>
</html>
