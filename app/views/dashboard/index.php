<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2><i class="fas fa-tachometer-alt"></i> Tableau de Bord BNGRC</h2>
    
    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Besoins</h5>
                    <h3><?php echo $stats['besoins']['total'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Dons</h5>
                    <h3><?php echo $stats['dons']['total'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Stock Disponible</h5>
                    <h3><?php echo number_format($stats['stock'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Taux Satisfaction</h5>
                    <h3>
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
    
    <!-- Actions rapides -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Actions Rapides</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <a href="/besoins/saisie" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Nouveau Besoin
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/dons/saisie" class="btn btn-success w-100">
                        <i class="fas fa-hand-holding-heart"></i> Nouveau Don
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/dispatch/simulation" class="btn btn-warning w-100">
                        <i class="fas fa-truck"></i> Dispatch
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/dispatch/etat" class="btn btn-info w-100">
                        <i class="fas fa-chart-line"></i> État Besoins
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques par région -->
    <div class="card">
        <div class="card-header">
            <h5>Statistiques par Région</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
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
                                <td><?php echo htmlspecialchars($region['region_libelle']); ?></td>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
