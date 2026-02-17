<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bar-chart-line"></i> Récapitulatif des Besoins</h2>
                <div>
                    <button id="btnActualiser" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Actualiser
                    </button>
                    <a href="/achats/liste" class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul"></i> Voir les achats
                    </a>
                </div>
            </div>

            <!-- Messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Indicateur de chargement -->
            <div id="loadingIndicator" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2">Actualisation des données...</p>
            </div>

            <!-- Cartes principales -->
            <div class="row" id="cartesPrincipales">
                <div class="col-md-4 mb-4">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Besoins Totaux</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-primary" id="montantTotal"><?= number_format($recapitulatif['montant_total_besoins'], 0, ',', ' ') ?></h2>
                            <p class="text-muted">Ar</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Nombre</small>
                                    <div class="fw-bold" id="nombreTotal"><?= number_format($recapitulatif['nombre_total_besoins']) ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantité</small>
                                    <div class="fw-bold" id="quantiteTotale"><?= number_format($recapitulatif['quantite_totale_besoins']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Besoins Satisfaits</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-success" id="montantSatisfait"><?= number_format($recapitulatif['montant_satisfait'], 0, ',', ' ') ?></h2>
                            <p class="text-muted">Ar</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Nombre</small>
                                    <div class="fw-bold" id="nombreSatisfait"><?= number_format($recapitulatif['nombre_besoins_satisfaits']) ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantité</small>
                                    <div class="fw-bold" id="quantiteSatisfaite"><?= number_format($recapitulatif['quantite_satisfaite']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Besoins Restants</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-warning" id="montantRestant"><?= number_format($recapitulatif['montant_restant'], 0, ',', ' ') ?></h2>
                            <p class="text-muted">Ar</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Nombre</small>
                                    <div class="fw-bold" id="nombreRestant"><?= number_format($recapitulatif['nombre_besoins_restants']) ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantité</small>
                                    <div class="fw-bold" id="quantiteRestante"><?= number_format($recapitulatif['quantite_restante']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barres de progression -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Taux de Satisfaction (Montant)</h6>
                        </div>
                        <div class="card-body">
                            <div class="progress mb-2" style="height: 25px;">
                                <div id="progressionMontant" class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?= $recapitulatif['pourcentage_montant_satisfait'] ?>%">
                                    <?= $recapitulatif['pourcentage_montant_satisfait'] ?>%
                                </div>
                            </div>
                            <small class="text-muted">
                                <?= number_format($recapitulatif['montant_satisfait'], 0, ',', ' ') ?> Ar sur 
                                <?= number_format($recapitulatif['montant_total_besoins'], 0, ',', ' ') ?> Ar
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Taux de Satisfaction (Quantité)</h6>
                        </div>
                        <div class="card-body">
                            <div class="progress mb-2" style="height: 25px;">
                                <div id="progressionQuantite" class="progress-bar bg-info" role="progressbar" 
                                     style="width: <?= $recapitulatif['pourcentage_quantite_satisfaite'] ?>%">
                                    <?= $recapitulatif['pourcentage_quantite_satisfaite'] ?>%
                                </div>
                            </div>
                            <small class="text-muted">
                                <?= number_format($recapitulatif['quantite_satisfaite']) ?> sur 
                                <?= number_format($recapitulatif['quantite_totale_besoins']) ?> unités
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau détaillé par région -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Détail par Région</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Région</th>
                                    <th>Besoins Totaux</th>
                                    <th>Besoins Satisfaits</th>
                                    <th>Besoins Restants</th>
                                    <th>Taux Satisfaction</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody id="tableauRegions">
                                <?php foreach ($recapitulatif_par_region as $region): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($region['region_libelle']) ?></strong></td>
                                        <td>
                                            <?= number_format($region['montant_total_besoins'], 0, ',', ' ') ?> Ar
                                            <br><small class="text-muted"><?= number_format($region['nombre_total_besoins']) ?> besoins</small>
                                        </td>
                                        <td class="text-success">
                                            <?= number_format($region['montant_satisfait'], 0, ',', ' ') ?> Ar
                                            <br><small><?= number_format($region['quantite_satisfaite']) ?> unités</small>
                                        </td>
                                        <td class="text-warning">
                                            <?= number_format($region['montant_restant'], 0, ',', ' ') ?> Ar
                                            <br><small><?= number_format($region['quantite_restante']) ?> unités</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= ($region['taux_satisfaction_montant'] >= 80) ? 'success' : (($region['taux_satisfaction_montant'] >= 50) ? 'warning' : 'danger') ?>">
                                                <?= number_format($region['taux_satisfaction_montant'], 1) ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px; width: 100px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: <?= $region['taux_satisfaction_montant'] ?>%">
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

            <!-- Dernière mise à jour -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Dernière mise à jour: <span id="derniereMiseAJour"><?= date('d/m/Y H:i:s') ?></span>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnActualiser = document.getElementById('btnActualiser');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const cartesPrincipales = document.getElementById('cartesPrincipales');
    
    btnActualiser.addEventListener('click', function() {
        actualiserDonnees();
    });
    
    function actualiserDonnees() {
        // Afficher l'indicateur de chargement
        loadingIndicator.classList.remove('d-none');
        btnActualiser.disabled = true;
        btnActualiser.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Actualisation...';
        
        // Ajouter l'animation de rotation
        const style = document.createElement('style');
        style.textContent = '.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
        
        fetch('/achats/api/recapitulatif')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mettreAJourInterface(data.recapitulatif, data.recapitulatif_par_region);
                    document.getElementById('derniereMiseAJour').textContent = new Date().toLocaleString('fr-FR');
                    
                    // Afficher un message de succès
                    afficherMessage('Données actualisées avec succès', 'success');
                } else {
                    afficherMessage('Erreur lors de l\'actualisation: ' + data.error, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                afficherMessage('Erreur de connexion au serveur', 'danger');
            })
            .finally(() => {
                // Masquer l'indicateur de chargement
                loadingIndicator.classList.add('d-none');
                btnActualiser.disabled = false;
                btnActualiser.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser';
            });
    }
    
    function mettreAJourInterface(recapitulatif, recapitulatifParRegion) {
        // Mettre à jour les cartes principales
        document.getElementById('montantTotal').textContent = formatNombre(recapitulatif.montant_total_besoins);
        document.getElementById('nombreTotal').textContent = formatNombre(recapitulatif.nombre_total_besoins);
        document.getElementById('quantiteTotale').textContent = formatNombre(recapitulatif.quantite_totale_besoins);
        
        document.getElementById('montantSatisfait').textContent = formatNombre(recapitulatif.montant_satisfait);
        document.getElementById('nombreSatisfait').textContent = formatNombre(recapitulatif.nombre_besoins_satisfaits);
        document.getElementById('quantiteSatisfaite').textContent = formatNombre(recapitulatif.quantite_satisfaite);
        
        document.getElementById('montantRestant').textContent = formatNombre(recapitulatif.montant_restant);
        document.getElementById('nombreRestant').textContent = formatNombre(recapitulatif.nombre_besoins_restants);
        document.getElementById('quantiteRestante').textContent = formatNombre(recapitulatif.quantite_restante);
        
        // Mettre à jour les barres de progression
        const progressionMontant = document.getElementById('progressionMontant');
        progressionMontant.style.width = recapitulatif.pourcentage_montant_satisfait + '%';
        progressionMontant.textContent = recapitulatif.pourcentage_montant_satisfait + '%';
        
        const progressionQuantite = document.getElementById('progressionQuantite');
        progressionQuantite.style.width = recapitulatif.pourcentage_quantite_satisfaite + '%';
        progressionQuantite.textContent = recapitulatif.pourcentage_quantite_satisfaite + '%';
        
        // Mettre à jour le tableau par région
        mettreAJourTableauRegions(recapitulatifParRegion);
    }
    
    function mettreAJourTableauRegions(regions) {
        const tbody = document.getElementById('tableauRegions');
        tbody.innerHTML = '';
        
        regions.forEach(region => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${region.region_libelle}</strong></td>
                <td>
                    ${formatNombre(region.montant_total_besoins)} Ar
                    <br><small class="text-muted">${formatNombre(region.nombre_total_besoins)} besoins</small>
                </td>
                <td class="text-success">
                    ${formatNombre(region.montant_satisfait)} Ar
                    <br><small>${formatNombre(region.quantite_satisfaite)} unités</small>
                </td>
                <td class="text-warning">
                    ${formatNombre(region.montant_restant)} Ar
                    <br><small>${formatNombre(region.quantite_restante)} unités</small>
                </td>
                <td>
                    <span class="badge bg-${getBadgeClass(region.taux_satisfaction_montant)}">
                        ${formatNombre(region.taux_satisfaction_montant, 1)}%
                    </span>
                </td>
                <td>
                    <div class="progress" style="height: 20px; width: 100px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: ${region.taux_satisfaction_montant}%">
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    function formatNombre(nombre, decimales = 0) {
        return parseFloat(nombre || 0).toLocaleString('fr-FR', {
            minimumFractionDigits: decimales,
            maximumFractionDigits: decimales
        });
    }
    
    function getBadgeClass(taux) {
        if (taux >= 80) return 'success';
        if (taux >= 50) return 'warning';
        return 'danger';
    }
    
    function afficherMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Actualisation automatique toutes les 30 secondes (optionnel)
    // setInterval(actualiserDonnees, 30000);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
