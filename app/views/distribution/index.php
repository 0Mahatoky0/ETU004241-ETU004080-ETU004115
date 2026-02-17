<?php
$distribution_en_cours = isset($_SESSION['distribution']) ? $_SESSION['distribution'] : null;
$distribution_proportionnelle_en_cours = isset($_SESSION['distribution_proportionnelle']) ? $_SESSION['distribution_proportionnelle'] : null;
$distribution_type = $_SESSION['distribution_type'] ?? 'classique';
?>

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
        <i class="fas fa-info-circle"></i> Simulation de distribution classique générée avec succès!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['simulation_proportionnelle'])): ?>
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-info-circle"></i> Simulation de distribution proportionnelle générée avec succès!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['valide'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> Distribution classique validée et enregistrée avec succès!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['valide_proportionnelle'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> Distribution proportionnelle validée et enregistrée avec succès!
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
                    <i class="fas fa-cogs"></i> Sélection du Type de Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distribution_type" id="type_classique" 
                                   value="classique" <?= $distribution_type == 'classique' ? 'checked' : '' ?> 
                                   onchange="changerTypeDistribution('classique')">
                            <label class="form-check-label" for="type_classique">
                                <strong>DISTRIBUTION CLASSIQUE</strong><br>
                                <small class="text-muted">Tri par date + allocation FIFO</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distribution_type" id="type_proportionnelle" 
                                   value="proportionnelle" <?= $distribution_type == 'proportionnelle' ? 'checked' : '' ?> 
                                   onchange="changerTypeDistribution('proportionnelle')">
                            <label class="form-check-label" for="type_proportionnelle">
                                <strong>DISTRIBUTION PROPORTIONNELLE</strong><br>
                                <small class="text-muted">Répartition équitable par pourcentage</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="distribution_type" id="type_prioritaire" 
                                   value="prioritaire" <?= $distribution_type == 'prioritaire' ? 'checked' : '' ?> 
                                   onchange="changerTypeDistribution('prioritaire')">
                            <label class="form-check-label" for="type_prioritaire">
                                <strong>DISTRIBUTION PRIORITAIRE</strong><br>
                                <small class="text-muted">Besoin les plus petits d'abord</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <button onclick="simulerDistribution()" class="btn btn-primary btn-lg w-100" 
                                id="btn_distribuer">
                            <i class="fas fa-play"></i><br>
                            <strong>DISTRIBUER</strong><br>
                            <small id="btn_distribuer_text">Simulation</small>
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button onclick="validerDistribution()" class="btn btn-success btn-lg w-100" 
                                id="btn_valider" disabled>
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
                
                <?php if ($distribution_en_cours && $distribution_type == 'classique'): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Simulation classique en cours:</strong> 
                        <?= count($distribution_en_cours) ?> allocations prêtes à être validées
                    </div>
                <?php endif; ?>
                
                <?php if ($distribution_proportionnelle_en_cours && $distribution_type == 'proportionnelle'): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Simulation proportionnelle en cours:</strong> 
                        <?= count($distribution_proportionnelle_en_cours['distribution']) ?> allocations prêtes à être validées
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Résultat de la simulation classique -->
<?php if ($distribution_en_cours && $distribution_type == 'classique'): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check"></i> Résultat de la Simulation Classique
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

