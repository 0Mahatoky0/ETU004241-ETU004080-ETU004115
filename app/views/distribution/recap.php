<?php include __DIR__ . '/../../../public/includes/header.php'; ?>

<!-- Top bar -->
<div class="top-bar">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-chart-bar text-info"></i>
                Récapitulatif de Distribution
            </h4>
            <small class="text-muted">Statistiques complètes du système de distribution</small>
        </div>
        <div class="d-flex gap-2">
            <button onclick="actualiserStats()" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
            <a href="/distribution" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<!-- Cartes de statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Totaux</h6>
                        <h2><?= number_format($statistiques['besoins']['nombre_total'] ?? 0) ?></h2>
                        <small>Unités: <?= number_format($statistiques['besoins']['quantite_totale_initiale'] ?? 0) ?></small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Satisfaits</h6>
                        <h2><?= number_format($statistiques['besoins']['quantite_totale_satisfaite'] ?? 0) ?></h2>
                        <small>Unités distribuées</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Restants</h6>
                        <h2><?= number_format($statistiques['besoins']['quantite_totale_restante'] ?? 0) ?></h2>
                        <small>Unités en attente</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Stock Restant</h6>
                        <h2><?= number_format($statistiques['stock']['quantite_totale_restante'] ?? 0) ?></h2>
                        <small>Unités disponibles</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Taux de satisfaction et progression -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-percentage"></i> Taux de Satisfaction
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="progress-circle-container">
                    <div class="circular-progress" style="--progress: <?= $statistiques['taux_satisfaction'] ?? 0 ?>%">
                        <div class="progress-value"><?= $statistiques['taux_satisfaction'] ?? 0 ?>%</div>
                    </div>
                </div>
                <p class="mt-3 text-muted">
                    <strong><?= number_format($statistiques['besoins']['quantite_totale_satisfaite'] ?? 0) ?></strong> unités satisfaites sur 
                    <strong><?= number_format($statistiques['besoins']['quantite_totale_initiale'] ?? 0) ?></strong> unités requises
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie"></i> Répartition des Stocks
                </h5>
            </div>
            <div class="card-body">
                <canvas id="stockChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tableaux détaillés -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt"></i> Détail des Besoins
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-striped table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Besoin</th>
                                <th>Ville</th>
                                <th>Initial</th>
                                <th>Restant</th>
                                <th>Satisfait</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): ?>
                                <?php 
                                $taux = $besoin['quantite_initiale'] > 0 
                                    ? (($besoin['quantite_initiale'] - $besoin['quantite_restante']) / $besoin['quantite_initiale']) * 100 
                                    : 0;
                                $quantite_satisfaite = $besoin['quantite_initiale'] - $besoin['quantite_restante'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($besoin['besoin_libelle']) ?></td>
                                    <td><?= htmlspecialchars($besoin['ville_libelle']) ?></td>
                                    <td><?= number_format($besoin['quantite_initiale']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $besoin['quantite_restante'] > 0 ? 'warning' : 'success' ?>">
                                            <?= number_format($besoin['quantite_restante']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($quantite_satisfaite) ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $taux >= 100 ? 'success' : ($taux >= 50 ? 'info' : 'warning') ?>" 
                                                 style="width: <?= min($taux, 100) ?>%">
                                                <?= round($taux, 1) ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-warehouse"></i> État des Stocks
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-striped table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Article</th>
                                <th>Catégorie</th>
                                <th>Initial</th>
                                <th>Restant</th>
                                <th>Distribué</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stock as $item): ?>
                                <?php 
                                $taux_distribution = $item['quantite_initiale'] > 0 
                                    ? (($item['quantite_initiale'] - $item['quantite']) / $item['quantite_initiale']) * 100 
                                    : 0;
                                $quantite_distribuee = $item['quantite_initiale'] - $item['quantite'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['besoin_libelle']) ?></td>
                                    <td><?= htmlspecialchars($item['categorie_libelle']) ?></td>
                                    <td><?= number_format($item['quantite_initiale']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $item['quantite'] > 0 ? 'primary' : 'danger' ?>">
                                            <?= number_format($item['quantite']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($quantite_distribuee) ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $taux_distribution >= 80 ? 'success' : ($taux_distribution >= 50 ? 'info' : 'warning') ?>" 
                                                 style="width: <?= min($taux_distribution, 100) ?>%">
                                                <?= round($taux_distribution, 1) ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-circle-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.circular-progress {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: conic-gradient(
        #28a745 0deg,
        #28a745 calc(var(--progress) * 3.6deg),
        #e9ecef calc(var(--progress) * 3.6deg),
        #e9ecef 360deg
    );
    display: flex;
    justify-content: center;
    align-items: center;
}

.circular-progress::before {
    content: '';
    position: absolute;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: white;
}

.progress-value {
    position: relative;
    font-size: 1.5rem;
    font-weight: bold;
    color: #28a745;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function actualiserStats() {
    // Afficher un indicateur de chargement
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualisation...';
    btn.disabled = true;
    
    // Recharger la page après un court délai pour montrer l'animation
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Graphique des stocks
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stockChart');
    if (ctx) {
        const stockData = <?= json_encode(array_map(function($item) {
            return [
                'label' => $item['besoin_libelle'],
                'initial' => $item['quantite_initiale'],
                'restant' => $item['quantite']
            ];
        }, $stock ?? [])) ?>;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: stockData.map(item => item.label),
                datasets: [{
                    label: 'Stock Initial',
                    data: stockData.map(item => item.initial),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Stock Restant',
                    data: stockData.map(item => item.restant),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../../../public/includes/footer.php'; ?>
