<?php
session_start();

$user_name = $_SESSION["user_name"] ?? null;
$user_role = $_SESSION["role"] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>À propos</title>
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

<div class="main-container" style="max-width: 900px; margin: 40px auto; padding: 2rem;">
    <h1 style="text-align: center; margin-bottom: 2rem;">À propos de la plateforme</h1>

    <section style="margin-bottom: 2rem;">
        <h2 style="color: #2c3e50;">Notre mission</h2>
        <p style="line-height: 1.8; font-size: 1.05rem; color: #333;">
            Cette plateforme a été conçue par des étudiants de l'Institut Supérieur des Arts Multimédias de la Manouba (ISAMM),
            dans le but de faciliter la mise en relation entre les étudiants à la recherche de stages ou d'opportunités professionnelles,
            et les entreprises souhaitant recruter des jeunes talents prometteurs.
        </p>
    </section>

    <section style="margin-bottom: 2rem;">
        <h2 style="color: #2c3e50;">L'équipe de développement</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
            <?php
            $equipe = [
                ['nom' => 'Lina Meddah', 'role' => 'Développeuse', 'image' => 'lina.jpg'],
                ['nom' => 'Eya Ibdelli', 'role' => 'Développeuse', 'image' => 'eya.jpg'],
                ['nom' => 'Islem Chatti', 'role' => 'Développeuse', 'image' => 'islem.jpg'],
                ['nom' => 'Maryem Baldi', 'role' => 'Testeur', 'image' => 'maryem.jpg'],
                ['nom' => 'Salma Ouni', 'role' => 'Scrum Master', 'image' => 'salma.jpg'],
                ['nom' => 'Aya Taboubi', 'role' => 'Product Owner', 'image' => 'aya.jpg']
            ];

            foreach ($equipe as $membre) {
                echo '
                <div style="flex: 1 1 250px; background-color: #f9f9f9; padding: 1.2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                    <img src="assets/images/team/' . htmlspecialchars($membre['image']) . '" alt="' . htmlspecialchars($membre['nom']) . '" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; margin-bottom: 10px;">
                    <h3 style="margin-bottom: 0.3rem; color: #2c3e50;">' . htmlspecialchars($membre['nom']) . '</h3>
                    <p style="color: #7f8c8d; font-weight: 500;">' . htmlspecialchars($membre['role']) . '</p>
                </div>';
            }
            ?>
        </div>
    </section>

    <section>
        <h2 style="color: #2c3e50;">Notre établissement</h2>
        <p style="line-height: 1.8; font-size: 1.05rem; color: #333;">
            <strong>Institut Supérieur des Arts Multimédias de la Manouba (ISAMM)</strong><br>
            Relevant de l'Université de la Manouba,<br>
            sous la tutelle du Ministère de l'Enseignement Supérieur et de la Recherche Scientifique.
        </p>
    </section>
</div>

<footer style="text-align: center; padding: 20px; background: #f0f2f5; color: #333; margin-top: 50px;">
    <p>&copy; <?= date("Y") ?> StageUP- ISAMM</p>
</footer>

</body>
</html>
