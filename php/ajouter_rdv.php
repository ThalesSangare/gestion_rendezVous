<?php
session_start();
require 'db.php';

header('Content-Type: application/json'); // Assure que la réponse est bien du JSON

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    echo json_encode(["error" => "Vous devez être connecté pour enregistrer un rendez-vous."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = htmlspecialchars($_POST["description"]);
    $date_rdv = $_POST["date"];
    $user_id = $_SESSION['id_user'];  // Récupère l'ID de l'utilisateur connecté

    if (strlen($description) > 10) {
        echo json_encode(["error" => "La description ne doit pas dépasser 10 caractères."]);
        exit();
    }

    $sql = "INSERT INTO t_rdv (id_user, description, date_rdv) VALUES (:id_user, :description, :date_rdv)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([":id_user" => $user_id, ":description" => $description, ":date_rdv" => $date_rdv])) {
        echo json_encode(["success" => "Rendez-vous enregistré avec succès !"]);
    } else {
        echo json_encode(["error" => "Erreur lors de l'ajout du rendez-vous."]);
    }
}
?>
