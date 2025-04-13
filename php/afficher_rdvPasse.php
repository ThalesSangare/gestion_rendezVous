<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

session_start();  // Démarre la session pour pouvoir accéder à $_SESSION

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

header('Content-Type: application/json');

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['id_user'];

// Requête pour récupérer les rendez-vous passés de l'utilisateur connecté, triés par date
$sql = "SELECT * FROM t_rdv WHERE id_user = :id_user AND date_rdv < NOW() ORDER BY date_rdv DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_user' => $user_id]);

if (!$stmt) {
    // Si la requête échoue, afficher l'erreur
    echo json_encode(['error' => 'Erreur dans la requête SQL']);
    exit;
}

// Récupérer tous les rendez-vous de l'utilisateur
$rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Afficher les rendez-vous sous forme JSON
echo json_encode($rdvs, JSON_PRETTY_PRINT);
?>

