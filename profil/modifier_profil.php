<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $universite = trim($_POST["universite"]);
    $specialisation = trim($_POST["specialisation"]);
    $telephone = trim($_POST["telephone"]);

    // Upload de la photo
    $photo = $_FILES["photo"]["name"];
    $photo_path = "../assets/uploads/" . basename($photo);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path);

    // Mise à jour des infos
    $update = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, universite = ?, specialisation = ?, telephone = ?, photo = ? WHERE id = ?");
    $update->execute([$fullname, $email, $universite, $specialisation, $telephone, $photo, $userId]);

    echo "Profil mis à jour avec succès.";
}

// Récupération des infos existantes
$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$userId]);
$user = $query->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Modifier votre profil</h2>
    <a href="../profil/compte.php" style="color: #6495ED;">← Retour à mon compte</a>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="text" name="universite" placeholder="Université" value="<?= htmlspecialchars($user['universite']) ?>">
        <input type="text" name="specialisation" placeholder="Spécialisation" value="<?= htmlspecialchars($user['specialisation']) ?>">
        <input type="text" name="telephone" placeholder="Téléphone" value="<?= htmlspecialchars($user['telephone']) ?>">
        <label>Photo de profil :</label>
        <input type="file" name="photo" accept="image/*">
        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
