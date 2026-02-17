<?php include __DIR__ . '/../../../public/includes/header.php'; ?>

<!-- Top bar avec actions -->
<div class="top-bar">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-truck text-primary"></i>
                Distribution des Ressources
            </h4>
            <small class="text-muted">Gestion de la distribution des aides aux sinistrés</small>
        </div>
        <div class="d-flex gap-2">
            <a href="/distribution/recap" class="btn btn-outline-info">
                <i class="fas fa-chart-bar"></i> Récapitulatif
            </a>
        </div>
    </div>
</div>

<!-- Messages -->
<?php if (isset($_GET['simulation'])): ?>
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-info-circle"></i> Simulation de distribution générée avec succès!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['valide'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> Distribution validée et enregistrée avec succès!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['reinitialise'])): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-undo"></i> Système réinitialisé à l'état initial!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> Erreur: <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Totaux</h6>
                        <h3><?= number_format($statistiques['besoins']['quantite_totale_initiale'] ?? 0) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Satisfaits</h6>
                        <h3><?= number_format($statistiques['besoins']['quantite_totale_satisfaite'] ?? 0) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Besoins Restants</h6>
                        <h3><?= number_format($statistiques['besoins']['quantite_totale_restante'] ?? 0) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Stock Restant</h6>
                        <h3><?= number_format($statistiques['stock']['quantite_totale_restante'] ?? 0) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions principales -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs"></i> Actions de Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <button onclick="simulerDistribution()" class="btn btn-primary btn-lg w-100" 
                                <?= $distribution_en_cours ? 'disabled' : '' ?>>
                            <i class="fas fa-play"></i><br>
                            <strong>DISTRIBUER</strong><br>
                            <small>Simulation de la distribution</small>
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button onclick="validerDistribution()" class="btn btn-success btn-lg w-100" 
                                <?= !$distribution_en_cours ? 'disabled' : '' ?>>
                            <i class="fas fa-check"></i><br>
                            <strong>VALIDER</strong><br>
                            <small>Confirmer la distribution</small>
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button onclick="reinitialiserDistribution()" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-undo"></i><br>
                            <strong>RÉINITIALISER</strong><br>
                            <small>Retour à l'état initial</small>
                        </button>
                    </div>
                </div>
                
                <?php if ($distribution_en_cours): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Simulation en cours:</strong> 
                        <?= count($distribution_en_cours) ?> allocations prêtes à être validées
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Résultat de la simulation -->
<?php if ($distribution_en_cours): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check"></i> Résultat de la Simulation
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Besoin</th>
                                <th>Ville</th>
                                <th>Quantité Requise</th>
                                <th>Quantité Allouée</th>
                                <th>Reste Après</th>
                                <th>Stock Restant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($distribution_en_cours as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['besoin_libelle']) ?></td>
                                <td><?= htmlspecialchars($item['ville_libelle']) ?></td>
                                <td>
                                    <span class="badge bg-warning">
                                        <?= number_format($item['quantite_requise']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= number_format($item['quantite_allouee']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= number_format($item['quantite_restante_apres']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= number_format($item['stock_restant_apres']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="2">Totaux</th>
                                <th><?= number_format(array_sum(array_column($distribution_en_cours, 'quantite_requise'))) ?></th>
                                <th><?= number_format(array_sum(array_column($distribution_en_cours, 'quantite_allouee'))) ?></th>
                                <th><?= number_format(array_sum(array_column($distribution_en_cours, 'quantite_restante_apres'))) ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Besoins en attente -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Besoins en Attente (<?= count($besoins) ?>)
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($besoins)): ?>
                    <p class="text-muted">Aucun besoin en attente</p>
                <?php else: ?>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Besoin</th>
                                    <th>Ville</th>
                                    <th>Restant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($besoins as $besoin): ?>
                                <tr>
                                    <td><?= htmlspecialchars($besoin['besoin_libelle']) ?></td>
                                    <td><?= htmlspecialchars($besoin['ville_libelle']) ?></td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <?= number_format($besoin['quantite_restante']) ?>
                                        </span>
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
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-boxes"></i> Stock Disponible (<?= count($stock) ?>)
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($stock)): ?>
                    <p class="text-muted">Aucun stock disponible</p>
                <?php else: ?>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Catégorie</th>
                                    <th>Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stock as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['besoin_libelle']) ?></td>
                                    <td><?= htmlspecialchars($item['categorie_libelle']) ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= number_format($item['quantite']) ?>
                                        </span>
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

<script>
function simulerDistribution() {
    if (confirm('Voulez-vous lancer la simulation de distribution?')) {
        window.location.href = '/distribution/simuler';
    }
}

function validerDistribution() {
    if (confirm('ATTENTION: Cette action va valider et enregistrer la distribution. Voulez-vous continuer?')) {
        window.location.href = '/distribution/valider';
    }
}

function reinitialiserDistribution() {
    if (confirm('ATTENTION: Cette action va réinitialiser tout le système à l\'état initial. Voulez-vous continuer?')) {
        window.location.href = '/distribution/reinitialiser';
    }
}
</script>

<?php include __DIR__ . '/../../../public/includes/footer.php'; ?>
