<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];
    $redirect_url = $_POST['redirect_url'] ?? ''; // Get the redirect URL from the form

    if (!empty($fullname) && !empty($email) && !empty($password) && !empty($role)) {
        // Vérifie si l'email existe déjà
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: " . ($redirect_url ? $redirect_url : "../register.php"));
            exit();
        } else {
            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base
            $insert = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->execute([$fullname, $email, $hashedPassword, $role]);

            // Set session variables
            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["user_name"] = $fullname;
            $_SESSION["role"] = $role;

            // Redirection
            if (!empty($redirect_url)) {
                header("Location: " . $redirect_url);
            } else {
                // Default redirection if no redirect_url was provided
                if ($role === "entreprise") {
                    header("Location: ../dashboard/entreprise.php");
                } else {
                    header("Location: ../welcome.php");
                }
            }
            exit();
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: " . ($redirect_url ? $redirect_url : "../register.php"));
        exit();
    }
}
?>