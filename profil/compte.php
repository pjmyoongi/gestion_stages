<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Récupérer les informations du candidat
$query = $pdo->prepare("SELECT fullname, email, universite, specialisation, telephone, photo, cv_text, cv_file FROM users WHERE id = ?");
$query->execute([$userId]);
$user = $query->fetch();

if (!$user) {
    echo "Informations du profil non disponibles.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        .profile-photo {
            max-width: 120px;
            max-height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Mon Profil</h2>
    <a href="../welcome.php" style="color: #6495ED;">← Retour à l'Accueil</a>
    <!-- Photo de profil -->
    <div class="section">
        <?php if (!empty($user["photo"]) && file_exists("../assets/uploads/" . $user["photo"])): ?>
            <img src="../assets/uploads/<?= htmlspecialchars($user["photo"]) ?>" alt="Photo de profil" class="profile-photo">
        <?php else: ?>
            <p><em>Aucune photo de profil</em></p>
        <?php endif; ?>
    </div>

    <!-- Informations personnelles -->
    <div class="section">
        <p><strong>Nom :</strong> <?= htmlspecialchars($user["fullname"]) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user["email"]) ?></p>
        <p><strong>Université :</strong> <?= htmlspecialchars($user["universite"] ?? 'Non renseignée') ?></p>
        <p><strong>Spécialisation :</strong> <?= htmlspecialchars($user["specialisation"] ?? 'Non renseignée') ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($user["telephone"] ?? 'Non renseigné') ?></p>
    </div>

    <!-- CV -->
    <div class="section">
        <h3>Mon CV</h3>
        <p><strong>Description :</strong></p>
        <p><?= nl2br(htmlspecialchars($user["cv_text"] ?? 'Non renseigné')) ?></p>

        <p><strong>Fichier PDF :</strong>
            <?php if (!empty($user["cv_file"]) && file_exists("../assets/uploads/" . $user["cv_file"])): ?>
                <a href="../assets/uploads/<?= htmlspecialchars($user["cv_file"]) ?>" target="_blank">Télécharger</a>
            <?php else: ?>
                <span>Non fourni</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="section">
        <a href="modifier_profil.php"><button>Gestion profil</button></a>
        <a href="../cv/gestion_cv.php"><button>Gestion CV</button></a>
    </div>
</div>
</body>
</html>
