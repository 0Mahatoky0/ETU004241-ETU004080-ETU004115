<?php
// Fichier de test simple pour le dashboard
?>
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
                    <span class="badge bg-light text-dark p-2">
                        <i class="bi bi-calendar3"></i> 16/02/2026
                    </span>
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
                                <button class="btn btn-outline-primary w-100">
                                    <i class="bi bi-plus-circle"></i> Ajouter un besoin
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-success w-100">
                                    <i class="bi bi-gift"></i> Enregistrer un don
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-warning w-100">
                                    <i class="bi bi-truck"></i> Distribuer des dons
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info w-100">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
