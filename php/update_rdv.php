<?php
require "db.php"; // Connexion à la base de données

header('Content-Type: application/json');

// Vérifier si les données sont bien envoyées
if (!isset($_POST['id'], $_POST['description'], $_POST['date_rdv'])) {
    echo json_encode(["error" => "Données incomplètes"]);
    exit();
}

$id = intval($_POST['id']);
$description = trim($_POST['description']);
$date_rdv = trim($_POST['date_rdv']);

// Vérifier que la connexion à la base de données existe
if (!isset($pdo)) {
    echo json_encode(["error" => "Connexion à la base de données non établie"]);
    exit();
}

// Mettre à jour le rendez-vous dans la base
$stmt = $pdo->prepare("UPDATE T_rdv SET description = ?, date_rdv = ? WHERE id = ?");
$success = $stmt->execute([$description, $date_rdv, $id]);

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Erreur lors de la mise à jour"]);
}
?>
