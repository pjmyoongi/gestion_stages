<?php include("includes/db.php"); ?>
<?php if (isset($_SESSION['error'])): ?>
    <script>
        alert("<?= addslashes($_SESSION['error']) ?>");
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion / Inscription</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 100px;
    }

    h1 {
      margin-bottom: 20px;
      color: #6495ED;
    }

    .form-container {
      display: flex;
      gap: 40px;
      background-color: white;
      padding: 30px 50px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    form {
      display: flex;
      flex-direction: column;
      width: 280px;
    }

    form h2 {
      margin-bottom: 15px;
      color: #6495ED;
    }

    input, select {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background-color: #6495ED;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: #4178D6;
    }

    @media screen and (max-width: 768px) {
      .form-container {
        flex-direction: column;
      }
    }
  </style>
  <script defer src="assets/js/register.js"></script>
</head>
<body>

  <div class="container">
    <h1>Bienvenue</h1>
    <div class="form-container">

      <!-- Formulaire de connexion -->
      <form method="post" action="includes/login_process.php">
        <h2>Connexion</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
      </form>

      <!-- Formulaire d'inscription -->
      <form method="post" action="includes/register_process.php">
        <h2>Inscription</h2>
        <input type="text" name="fullname" placeholder="Nom complet" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer le mot de passe" required>
        <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'] ?? ''); ?>">
        <select name="role" required>
          <option value="">Sélectionnez votre rôle</option>
          <option value="candidat">Candidat</option>
          <option value="entreprise">Entreprise</option>
        </select>
        <button type="submit">S'inscrire</button>
      </form>

    </div>
  </div>

</body>
</html>
