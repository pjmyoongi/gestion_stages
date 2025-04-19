<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entreprise") {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $type = $_POST["type"];
    $localisation = trim($_POST["localisation"]);

    $stmt = $pdo->prepare("INSERT INTO offres (entreprise_id, titre, description, type, localisation) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION["user_id"], $titre, $description, $type, $localisation]);

    echo "Offre ajoutée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Offre</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Ajouter une Offre</h2>

    <form method="post">
        <input type="text" name="titre" placeholder="Titre de l'offre" required>
        <textarea name="description" placeholder="Description détaillée" rows="5" required></textarea>
        <select name="type" required>
            <option value="">Type d'offre</option>
            <option value="stage">Stage</option>
            <option value="emploi">Emploi</option>
        </select>
        <input type="text" name="localisation" placeholder="Localisation" required>
        <button type="submit">Publier l'offre</button>
    </form>
</div>
</body>
</html>
