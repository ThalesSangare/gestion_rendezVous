const champsDescription = document.getElementById("description");
const champsDate = document.getElementById("date");
const message = document.getElementById("message");

// Charger les rendez-vous depuis la base de données
function displayAppointments() {
    fetch("php/afficher_rdv.php")
        .then((response) => {
            // Vérifier que la réponse est bien un JSON
            if (!response.ok) {
                throw new Error("Erreur réseau");
            }
            return response.json();
        })
        .then((data) => {
            const tableBody = document.querySelector("#appointments-table tbody");
            tableBody.innerHTML = ""; // Vide la table avant mise à jour

            // Vérifier si on a des données
            if (data.length === 0) {
                tableBody.innerHTML =
                    "<tr><td colspan='3'>Aucun rendez-vous enregistré.</td></tr>";
                return;
            }

            // Affichage de tous les RDV
            data.forEach((app) => {
                const rdvDate = new Date(app.date_rdv); // Convertir la date en objet Date
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${app.description}</td>
                    <td>${rdvDate.toLocaleString("fr-FR")}</td>
                    <td>
                        <!-- Liste déroulante pour petits écrans uniquement -->
                        <div class="dropdown d-block d-md-none">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item btn-view" href="#" data-id="${app.id
                    }"><i class="bi bi-eye"></i> Voir</a></li>
                                <li><a class="dropdown-item btn-edit" href="#" data-id="${app.id
                    }"><i class="bi bi-pencil"></i> Modifier</a></li>
                                <li><a class="dropdown-item btn-delete" href="#" data-id="${app.id
                    }"><i class="bi bi-trash"></i> Supprimer</a></li>
                            </ul>
                        </div>
            
                        <!-- Boutons pour grands écrans uniquement -->
                        <div class="d-none d-md-block">
                            <button class="btn-view btn btn-primary" data-id="${app.id
                    }">
                                <i class="bi bi-eye"></i> Voir
                            </button>
                            <button class="btn-edit btn btn-warning" data-id="${app.id
                    }">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button class="btn-delete btn btn-danger" data-id="${app.id
                    }">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            // Événements sur les boutons "Supprimer"
            document.querySelectorAll(".btn-delete").forEach((button) => {
                button.addEventListener("click", function () {
                    const rdvId = button.getAttribute("data-id");
                    if (rdvId) {
                        supprimerRdv(rdvId);
                    } else {
                        console.error("Erreur : ID non trouvé !");
                    }
                });
            });
        })
        .catch((error) =>
            console.error("Erreur lors de la récupération des RDV :", error)
        );
}

// Charger les rendez-vous depuis la base de données
function displayAppointmentsPast() {
    fetch("php/afficher_rdvPasse.php")
        .then((response) => {
            // Vérifier que la réponse est bien un JSON
            if (!response.ok) {
                throw new Error("Erreur réseau");
            }
            return response.json();
        })
        .then((data) => {
            const tableBody = document.querySelector(
                "#appointments-table-rdvPasse tbody"
            );
            tableBody.innerHTML = ""; // Vide la table avant mise à jour

            // Vérifier si on a des données
            if (data.length === 0) {
                tableBody.innerHTML =
                    "<tr><td colspan='3'>Aucun rendez-vous Passé.</td></tr>";
                return;
            }

            // Affichage de tous les RDV passé
            data.forEach((app) => {
                const rdvDate = new Date(app.date_rdv); // Convertir la date en objet Date
                const row = document.createElement("tr");
                row.innerHTML = `
                                <td>${app.description}</td>
                                <td>${rdvDate.toLocaleString("fr-FR")}</td>
                                <td>
                                <div class="d-block d-md-none">
                                    <button style="font-size:10px;" class="btn-delete btn btn-danger" data-id="${app.id}">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                </div>
                                    <div class="d-none d-md-block">
                                        <button class="btn-delete btn btn-danger" data-id="${app.id}">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </td>
                            `;
                tableBody.appendChild(row);
            });
            // Événements sur les boutons "Supprimer"
            document.querySelectorAll(".btn-delete").forEach((button) => {
                button.addEventListener("click", function () {
                    const rdvId = button.getAttribute("data-id");
                    if (rdvId) {
                        supprimerRdv(rdvId);
                    } else {
                        console.error("Erreur : ID non trouvé !");
                    }
                });
            });
        })
        .catch((error) =>
            console.error("Erreur lors de la récupération des RDV :", error));
}

// Charger les RDV
displayAppointments();
displayAppointmentsPast();

// Ajouter un rendez-vous avec AJAX

