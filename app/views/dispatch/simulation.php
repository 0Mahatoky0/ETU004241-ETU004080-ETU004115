<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck"></i> Simulation de Dispatch</h2>
        <a href="/dispatch/etat" class="btn btn-info">
            <i class="fas fa-chart-line"></i> Voir l'état des besoins
        </a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Dispatch effectué avec succès!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Stock disponible -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-warehouse"></i> Stock Disponible</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stock_disponible)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Aucun stock disponible</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Bien</th>
                                        <th>Quantité</th>
                                        <th>Valeur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stock_disponible as $stock): ?>
                                        <tr>
                                            <td>
                                                <small><?php echo htmlspecialchars($stock['besoin_libelle']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo number_format($stock['stock_disponible']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo number_format($stock['stock_disponible'] * $stock['prix_unitaire'], 0); ?> MGA</small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Besoins non satisfaits -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5><i class="fas fa-exclamation-triangle"></i> Besoins Non Satisfaits</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($besoins_non_satisfaits)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted">Tous les besoins sont satisfaits!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Restant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins_non_satisfaits as $besoin): ?>
                                        <tr>
                                            <td>
                                                <small><?php echo htmlspecialchars($besoin['ville_libelle']); ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <?php echo number_format($besoin['quantite_restante']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/dispatch/simuler/<?php echo $besoin['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Simuler dispatch">
                                                    <i class="fas fa-truck"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-warehouse fa-2x mb-2"></i>
                    <h5>Stock Total</h5>
                    <h3><?php echo array_sum(array_column($stock_disponible, 'stock_disponible')); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h5>Besoins en Attente</h5>
                    <h3><?php echo count($besoins_non_satisfaits); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-boxes fa-2x mb-2"></i>
                    <h5>Quantité Requise</h5>
                    <h3><?php echo number_format(array_sum(array_column($besoins_non_satisfaits, 'quantite_restante'))); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-2"></i>
                    <h5>Taux de Satisfaction</h5>
                    <h3>
                        <?php 
                        $total_besoins = count($besoins_non_satisfaits) + count(array_filter($tous_besoins, function($b) use ($besoins_non_satisfaits) {
                            foreach ($besoins_non_satisfaits as $ns) {
                                if ($ns['id'] == $b['id']) return false;
                            }
                            return true;
                        }));
                        $taux = $total_besoins > 0 ? (($total_besoins - count($besoins_non_satisfaits)) / $total_besoins) * 100 : 100;
                        echo number_format($taux, 1); ?>%
                    </h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="card mt-4">
        <div class="card-header">
            <h5><i class="fas fa-bolt"></i> Actions Rapides</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <a href="/besoins/saisie" class="btn btn-outline-primary w-100">
                        <i class="fas fa-plus"></i> Nouveau Besoin
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/dons/saisie" class="btn btn-outline-success w-100">
                        <i class="fas fa-hand-holding-heart"></i> Nouveau Don
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/dashboard" class="btn btn-outline-info w-100">
                        <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
