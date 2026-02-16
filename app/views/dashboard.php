<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Dons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .city-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .city-card:hover {
            transform: translateY(-5px);
        }
        .need-item {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
        }
        .don-item {
            border-left: 4px solid #28a745;
            background: #f8f9fa;
        }
        .stats-card {
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
        }
        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-speedometer2"></i> Tableau de Bord</h1>
                    <p class="mb-0">Gestion des besoins et des dons par ville</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-light text-dark p-2 me-2">
                        <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?>
                    </span>
                    <a href="/villes" class="btn btn-light">
                        <i class="bi bi-geo-alt-fill"></i> Voir toutes les villes
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Statistiques générales -->
        <section class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt-fill fs-2"></i>
                        <h3 class="card-title mt-2">2</h3>
                        <p class="card-text">Villes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                        <h3 class="card-title mt-2">2</h3>
                        <p class="card-text">Besoins totaux</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-gift-fill fs-2"></i>
                        <h3 class="card-title mt-2">2</h3>
                        <p class="card-text">Dons reçus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                        <h3 class="card-title mt-2">55</h3>
                        <p class="card-text">Unités distribuées</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ville sélectionnée -->
        <section class="row mb-4">
            <div class="col-12">
                <div class="card city-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-geo-alt"></i> 
                            Antananarivo (Analamanga)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary">
                                    <i class="bi bi-list-check"></i> Besoins de la ville
                                </h5>
                                
                                <!-- Besoin 1 -->
                                <div class="card need-item mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="bi bi-basket"></i> Riz
                                                </h6>
                                                <small class="text-muted">Nature</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning text-dark">En attente</span>
                                                <div class="mt-1">
                                                    <small>Quantité requise: <strong>100</strong> unités</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: 50%" aria-valuenow="50" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                50% couvert
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="text-success">
                                    <i class="bi bi-gift"></i> Dons attribués
                                </h5>
                                
                                <!-- Don 1 -->
                                <div class="card don-item mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="bi bi-box-seam"></i> Riz
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-building"></i> ONG A
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">Distribué</span>
                                                <div class="mt-1">
                                                    <small>Quantité: <strong>50</strong> unités</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar2"></i> 
                                                Reçu le: 15/02/2026
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Résumé de la ville -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle"></i> Résumé pour Antananarivo
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Taux de couverture:</strong> 50% des besoins sont actuellement satisfaits
                                    </p>
                                    <p class="mb-0">
                                        <strong>Besoins restants:</strong> 50 unités de Riz encore nécessaires
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Actions rapides -->
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning"></i> Actions rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-primary w-100" onclick="ajouterBesoin()">
                                    <i class="bi bi-plus-circle"></i> Ajouter un besoin
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-success w-100" onclick="enregistrerDon()">
                                    <i class="bi bi-gift"></i> Enregistrer un don
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-warning w-100" onclick="distribuerDons()">
                                    <i class="bi bi-truck"></i> Distribuer des dons
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info w-100" onclick="genererRapport()">
                                    <i class="bi bi-file-earmark-text"></i> Générer un rapport
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-c-circle"></i> 2026 - Système de Gestion des Dons et Besoins
            </p>
        </div>
    </footer>

    <!-- Modal Ajout Besoin -->
    <div class="modal fade" id="ajoutBesoinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> Ajouter un besoin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ajoutBesoinForm">
                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <select class="form-select" id="ville" required>
                                <option value="">Sélectionner une ville</option>
                                <option value="1">Antananarivo</option>
                                <option value="2">Toamasina</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="besoin" class="form-label">Type de besoin</label>
                            <select class="form-select" id="besoin" required>
                                <option value="">Sélectionner un besoin</option>
                                <option value="1">Riz</option>
                                <option value="2">Clou</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité requise</label>
                            <input type="number" class="form-control" id="quantite" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" required>
                                <option value="2">En attente</option>
                                <option value="1">Accepté</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="soumettreBesoin()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Enregistrement Don -->
    <div class="modal fade" id="enregistrementDonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-gift"></i> Enregistrer un don
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="enregistrementDonForm">
                        <div class="mb-3">
                            <label for="donBesoin" class="form-label">Type de don</label>
                            <select class="form-select" id="donBesoin" required>
                                <option value="">Sélectionner un type</option>
                                <option value="1">Riz</option>
                                <option value="2">Clou</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantiteDon" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="quantiteDon" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="source" class="form-label">Source du don</label>
                            <input type="text" class="form-control" id="source" placeholder="ONG, Particulier, Entreprise..." required>
                        </div>
                        <div class="mb-3">
                            <label for="dateDon" class="form-label">Date du don</label>
                            <input type="datetime-local" class="form-control" id="dateDon" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" onclick="soumettreDon()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Distribution -->
    <div class="modal fade" id="distributionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-truck"></i> Distribuer des dons
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="distributionForm">
                        <div class="mb-3">
                            <label for="donDisponible" class="form-label">Don disponible</label>
                            <select class="form-select" id="donDisponible" required>
                                <option value="">Sélectionner un don</option>
                                <option value="1">Riz - 50 unités (ONG A)</option>
                                <option value="2">Clou - 5 unités (Particulier B)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantiteDistribution" class="form-label">Quantité à distribuer</label>
                            <input type="number" class="form-control" id="quantiteDistribution" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="destination" class="form-label">Destination</label>
                            <select class="form-select" id="destination" required>
                                <option value="">Sélectionner une ville</option>
                                <option value="1">Antananarivo</option>
                                <option value="2">Toamasina</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateDistribution" class="form-label">Date de distribution</label>
                            <input type="datetime-local" class="form-control" id="dateDistribution" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-warning" onclick="soumettreDistribution()">Distribuer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des chiffres au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const statsCards = document.querySelectorAll('.stats-card h3');
            statsCards.forEach(card => {
                const finalValue = parseInt(card.textContent);
                let currentValue = 0;
                const increment = finalValue / 50;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    card.textContent = Math.floor(currentValue);
                }, 30);
            });
        });

        // Fonction pour changer de ville (simulation)
        function changeVille(villeId) {
            console.log('Changement vers la ville:', villeId);
            // Ici vous pourriez ajouter une requête AJAX pour charger les données d'une autre ville
        }

        // Fonctions pour les actions rapides
        function ajouterBesoin() {
            const modal = new bootstrap.Modal(document.getElementById('ajoutBesoinModal'));
            modal.show();
        }

        function enregistrerDon() {
            const modal = new bootstrap.Modal(document.getElementById('enregistrementDonModal'));
            modal.show();
        }

        function distribuerDons() {
            const modal = new bootstrap.Modal(document.getElementById('distributionModal'));
            modal.show();
        }

        function genererRapport() {
            // Simulation de génération de rapport
            const alert = document.createElement('div');
            alert.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-file-earmark-text"></i> 
                Génération du rapport en cours...
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.innerHTML = `
                    <i class="bi bi-check-circle"></i> 
                    Rapport généré avec succès!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            }, 2000);
            
            setTimeout(() => {
                alert.remove();
            }, 4000);
        }

        // Fonctions pour soumettre les formulaires
        function soumettreBesoin() {
            const form = document.getElementById('ajoutBesoinForm');
            if (form.checkValidity()) {
                // Simulation d'envoi de données
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i class="bi bi-check-circle"></i> 
                    Besoin ajouté avec succès!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                // Fermer la modale
                bootstrap.Modal.getInstance(document.getElementById('ajoutBesoinModal')).hide();
                
                // Réinitialiser le formulaire
                form.reset();
                
                // Supprimer l'alerte après 3 secondes
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            } else {
                form.reportValidity();
            }
        }

        function soumettreDon() {
            const form = document.getElementById('enregistrementDonForm');
            if (form.checkValidity()) {
                // Simulation d'envoi de données
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i class="bi bi-check-circle"></i> 
                    Don enregistré avec succès!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                // Fermer la modale
                bootstrap.Modal.getInstance(document.getElementById('enregistrementDonModal')).hide();
                
                // Réinitialiser le formulaire
                form.reset();
                
                // Supprimer l'alerte après 3 secondes
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            } else {
                form.reportValidity();
            }
        }

        function soumettreDistribution() {
            const form = document.getElementById('distributionForm');
            if (form.checkValidity()) {
                // Simulation d'envoi de données
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i class="bi bi-check-circle"></i> 
                    Distribution effectuée avec succès!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                // Fermer la modale
                bootstrap.Modal.getInstance(document.getElementById('distributionModal')).hide();
                
                // Réinitialiser le formulaire
                form.reset();
                
                // Supprimer l'alerte après 3 secondes
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            } else {
                form.reportValidity();
            }
        }

        // Initialiser les champs date avec la date/heure actuelle
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const dateTimeLocal = now.toISOString().slice(0, 16);
            
            const dateDonField = document.getElementById('dateDon');
            const dateDistributionField = document.getElementById('dateDistribution');
            
            if (dateDonField) dateDonField.value = dateTimeLocal;
            if (dateDistributionField) dateDistributionField.value = dateTimeLocal;
        });
    </script>
</body>
</html>
