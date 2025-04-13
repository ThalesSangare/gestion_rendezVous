<?php
session_start();
include('db.php'); // Fichier pour la connexion à la DB

// Vérifie si les champs sont remplis
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour vérifier l'utilisateur dans la DB
    $query = "SELECT * FROM t_user WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user) {

        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            // Si c'est bon, on crée une session
            $_SESSION['id_user'] = $user['id']; // ou autre identifiant unique
            $_SESSION['username'] = $user['username'];
            header("Location: ../dashboard.php"); // Redirection vers la page d'accueil
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Utilisateur non trouvé.";
    }
}

if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>
