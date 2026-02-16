<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des Besoins et Dons</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .card-header {
            font-weight: 600;
        }
        .badge {
            font-size: 0.8em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .alert {
            border: none;
            border-radius: 0.5rem;
        }
        .nav-tabs .nav-link {
            border: 1px solid #dee2e6;
            border-bottom: none;
        }
        .nav-tabs .nav-link.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-home"></i> BNGRC
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-list"></i> Besoins
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/besoins/saisie">
                                <i class="fas fa-plus"></i> Saisir un besoin
                            </a></li>
                            <li><a class="dropdown-item" href="/besoins/liste">
                                <i class="fas fa-list"></i> Liste des besoins
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-hand-holding-heart"></i> Dons
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dons/saisie">
                                <i class="fas fa-plus"></i> Saisir un don
                            </a></li>
                            <li><a class="dropdown-item" href="/dons/achat">
                                <i class="fas fa-shopping-cart"></i> Acheter avec dons
                            </a></li>
                            <li><a class="dropdown-item" href="/dons/liste">
                                <i class="fas fa-list"></i> Liste des dons
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-truck"></i> Dispatch
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dispatch/simulation">
                                <i class="fas fa-play"></i> Simulation
                            </a></li>
                            <li><a class="dropdown-item" href="/dispatch/etat">
                                <i class="fas fa-chart-line"></i> État des besoins
                            </a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="navbar-text">
                            <i class="fas fa-user"></i> Admin
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages flash -->
    <?php if (isset($_GET['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Opération réussie!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Contenu principal -->
    <main>
