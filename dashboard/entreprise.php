<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entreprise") {
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
$user_role = $_SESSION["role"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Entreprise</title>
    <link rel="stylesheet" href="../assets/css/style1.css">
</head>
<body>

<header style="background-color: #6495ED; padding: 15px; color: white;"> 
    <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: auto;">
        <h1 style="font-size: 20px;">StageUP</h1>
        <nav>
            <a href="../welcome.php" style="margin-right: 15px; color: white;">Accueil</a>
            <a href="../apropos.php" style="margin-right: 15px; color: white;">À propos</a>
            <a href="../contact.php" style="margin-right: 15px; color: white;">Contact</a>
            <span style="margin-right: 15px;">Bonjour, <?= htmlspecialchars($user_name) ?></span>
            <a href="../logout.php" style="color: white;">Déconnexion</a>
        </nav>
    </div>
</header>

<div class="main-container" style="text-align: center;">
    <h2>Bienvenue, <?= htmlspecialchars($user_name) ?> !</h2>
    <p>Vous êtes connecté en tant qu’<strong>entreprise</strong>.</p>

    <a href="../offres/ajouter.php"><button>Publier une offre</button></a>
    <a href="../offres/mes_offres.php"><button>Gestion offres</button></a>
    <a href="../candidatures/gestion.php"><button>Gestion candidatures</button></a>
</div>

<footer style="text-align: center; padding: 20px; background: #f0f2f5; color: #333; margin-top: 30px;">
    <p>&copy; <?= date("Y") ?> StageUP - ISAMM</p>
</footer>

</body>
</html>
