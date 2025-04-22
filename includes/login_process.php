<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $redirect_url = $_POST['redirect_url'] ?? ''; // Get the redirect URL from the form

    if (!empty($email) && !empty($password)) {
        $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["fullname"];
            $_SESSION["role"] = $user["role"];

            // Redirection
            if (!empty($redirect_url)) {
                header("Location: " . $redirect_url);
            } else {
                // Default redirection if no redirect_url was provided
                if ($user["role"] === "entreprise") {
                    header("Location: ../dashboard/entreprise.php");
                } else {
                    header("Location: ../welcome.php");
                }
            }
            exit();
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header("Location: " . ($redirect_url ? $redirect_url : "../register.php"));
            exit();
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: " . ($redirect_url ? $redirect_url : "../register.php"));
        exit();
    }
}
?>