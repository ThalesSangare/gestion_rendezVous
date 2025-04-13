<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Vérifier que la connexion à la base de données existe
if (!isset($pdo)) {
    echo json_encode(["error" => "Connexion à la base de données non établie"]);
    exit();
}
    
    $stmt = $pdo->prepare("SELECT description, date_rdv FROM t_rdv WHERE id = ?");
    $stmt->execute([$id]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rdv) {
        echo json_encode(["success" => true, "description" => $rdv['description'], "date_rdv" => $rdv['date_rdv']]);
    } else {
        echo json_encode(["success" => false, "error" => "Rendez-vous introuvable"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "ID manquant"]);
}
?>