<!-- Résultat de la simulation proportionnelle -->
<?php if ($distribution_proportionnelle_en_cours && $distribution_type == 'proportionnelle'): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-percentage"></i> Résultat de la Simulation Proportionnelle
                </h5>
            </div>
            <div class="card-body">
                <!-- Résumé des calculs -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Total Besoins</h6>
                                <h4 class="text-primary"><?= number_format($distribution_proportionnelle_en_cours['total_besoins']) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Stock Total</h6>
                                <h4 class="text-success"><?= number_format($distribution_proportionnelle_en_cours['stock_total']) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Stock Distribué</h6>
                                <h4 class="text-warning"><?= number_format($distribution_proportionnelle_en_cours['stock_distribue']) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Stock Restant</h6>
                                <h4 class="text-info"><?= number_format($distribution_proportionnelle_en_cours['stock_restant']) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Besoin</th>
                                <th>Ville</th>
                                <th>Quantité Requise</th>
                                <th>Pourcentage</th>
                                <th>Part Calculée</th>
                                <th>Quantité Allouée</th>
                                <th>Reste Après</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($distribution_proportionnelle_en_cours['distribution'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['besoin_libelle']) ?></td>
                                <td><?= htmlspecialchars($item['ville_libelle']) ?></td>
                                <td>
                                    <span class="badge bg-warning">
                                        <?= number_format($item['quantite_requise']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= $item['pourcentage'] ?>%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= number_format($item['part_calculee'], 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= number_format($item['quantite_allouee']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $item['quantite_restante_apres'] > 0 ? 'bg-warning' : 'bg-success' ?>">
                                        <?= number_format($item['quantite_restante_apres']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th colspan="2">Totaux</th>
                                <th><?= number_format(array_sum(array_column($distribution_proportionnelle_en_cours['distribution'], 'quantite_requise'))) ?></th>
                                <th>100%</th>
                                <th><?= number_format(array_sum(array_column($distribution_proportionnelle_en_cours['distribution'], 'part_calculee')), 2) ?></th>
                                <th><?= number_format(array_sum(array_column($distribution_proportionnelle_en_cours['distribution'], 'quantite_allouee'))) ?></th>
                                <th><?= number_format(array_sum(array_column($distribution_proportionnelle_en_cours['distribution'], 'quantite_restante_apres'))) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>Résumé proportionnel:</strong> 
                    <?= $distribution_proportionnelle_en_cours['besoins_satisfaits'] ?> besoins complètement satisfaits sur 
                    <?= count($distribution_proportionnelle_en_cours['distribution']) ?> au total.
                    Les restes (<?= $distribution_proportionnelle_en_cours['stock_restant'] ?>) ne sont PAS redistribués selon la règle.
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
function changerTypeDistribution(type) {
    // Mettre à jour le texte du bouton
    var btnText = document.getElementById('btn_distribuer_text');
    if (type === 'proportionnelle') {
        btnText.textContent = 'Simulation proportionnelle';
    } else if (type === 'prioritaire') {
        btnText.textContent = 'Simulation prioritaire';
    } else {
        btnText.textContent = 'Simulation classique';
    }
    
    // Réactiver le bouton distribuer
    document.getElementById('btn_distribuer').disabled = false;
    
    // Vérifier s'il y a une simulation en cours pour ce type
    var hasSimulation = false;
    <?php if ($distribution_en_cours && $distribution_type == 'classique'): ?>
        if (type === 'classique') hasSimulation = true;
    <?php endif; ?>
    <?php if ($distribution_proportionnelle_en_cours && $distribution_type == 'proportionnelle'): ?>
        if (type === 'proportionnelle') hasSimulation = true;
    <?php endif; ?>
    
    // Activer/désactiver le bouton valider selon la simulation
    document.getElementById('btn_valider').disabled = !hasSimulation;
}

function simulerDistribution() {
    var type = document.querySelector('input[name="distribution_type"]:checked').value;
    
    if (type === 'proportionnelle') {
        if (confirm('Voulez-vous lancer la simulation de distribution PROPORTIONNELLE?')) {
            window.location.href = '/distribution/simuler-proportionnelle';
        }
    } else if (type === 'prioritaire') {
        if (confirm('Voulez-vous lancer la simulation de distribution PRIORITAIRE?')) {
            alert('Fonctionnalité prioritaire en cours de développement...');
            // window.location.href = '/distribution/simuler-prioritaire';
        }
    } else {
        if (confirm('Voulez-vous lancer la simulation de distribution CLASSIQUE?')) {
            window.location.href = '/distribution/simuler';
        }
    }
}

function validerDistribution() {
    var type = document.querySelector('input[name="distribution_type"]:checked').value;
    
    if (type === 'proportionnelle') {
        if (confirm('ATTENTION: Cette action va valider et enregistrer la distribution PROPORTIONNELLE. Voulez-vous continuer?')) {
            window.location.href = '/distribution/valider-proportionnelle';
        }
    } else if (type === 'prioritaire') {
        if (confirm('Fonctionnalité prioritaire en cours de développement...')) {
            alert('Fonctionnalité prioritaire en cours de développement...');
            // window.location.href = '/distribution/valider-prioritaire';
        }
    } else {
        if (confirm('ATTENTION: Cette action va valider et enregistrer la distribution CLASSIQUE. Voulez-vous continuer?')) {
            window.location.href = '/distribution/valider';
        }
    }
}

function reinitialiserDistribution() {
    if (confirm('ATTENTION: Cette action va réinitialiser tout le système à l\'état initial. Voulez-vous continuer?')) {
        window.location.href = '/distribution/reinitialiser';
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    var type = document.querySelector('input[name="distribution_type"]:checked')?.value || 'classique';
    changerTypeDistribution(type);
    
    // Vérifier s'il y a une simulation en cours et activer le bouton valider
    var hasSimulation = false;
    <?php if ($distribution_en_cours && $distribution_type == 'classique'): ?>
        hasSimulation = true;
    <?php endif; ?>
    <?php if ($distribution_proportionnelle_en_cours && $distribution_type == 'proportionnelle'): ?>
        hasSimulation = true;
    <?php endif; ?>
    
    if (hasSimulation) {
        document.getElementById('btn_valider').disabled = false;
    }
});
</script>

<?php include __DIR__ . '/../../../public/includes/footer.php'; ?>
