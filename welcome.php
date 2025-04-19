<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$user_role = $_SESSION["role"];

$notif_count = 0;
if ($user_role === "candidat") {
    $notif_stmt = $pdo->prepare("SELECT COUNT(*) FROM candidatures WHERE candidat_id = ? AND statut IN ('accepte', 'rejete')");
    $notif_stmt->execute([$user_id]);
    $notif_count = $notif_stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>

<header style="background-color: #6495ED; padding: 15px; color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: auto;">
        <h1 style="font-size: 20px;">StageUP</h1>
        <nav>
            <a href="welcome.php" style="margin-right: 15px; color: white;">Accueil</a>
            <?php if ($user_role === "candidat"): ?>
                <a href="profil/compte.php" style="margin-right: 15px; color: white;">Mon Compte</a>
                <a href="candidatures/mes_candidatures.php" style="margin-right: 15px; color: white;">
                    Consultation offres
                    <?php if ($notif_count > 0): ?>
                        <span style="background-color: red; color: white; padding: 2px 6px; font-size: 12px; border-radius: 50%; margin-left: 5px;">
                            <?= $notif_count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="profil/notifications.php" style="margin-right: 15px; color: white;">Notifications</a>
            <?php elseif ($user_role === "entreprise"): ?>
                <a href="offres/ajouter.php" style="margin-right: 15px; color: white;">Publier</a>
                <a href="candidatures/gestion.php" style="margin-right: 15px; color: white;">Candidatures</a>
            <?php endif; ?>
            <a href="apropos.php" style="margin-right: 15px; color: white;">À propos</a>
            <a href="contact.php" style="margin-right: 15px; color: white;">Contact</a>

            <span style="margin-right: 15px;">Bonjour, <?= htmlspecialchars($user_name) ?></span>
            <a href="logout.php" style="color: white;">Déconnexion</a>
        </nav>
    </div>
</header>

<div class="main-container">
    <?php if ($user_role === "candidat" && $notif_count > 0): ?>
        <div style="background-color: #fef3c7; padding: 10px; margin: 20px auto; border: 1px solid #fcd34d; border-radius: 8px; text-align: center; max-width: 800px;">
            <strong>🔔 Vous avez <?= $notif_count ?> réponse(s) à vos candidatures.</strong><br>
            <a href="candidatures/mes_candidatures.php">Voir les détails</a>
        </div>
    <?php endif; ?>

    <h2>Bienvenue, <?= htmlspecialchars($user_name) ?> !</h2>
    <p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($user_role) ?></strong>.</p>

    <?php if ($user_role === "candidat"): ?>
        <a href="offres/filtrer.php"><button>Voir les offres</button></a>
    <?php elseif ($user_role === "entreprise"): ?>
        <a href="offres/ajouter.php"><button>Publier une offre</button></a>
        <a href="candidatures/gestion.php"><button>Voir les candidatures</button></a>
    <?php endif; ?>
</div>

<footer style="text-align: center; padding: 20px; background: #f0f2f5; color: #333;">
    <p>&copy; <?= date("Y") ?> StageUP- ISAMM</p>
</footer>
</body>
</html>
