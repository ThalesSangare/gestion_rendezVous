<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Inscription</title>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-80">
        <div class="card p-4 shadow-sm" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4">Inscription</h3>
            <form action="inscription.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Votre nom d'utilisateur" autocomplete="off" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe" autocomplete="off" required>
                </div>
                <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            </form>
            <div class="text-center mt-3">
                <p>Avez-vous déjà un compte ? <a href="connexion_form.php">Se connecter </a></p>
            </div>
        </div>
    </div>

    <footer class="container-fluid footer">
        <div id="">
            <p>Developed by Thales </p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("username").value = "";
            document.getElementById("password").value = "";
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>



