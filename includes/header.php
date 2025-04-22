<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("flash.php"); // OK, on garde celle-ci
$user_id = $_SESSION["user_id"] ?? null;
$user_name = $_SESSION["user_name"] ?? null;
$user_role = $_SESSION["role"] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/projet-federe/assets/css/style.css">
</head>
<body>

<!-- le reste du header continue normalement -->

<div style="max-width: 800px; margin: auto; padding-top: 20px;">
    <?php show_flash(); ?>
</div>
<header style="background-color: #6495ED; padding: 15px; color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: auto;">
        <h1 style="font-size: 20px;">Projet Fédéré</h1>
        <nav>
            <a href="/projet-federe/index.php" style="margin-right: 15px; color: white;">Accueil</a>
            <a href="/projet-federe/offres/filtrer.php" style="margin-right: 15px; color: white;">Offres</a>
            <?php if ($user_role === "candidat"): ?>
                <a href="/projet-federe/cv/gestion_cv.php" style="margin-right: 15px; color: white;">Mon CV</a>
                <a href="/projet-federe/profil/modifier_profil.php" style="margin-right: 15px; color: white;">Mon Profil</a>
            <?php elseif ($user_role === "entreprise"): ?>
                <a href="/projet-federe/offres/ajouter.php" style="margin-right: 15px; color: white;">Publier</a>
                <a href="/projet-federe/candidatures/gestion.php" style="margin-right: 15px; color: white;">Candidatures</a>
            <?php endif; ?>
            <a href="/projet-federe/apropos.php" style="margin-right: 15px; color: white;">À propos</a>
            <a href="/projet-federe/contact.php" style="margin-right: 15px; color: white;">Contact</a>
            <?php if ($user_id): ?>
                <span style="margin-right: 15px;">Bonjour, <?= htmlspecialchars($user_name) ?></span>
                <a href="/projet-federe/logout.php" style="color: white;">Déconnexion</a>
            <?php else: ?>
                <a href="/projet-federe/login.php" style="color: white;">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div style="max-width: 800px; margin: auto; padding-top: 20px;">
    <?php show_flash(); ?>
</div>
<footer style="text-align: center; padding: 20px; background: #f0f2f5; color: #333;">
    <p>&copy; <?= date("Y") ?> Projet Fédéré - ISAMM</p>
</footer>
</body>
</html>

