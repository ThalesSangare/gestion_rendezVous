<?php
header("Content-Type: application/json");
require 'db.php'; // Assure-toi que ce fichier existe et connecte bien à la base de données

// Lire les données envoyées par la requête AJAX
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = intval($data['id']);

    // Préparer la requête SQL pour supprimer le rendez-vous
    $stmt = $pdo->prepare("DELETE FROM T_rdv WHERE id = ?");
    $stmt->execute([$id]);

    // Vérifier si un rendez-vous a bien été supprimé
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Rendez-vous supprimé avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Aucun rendez-vous trouvé avec cet ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID du rendez-vous manquant."]);
}
?>
