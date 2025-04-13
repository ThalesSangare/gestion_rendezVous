<?php
require 'db.php'; // Inclure la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM t_user WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->fetch()) {
        echo "Nom d'utilisateur déjà pris.";
        exit();
    }

    // Hacher le mot de passe avant de l'insérer
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO t_user (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
        echo( "Utilisateur inscrit avec succès !");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

header("Location: connexion_form.php");
exit();

?>
