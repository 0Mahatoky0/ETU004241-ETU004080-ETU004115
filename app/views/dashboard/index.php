<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête du dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 dashboard-title"><i class="fas fa-tachometer-alt me-2"></i> Tableau de Bord</h2>
            <p class="text-muted mb-0">Aperçu général de la gestion des besoins et dons</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-2"></i><?php echo date('d/m/Y'); ?>
        </div>
    </div>
    
    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Besoins</div>
                            <h3 class="stat-value mb-0"><?php echo $stats['besoins']['total'] ?? 0; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Dons</div>
                            <h3 class="stat-value mb-0"><?php echo $stats['dons']['total'] ?? 0; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <div class="stat-label">Stock Disponible</div>
                            <h3 class="stat-value mb-0"><?php echo number_format($stats['stock'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="stat-label">Taux Satisfaction</div>
                            <h3 class="stat-value mb-0">
                                <?php 
                                $total = $stats['satisfaction']['total'] ?? 0;
                                $satisfaits = $stats['satisfaction']['satisfaits'] ?? 0;
                                $taux = $total > 0 ? ($satisfaits / $total) * 100 : 0;
                                echo number_format($taux, 1); ?>%
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions Rapides</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <a href="/besoins/saisie" class="btn btn-action w-100">
                        <i class="fas fa-plus me-2"></i> Nouveau Besoin
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/dons/saisie" class="btn btn-action w-100">
                        <i class="fas fa-hand-holding-heart me-2"></i> Nouveau Don
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="#" class="btn btn-action w-100">
                        <i class="fas fa-list-ul me-2"></i> Liste Besoins
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="#" class="btn btn-action w-100">
                        <i class="fas fa-list-alt me-2"></i> Liste Dons
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques par région -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i> Statistiques par Région</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Région</th>
                            <th>Nombre Besoins</th>
                            <th>Quantité Totale</th>
                            <th>Quantité Assignée</th>
                            <th>Quantité Restante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats_by_region as $region): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($region['region_libelle']); ?></strong></td>
                                <td><?php echo $region['nombre_besoins']; ?></td>
                                <td><?php echo number_format($region['quantite_totale_besoins']); ?></td>
                                <td><?php echo number_format($region['quantite_allouee']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $region['quantite_restante'] > 0 ? 'warning' : 'success'; ?>">
                                        <?php echo number_format($region['quantite_restante']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dashboard general */
    .dashboard-title {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Stats Cards - Minimaliste */
    .stat-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    .stat-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        border-color: #2c3e50;
    }

    .stat-card .card-body {
        padding: 1.5rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background-color: #34495e;
        color: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .stat-value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.8rem;
    }

    /* Cards générales */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: #2c3e50;
        color: #fff;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.25rem;
        font-weight: 600;
    }

    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
    }

    /* Boutons actions */
    .btn-action {
        background-color: #34495e;
        color: #fff;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-action:hover {
        background-color: #2c3e50;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Table */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #495057;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<?php include('includes/footer.php'); ?>