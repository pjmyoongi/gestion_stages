<?php
session_start();

$user_name = $_SESSION["user_name"] ?? null;
$user_role = $_SESSION["role"] ?? null;

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // TODO : envoyer email ou enregistrer en base de données
    $messageSent = true;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
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
                <a href="candidatures/mes_candidatures.php" style="margin-right: 15px; color: white;">Mes Candidatures</a>
                <a href="profil/notifications.php" style="margin-right: 15px; color: white;">Notifications</a>
            <?php elseif ($user_role === "entreprise"): ?>
                <a href="offres/ajouter.php" style="margin-right: 15px; color: white;">Publier</a>
                <a href="candidatures/gestion.php" style="margin-right: 15px; color: white;">Candidatures</a>
            <?php endif; ?>
            <a href="apropos.php" style="margin-right: 15px; color: white;">À propos</a>
            <a href="contact.php" style="margin-right: 15px; color: white;">Contact</a>

            <?php if ($user_name): ?>
                <span style="margin-right: 15px;">Bonjour, <?= htmlspecialchars($user_name) ?></span>
                <a href="logout.php" style="color: white;">Déconnexion</a>
            <?php else: ?>
                <a href="register.php" style="color: white;">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="main-container" style="max-width: 700px; margin: 40px auto; padding: 2rem;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Contactez-nous</h1>

    <?php if ($messageSent): ?>
        <div style="background-color: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 6px; margin-bottom: 2rem; color: #155724;">
            ✅ Merci pour votre message, <?= htmlspecialchars($name) ?> ! Nous vous répondrons dès que possible.
        </div>
    <?php else: ?>
        <form method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
            <label for="name">Votre nom</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Votre message</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit" style="background-color: #6495ED; color: white; padding: 10px; border: none; border-radius: 6px; cursor: pointer;">
                Envoyer le message
            </button>
        </form>
    <?php endif; ?>

    <hr style="margin: 3rem 0;">

    <div>
        <h2>Informations de contact</h2>
        <p>
            <strong>Adresse :</strong> ISAMM, Université de la Manouba, Tunisie<br>
            <strong>Email :</strong> contact@isamm-stage.tn<br>
            <strong>Téléphone :</strong> +216 71 000 000
        </p>
    </div>
</div>

<footer style="text-align: center; padding: 20px; background: #f0f2f5; color: #333; margin-top: 50px;">
    <p>&copy; <?= date("Y") ?> StageUP - ISAMM</p>
</footer>

</body>
</html>
