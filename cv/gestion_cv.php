<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidat') {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Check if users table has the required columns
try {
    $checkColumns = $pdo->prepare("SHOW COLUMNS FROM users LIKE 'cv_text'");
    $checkColumns->execute();
    if ($checkColumns->rowCount() == 0) {
        die("Database error: Required columns missing in users table");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cv_text = trim($_POST["cv_text"]);
    
    // Validate text
    if (empty($cv_text)) {
        $error = "Veuillez saisir une description de votre profil.";
    }
    
    // Validate file
    if (!isset($_FILES["cv_file"]) || $_FILES["cv_file"]["error"] != UPLOAD_ERR_OK) {
        $error = "Erreur lors du téléchargement du fichier.";
    } else {
        $file = $_FILES["cv_file"];
        
        // Check file size (max 5MB)
        if ($file["size"] > 5000000) {
            $error = "Le fichier est trop volumineux (max 5MB).";
        }
        
        // Check file type (PDF only)
        $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($fileType != "pdf") {
            $error = "Seuls les fichiers PDF sont autorisés.";
        }
    }
    
    // If no errors, process upload
    if (empty($error)) {
        $target_dir = "../assets/uploads/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Generate unique filename to prevent overwrites
        $newFilename = uniqid() . '_' . basename($file["name"]);
        $target_file = $target_dir . $newFilename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            try {
                $stmt = $pdo->prepare("UPDATE users SET cv_text = ?, cv_file = ? WHERE id = ?");
                $stmt->execute([$cv_text, $newFilename, $userId]);
                $success = "CV mis à jour avec succès.";
            } catch (PDOException $e) {
                // Delete the uploaded file if DB update fails
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
                $error = "Erreur lors de la mise à jour de la base de données.";
            }
        } else {
            $error = "Erreur lors de l'enregistrement du fichier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion du CV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Mettre à jour votre CV</h2>
        <a href="../profil/compte.php" style="color: #6495ED;">← Retour à mon compte</a>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <label for="cv_text">Description de votre profil:</label>
            <textarea name="cv_text" id="cv_text" placeholder="Décrivez votre profil..." rows="5" required><?php 
                echo isset($cv_text) ? htmlspecialchars($cv_text) : ''; 
            ?></textarea>
            
            <label for="cv_file">CV (PDF uniquement, max 5MB):</label>
            <input type="file" name="cv_file" id="cv_file" accept=".pdf" required>
            
            <button type="submit">Sauvegarder</button>
        </form>
    </div>
</body>
</html>