document
    .getElementById("appointment-form")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche le rechargement de la page lors de la soumission du formulaire

        // Réinitialise le message de retour
        const message = document.getElementById("message"); // Div ou élément où tu veux afficher les messages
        message.textContent = ""; // Vide le message précédent

        // Récupère les champs du formulaire
        const champsDescription = document.getElementById("description");
        const champsDate = document.getElementById("date");

        // Vérifie si les champs sont vides
        if (!champsDescription.value || !champsDate.value) {
            message.textContent = "Veuillez remplir tous les champs.";
            message.style.color = "red";
            return; // Empêche l'envoi du formulaire si les champs sont vides
        }

        // Création de l'objet FormData pour envoyer les données
        const formData = new FormData();
        formData.append("description", champsDescription.value);
        formData.append("date", champsDate.value);

        // Effectuer la requête AJAX pour ajouter un rendez-vous
        fetch("php/ajouter_rdv.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.text()) // On attend une réponse JSON du serveur
            .then((data) => {
                console.log(data); // Affiche la réponse brute pour inspection
                try {
                    const jsonData = JSON.parse(data); // Essaie de parser la réponse
                    if (jsonData.success) {
                        alert(jsonData.success); // Affiche un message de succès
                        displayAppointments(); // Mise à jour de l'affichage des rendez-vous
                        displayAppointmentsPast();
                        document.getElementById("appointment-form").reset(); // Réinitialise le formulaire
                    } else if (jsonData.error) {
                        message.textContent = jsonData.error; // Affiche l'erreur si elle existe
                        message.style.color = "red"; // Colorier l'erreur en rouge
                    }
                } catch (e) {
                    console.error("Erreur lors du parsing de la réponse JSON", e);
                    message.textContent = "Erreur de format de réponse du serveur.";
                    message.style.color = "red";
                }
            })
            .catch((error) => {
                console.error("Erreur lors de l'ajout :", error); // Affiche l'erreur dans la console pour débogage
                message.textContent = "Erreur lors de l'ajout du rendez-vous."; // Affiche une erreur générique
                message.style.color = "red"; // Colorie le message en rouge
            });
    });

// Supprimer un rendez-vous via AJAX sur click
function supprimerRdv(id) {
  if (confirm("Êtes-vous sûr de vouloir supprimer ce rendez-vous ?")) {
    fetch("php/supprimer_rdv.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: id }),
    })
      .then((response) => response.json())
      .then((data) => {
        alert("Rendez-vous supprimé avec succès!");
        displayAppointments(); // Met à jour la table
        displayAppointmentsPast();
      })
      .catch((error) =>
        console.error("Erreur lors de la suppression du RDV :", error)
      );
  }
}

//  l'event sur les boutons "Voir"
document.addEventListener("click", function (event) {
    const btnView = event.target.closest(".btn-view");
    if (btnView) {
        event.preventDefault();
        
        // Récupérer l'ID du rendez-vous
        const rdvId = btnView.getAttribute("data-id");
        // console.log(rdvId);

        // Faire une requête AJAX pour récupérer les détails
        fetch(`php/get_rdv.php?id=${rdvId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remplir les données de la modale
                    document.getElementById("modalDescription").textContent = data.description;
                    document.getElementById("modalDate").textContent = new Date(data.date_rdv).toLocaleString("fr-FR");

                    // Afficher la modale
                    let myModal = new bootstrap.Modal(document.getElementById('rdvModal'));
                    myModal.show();
                } else {
                    alert("Erreur : " + data.error);
                }
            })
            .catch(error => console.error("Erreur lors de la récupération des détails :", error));
    }
});


//  l'event sur les boutons "modifier"

document.addEventListener("click", function (event) {
    const btnEdit = event.target.closest(".btn-edit");
    if (btnEdit) {
        event.preventDefault();
        
        // Récupérer l'ID du rendez-vous
        const rdvId = btnEdit.getAttribute("data-id");

        // Faire une requête AJAX pour récupérer les détails du rendez-vous
        fetch(`php/get_rdv.php?id=${rdvId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remplir les champs du formulaire de la modale
                    document.getElementById("editRdvId").value = rdvId;
                    document.getElementById("editDescription").value = data.description;
                    document.getElementById("editDate").value = data.date_rdv.replace(" ", "T"); // Format pour input datetime-local

                    // Afficher la modale
                    let myModal = new bootstrap.Modal(document.getElementById("editRdvModal"));
                    myModal.show();
                } else {
                    alert("Erreur : " + data.error);
                }
            })
            .catch(error => console.error("Erreur lors de la récupération des données :", error));
    }

        // Gestion de la soumission du formulaire de modification
        document.getElementById("editRdvForm").addEventListener("submit", function (e) {
            e.preventDefault();
            
            const rdvId = document.getElementById("editRdvId").value;
            const description = document.getElementById("editDescription").value;
            const date = document.getElementById("editDate").value;
    
            fetch("php/update_rdv.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${rdvId}&description=${encodeURIComponent(description)}&date_rdv=${encodeURIComponent(date)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Rendez-vous modifié avec succès !");
                    location.reload(); // Recharge la page pour voir les modifications
                } else {
                    alert("Erreur : " + data.error);
                }
            })
            .catch(error => console.error("Erreur lors de la mise à jour :", error));
        });
});



