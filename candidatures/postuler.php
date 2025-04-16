<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "candidat") {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["offre_id"])) {
    $offre_id = intval($_POST["offre_id"]);
    $candidat_id = $_SESSION["user_id"];

    // Vérifier si le candidat a déjà postulé
    $check = $pdo->prepare("SELECT id FROM candidatures WHERE candidat_id = ? AND offre_id = ?");
    $check->execute([$candidat_id, $offre_id]);

    if ($check->rowCount() > 0) {
        echo "Vous avez déjà postulé à cette offre.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO candidatures (candidat_id, offre_id) VALUES (?, ?)");
        $stmt->execute([$candidat_id, $offre_id]);
        echo "Candidature envoyée avec succès.";
    }
} else {
    echo "Données invalides.";
}
?>
