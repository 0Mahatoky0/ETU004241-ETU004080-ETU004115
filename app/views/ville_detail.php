<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ville['libelle']) ?> - Détails de la ville</title>
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
        }
        .need-item {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            transition: transform 0.2s;
        }
        .need-item:hover {
            transform: translateX(5px);
        }
        .don-item {
            border-left: 4px solid #28a745;
            background: #f8f9fa;
            transition: transform 0.2s;
        }
        .don-item:hover {
            transform: translateX(5px);
        }
        .progress-custom {
            height: 10px;
            border-radius: 5px;
        }
        .stats-mini {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard" class="text-white">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="/villes" class="text-white">Villes</a></li>
                            <li class="breadcrumb-item active text-white"><?= htmlspecialchars($ville['libelle']) ?></li>
                        </ol>
                    </nav>
                    <h1><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($ville['libelle']) ?></h1>
                    <p class="mb-0">
                        <i class="bi bi-geo"></i> Région: <?= htmlspecialchars($ville['region_libelle']) ?>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="/villes" class="btn btn-light me-2">
                        <i class="bi bi-arrow-left"></i> Retour aux villes
                    </a>
                    <button class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Statistiques rapides -->
        <section class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-mini text-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                    <div class="fw-bold fs-4"><?= count($besoins) ?></div>
                    <small>Besoins</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini text-center">
                    <i class="bi bi-gift-fill fs-3"></i>
                    <div class="fw-bold fs-4"><?= count($dons) ?></div>
                    <small>Dons reçus</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini text-center">
                    <i class="bi bi-percent fs-3"></i>
                    <div class="fw-bold fs-4">
                        <?= $besoins ? round(array_sum(array_column($besoins, 'taux_couverture')) / count($besoins), 1) : 0 ?>%
                    </div>
                    <small>Couverture moyenne</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini text-center">
                    <i class="bi bi-currency-euro fs-3"></i>
                    <div class="fw-bold fs-4">
                        <?= number_format(array_sum(array_map(function($b) { 
                            return $b['quantite_requise'] * $b['prix_unitaire']; 
                        }, $besoins)), 0, ',', ' ') ?>
                    </div>
                    <small>Valeur totale</small>
                </div>
            </div>
        </section>

        <!-- Besoins et Dons -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card city-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check"></i> Besoins de la ville
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($besoins)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle text-success fs-1"></i>
                                <h6 class="mt-2">Aucun besoin enregistré</h6>
                                <p class="text-muted">Tous les besoins sont satisfaits</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($besoins as $besoin): ?>
                                <div class="card need-item mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="bi bi-basket"></i> <?= htmlspecialchars($besoin['libelle']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($besoin['categorie']) ?>
                                                </small>
                                                <?php if ($besoin['prix_unitaire'] > 0): ?>
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-currency-euro"></i> 
                                                        <?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> / unité
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge <?= $besoin['taux_couverture'] >= 100 ? 'bg-success' : ($besoin['taux_couverture'] >= 50 ? 'bg-warning' : 'bg-danger') ?>">
                                                    <?= $besoin['status'] ?>
                                                </span>
                                                <div class="mt-1">
                                                    <small>Requis: <strong><?= $besoin['quantite_requise'] ?></strong></small>
                                                </div>
                                                <div>
                                                    <small>Reçu: <strong><?= $besoin['quantite_recue'] ?></strong></small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Taux de couverture</small>
                                                <small class="fw-bold"><?= $besoin['taux_couverture'] ?>%</small>
                                            </div>
                                            <div class="progress progress-custom">
                                                <div class="progress-bar <?= $besoin['taux_couverture'] >= 100 ? 'bg-success' : ($besoin['taux_couverture'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                     style="width: <?= min($besoin['taux_couverture'], 100) ?>%" 
                                                     role="progressbar"></div>
                                            </div>
                                        </div>
                                        
                                        <?php if ($besoin['taux_couverture'] < 100): ?>
                                            <div class="mt-2">
                                                <small class="text-danger">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    Reste: <?= max(0, $besoin['quantite_requise'] - $besoin['quantite_recue']) ?> unités
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Ajouter un besoin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card city-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gift"></i> Dons reçus
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($dons)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                                <h6 class="mt-2">Aucun don reçu</h6>
                                <p class="text-muted">Cette ville n'a pas encore reçu de dons</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($dons as $don): ?>
                                <div class="card don-item mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="bi bi-box-seam"></i> <?= htmlspecialchars($don['besoin_libelle']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-building"></i> <?= htmlspecialchars($don['source']) ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">
                                                    <?= $don['quantite_distribuee'] > 0 ? 'Distribué' : 'Reçu' ?>
                                                </span>
                                                <div class="mt-1">
                                                    <small>Quantité: <strong><?= $don['quantite'] ?></strong></small>
                                                </div>
                                                <?php if ($don['quantite_distribuee'] > 0): ?>
                                                    <div>
                                                        <small class="text-success">
                                                            Distribué: <?= $don['quantite_distribuee'] ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar2"></i> 
                                                Reçu le: <?= date('d/m/Y H:i', strtotime($don['date'])) ?>
                                            </small>
                                        </div>
                                        
                                        <?php if ($don['quantite_distribuee'] == 0): ?>
                                            <div class="mt-2">
                                                <button class="btn btn-outline-warning btn-sm">
                                                    <i class="bi bi-truck"></i> Distribuer
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Enregistrer un don
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
