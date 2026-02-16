<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-gear"></i> Configuration des Achats</h2>
                <a href="/achats/besoins-restants" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour aux achats
                </a>
            </div>

            <!-- Messages -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Configuration des frais -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-percent"></i> Frais d'Achat</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/achats/configuration">
                                <div class="mb-3">
                                    <label for="frais_achat_percent" class="form-label">
                                        Pourcentage des frais d'achat
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="frais_achat_percent" 
                                               name="frais_achat_percent"
                                               value="<?= number_format($config['frais_achat_percent'], 2, '.', '') ?>"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="form-text">
                                        Ce pourcentage sera appliqué sur le montant brut de chaque achat
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Enregistrer la configuration
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Simulation actuelle -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calculator"></i> Simulation avec les frais actuels</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="sim_montant_brut" class="form-label">Montant brut (Ar)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="sim_montant_brut" 
                                       value="100000"
                                       min="0" 
                                       step="0.01">
                            </div>
                            
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Frais (<?= number_format($config['frais_achat_percent'], 2) ?>%):</strong>
                                        <div id="sim_frais" class="fs-5"><?= number_format(100000 * $config['frais_achat_percent'] / 100, 2, ',', ' ') ?> Ar</div>
                                    </div>
                                    <div class="col-6">
                                        <strong>Total:</strong>
                                        <div id="sim_total" class="fs-5 text-success"><?= number_format(100000 * (1 + $config['frais_achat_percent'] / 100), 2, ',', ' ') ?> Ar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations sur la configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Configuration actuelle</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Frais d'achat:</td>
                                            <td><strong><?= number_format($config['frais_achat_percent'], 2) ?>%</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Dernière mise à jour:</td>
                                            <td><?= date('d/m/Y H:i:s', strtotime($config['updated_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Date de création:</td>
                                            <td><?= date('d/m/Y H:i:s', strtotime($config['created_at'])) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Impact sur les achats</h6>
                                    <p>
                                        Les frais d'achat sont calculés automatiquement sur chaque transaction 
                                        selon la formule suivante:
                                    </p>
                                    <div class="alert alert-light">
                                        <code>
                                            Montant Total = Montant Brut + (Montant Brut × Frais% / 100)
                                        </code>
                                    </div>
                                    <p class="mb-0">
                                        <small class="text-muted">
                                            Exemple: Pour un achat de 100 000 Ar avec 10% de frais:<br>
                                            • Montant brut: 100 000 Ar<br>
                                            • Frais: 10 000 Ar<br>
                                            • Total: 110 000 Ar
                                        </small>
                                    </p>
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
    const fraisPercent = <?= $config['frais_achat_percent'] ?>;
    const montantBrutInput = document.getElementById('sim_montant_brut');
    const fraisSpan = document.getElementById('sim_frais');
    const totalSpan = document.getElementById('sim_total');
    
    function updateSimulation() {
        const montantBrut = parseFloat(montantBrutInput.value) || 0;
        const frais = montantBrut * fraisPercent / 100;
        const total = montantBrut + frais;
        
        fraisSpan.textContent = frais.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' Ar';
        totalSpan.textContent = total.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' Ar';
    }
    
    montantBrutInput.addEventListener('input', updateSimulation);
    updateSimulation();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
