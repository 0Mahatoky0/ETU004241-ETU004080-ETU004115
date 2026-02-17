<?php include(BASE_URL . 'includes/header.php') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calculator"></i> Simulation d'Achat</h2>
                <a href="/achats/besoins-restants" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <!-- Messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Informations du besoin -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations du Besoin</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Région:</strong></td>
                                    <td><?= htmlspecialchars($besoin['region_libelle']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Ville:</strong></td>
                                    <td><?= htmlspecialchars($besoin['ville_libelle']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Catégorie:</strong></td>
                                    <td><span class="badge bg-info"><?= htmlspecialchars($besoin['categorie_libelle']) ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Besoin:</strong></td>
                                    <td><?= htmlspecialchars($besoin['besoin_libelle']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Quantité restante:</strong></td>
                                    <td><span class="badge bg-success"><?= number_format($besoin['quantite_restante']) ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Prix unitaire:</strong></td>
                                    <td><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de simulation -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calculator"></i> Simulation</h5>
                        </div>
                        <div class="card-body">
                            <form id="simulationForm">
                                <input type="hidden" name="id_besoin_sinistre" value="<?= $besoin['besoin_sinistre_id'] ?>">
                                <input type="hidden" name="id_besoin" value="<?= $besoin['id_besoin'] ?>">
                                <input type="hidden" name="id_ville" value="<?= $besoin['id_ville'] ?>">
                                
                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité à acheter</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite" 
                                           min="1" max="<?= $besoin['quantite_restante'] ?>" required>
                                    <div class="form-text">Maximum: <?= number_format($besoin['quantite_restante']) ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prix_unitaire" class="form-label">Prix unitaire (Ar)</label>
                                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                                           value="<?= number_format($besoin['prix_unitaire'], 2, '.', '') ?>" 
                                           step="0.01" min="0.01" required>
                                </div>
                                
                                <button type="button" id="btnSimuler" class="btn btn-primary w-100">
                                    <i class="bi bi-calculator"></i> Simuler
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultat de la simulation -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card" id="resultatSimulation" style="display: none;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-receipt"></i> Résumé de la Simulation</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">Montant Brut</h6>
                                            <h4 id="montantBrut">0 Ar</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">Frais (<span id="fraisPercent">10</span>%)</h6>
                                            <h4 id="montantFrais">0 Ar</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6>Montant Total</h6>
                                            <h4 id="montantTotal">0 Ar</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6>Action</h6>
                                            <button id="btnValider" class="btn btn-light w-100">
                                                <i class="bi bi-check-circle"></i> Valider l'achat
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du calcul -->
            <div class="row mt-4" id="detailsCalcul" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calculator-fill"></i> Détails du Calcul</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Quantité:</td>
                                            <td><strong id="detailQuantite">0</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Prix unitaire:</td>
                                            <td><strong id="detailPrixUnitaire">0 Ar</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Montant brut:</td>
                                            <td><strong id="detailMontantBrut">0 Ar</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Frais (%):</td>
                                            <td><strong id="detailFraisPercent">0%</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Montant des frais:</td>
                                            <td><strong id="detailMontantFrais">0 Ar</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Montant total:</td>
                                            <td><strong id="detailMontantTotal" class="text-success">0 Ar</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('simulationForm');
    const btnSimuler = document.getElementById('btnSimuler');
    const btnValider = document.getElementById('btnValider');
    const resultatDiv = document.getElementById('resultatSimulation');
    const detailsDiv = document.getElementById('detailsCalcul');
    
    btnSimuler.addEventListener('click', function() {
        const quantite = parseInt(document.getElementById('quantite').value);
        const prixUnitaire = parseFloat(document.getElementById('prix_unitaire').value);
        
        if (!quantite || !prixUnitaire || quantite <= 0 || prixUnitaire <= 0) {
            alert('Veuillez remplir correctement tous les champs');
            return;
        }
        
        // Simuler l'achat
        fetch('/achats/api/simuler', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                quantite: quantite,
                prix_unitaire: prixUnitaire
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const sim = data.simulation;
                
                // Afficher les résultats
                document.getElementById('montantBrut').textContent = sim.montant_brut.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('montantFrais').textContent = sim.montant_frais.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('montantTotal').textContent = sim.montant_total.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('fraisPercent').textContent = sim.frais_percent;
                
                // Détails du calcul
                document.getElementById('detailQuantite').textContent = quantite.toLocaleString('fr-FR');
                document.getElementById('detailPrixUnitaire').textContent = prixUnitaire.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('detailMontantBrut').textContent = sim.montant_brut.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('detailFraisPercent').textContent = sim.frais_percent + '%';
                document.getElementById('detailMontantFrais').textContent = sim.montant_frais.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('detailMontantTotal').textContent = sim.montant_total.toLocaleString('fr-FR') + ' Ar';
                
                resultatDiv.style.display = 'block';
                detailsDiv.style.display = 'block';
            } else {
                alert('Erreur: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la simulation');
        });
    });
    
    btnValider.addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir valider cet achat ?')) {
            const formData = new FormData(form);
            
            fetch('/achats/valider', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .then(data => {
                if (data) {
                    // En cas d'erreur, afficher le message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger alert-dismissible fade show';
                    errorDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle"></i> ${data}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.container').insertBefore(errorDiv, document.querySelector('.row'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la validation');
            });
        }
    });
});
</script>

<?php include(BASE_URL . 'includes/footer.php') ?>
