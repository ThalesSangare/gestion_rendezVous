<?php
session_start();

// Vérification de la session pour l'utilisateur
if (!isset($_SESSION['id_user'])) {
    header("Location: php/connexion_form.php");  // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$username = $_SESSION['username'];  // Récupère le nom d'utilisateur depuis la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <title>Gestion de rendez-vous</title>
</head>
<body>

    <header class="d-flex justify-content-between align-items-center p-3">
        <nav>
            <ul class="list-unstyled d-flex m-0">
                <?php if (isset($_SESSION['id_user'])): ?>
                    <!-- Si l'utilisateur est connecté, afficher "Déconnexion" -->
                    <li><a href="php/deconnexion.php" class="nav-link-custom deconnexion" onclick="confirmDeconnexion(event)">Déconnexion</a></li>
                <?php else: ?>
                    <!-- Si l'utilisateur n'est pas connecté, afficher "Connexion" -->
                    <li><a href="php/connexion_form.php" class="nav-link-custom connexion">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div>
        <h1 id="UsernameAccueil" class="m-0" style="font-size: 14px; margin-top: -15px;">Bienvenue, <?php echo htmlspecialchars($username); ?></h1>
    </div>
    </header>
    

    <div class="container">
    
        <h1>Gestion de Rendez-vous</h1>

        <!-- Formulaire de rendez-vous -->
        <form id="appointment-form" method="POST" action="php/ajouter_rdv.php">
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="Entrer la description du rendez-vous">
                <p id="message" style="color: red;"></p>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="datetime-local" id="date" name="date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-Ajouter">Ajouter</button>
        </form>


        <!-- Section d'affichage des rendez-vous -->
        <h2 class="text-center" id="rdvAVenir">Rendez-vous à venir</h2>
        <table class="table table-bordered mt-4" id="appointments-table">
            <thead>
                <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Section d'affichage des rendez-vous Passés -->
        <h2 class="text-center" id="rdvPasse">Rendez-vous passés</h2>
        <table class="table table-bordered mt-4" id="appointments-table-rdvPasse">
            <thead>
                <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>



    </div>
    <footer class="container-fluid footer">
        <div>
            <p>Developed by Thales </p>
        </div>
    </footer>


    <!-- FENETRE MODALE POUR VOIR LES DETAILS -->
    <div class="modal fade" id="rdvModal" tabindex="-1" aria-labelledby="rdvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rdvModalLabel">Détails du Rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Description :</strong> <span id="modalDescription"></span></p>
                    <p><strong>Date :</strong> <span id="modalDate"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier RDV -->
<div class="modal fade" id="editRdvModal" tabindex="-1" aria-labelledby="editRdvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRdvModalLabel">Modifier le Rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRdvForm">
                    <input type="hidden" id="editRdvId">
                    
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editDescription" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="editDate" required>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- BOITE DE CONFIRMATION LORS DE LA DECONNEXION  -->
    <script>
        function confirmDeconnexion(event) {
            event.preventDefault(); // Empêche la redirection immédiate

            const confirmation = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
            
            if (confirmation) {
                window.location.href = event.target.href; // Redirige si l'utilisateur confirme
            }
        }
    </script>


    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
