<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Marquer toutes les notifications comme lues
$pdo->prepare("UPDATE notifications SET vue = 1 WHERE user_id = ?")->execute([$user_id]);

// Récupérer les notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY date_creation DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Notifications</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
            border: 1px solid transparent;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .alert small {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        a.return-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #6495ED;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="form-container">
    <a href="../welcome.php" class="return-link">← Retour à l'Accueil</a>
    <h2>Mes Notifications</h2>

    <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $n): ?>
            <div class="alert <?= htmlspecialchars($n['type']) ?>">
                <?= htmlspecialchars($n['message']) ?>
                <small><?= date("d/m/Y H:i", strtotime($n["date_creation"])) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune notification pour l’instant.</p>
    <?php endif; ?>
</div>
</body>
</html>
