<?php
session_start();

// Redirection si non connecté
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entreprise") {
    header("Location: ../login.php");
    exit();
}

$nom = $_SESSION["user_name"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Entreprise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Bienvenue, <?= htmlspecialchars($nom) ?> !</h2>
        <p>Vous êtes connecté en tant qu’<strong>entreprise</strong>.</p>

        <a href="../offres/ajouter.php"><button>Publier une offre</button></a>
        <a href="../offres/mes_offres.php"><button>Gestion offres</button></a>
        <a href="../candidatures/gestion.php"><button>Gestion candidatures</button></a>

    </div>
</body>
</html>
