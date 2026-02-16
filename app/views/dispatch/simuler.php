<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck"></i> Simuler Dispatch pour <?php echo htmlspecialchars($besoin['ville_libelle']); ?></h2>
        <a href="/dispatch/simulation" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Informations du besoin -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-info-circle"></i> Détails du Besoin</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Région:</strong><br>
                    <?php echo htmlspecialchars($besoin['region_libelle']); ?>
                </div>
                <div class="col-md-3">
                    <strong>Ville:</strong><br>
                    <?php echo htmlspecialchars($besoin['ville_libelle']); ?>
                </div>
                <div class="col-md-3">
                    <strong>Besoin:</strong><br>
                    <?php echo htmlspecialchars($besoin['besoin_libelle']); ?>
                </div>
                <div class="col-md-3">
                    <strong>Quantité Requise:</strong><br>
                    <span class="badge bg-primary"><?php echo number_format($besoin['quantite']); ?></span>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <strong>Quantité Assignée:</strong><br>
                    <span class="badge bg-success"><?php echo number_format($besoin['quantite_assignee'] ?? 0); ?></span>
                </div>
                <div class="col-md-3">
                    <strong>Quantité Restante:</strong><br>
                    <span class="badge bg-warning"><?php echo number_format($besoin['quantite_requise'] - ($besoin['quantite_assignee'] ?? 0)); ?></span>
                </div>
                <div class="col-md-3">
                    <strong>Prix Unitaire:</strong><br>
                    <?php echo number_format($besoin['prix_unitaire'], 2); ?> MGA
                </div>
                <div class="col-md-3">
                    <strong>Valeur Totale:</strong><br>
                    <strong><?php echo number_format($besoin['quantite'] * $besoin['prix_unitaire'], 2); ?> MGA</strong>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock disponible pour ce besoin -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5><i class="fas fa-warehouse"></i> Stock Disponible</h5>
        </div>
        <div class="card-body">
            <?php if (empty($stock_disponible)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Aucun stock disponible pour ce besoin. 
                    <a href="/dons/saisie" class="btn btn-sm btn-success ms-2">
                        <i class="fas fa-plus"></i> Ajouter un don
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Source du Don</th>
                                <th>Quantité Disponible</th>
                                <th>Prix Unitaire</th>
                                <th>Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_disponible = 0;
                            foreach ($stock_disponible as $stock): 
                                $total_disponible += $stock['stock_disponible'];
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stock['don_source'] ?? 'Non spécifié'); ?></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo number_format($stock['stock_disponible']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($stock['prix_unitaire'], 2); ?> MGA</td>
                                    <td>
                                        <strong><?php echo number_format($stock['stock_disponible'] * $stock['prix_unitaire'], 2); ?> MGA</strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <th>Total</th>
                                <th><?php echo number_format($total_disponible); ?></th>
                                <th>-</th>
                                <th>
                                    <?php 
                                    $valeur_totale = array_sum(array_map(fn($s) => $s['stock_disponible'] * $s['prix_unitaire'], $stock_disponible));
                                    echo number_format($valeur_totale, 2); ?> MGA
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Formulaire de dispatch -->
    <?php if (!empty($stock_disponible)): ?>
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5><i class="fas fa-truck-loading"></i> Effectuer le Dispatch</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/dispatch/process">
                    <input type="hidden" name="id_besoin_sinistre" value="<?php echo $besoin['id']; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantite" class="form-label">Quantité à Allouer *</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" 
                                   min="1" max="<?php echo min($total_disponible, $besoin['quantite_requise'] - ($besoin['quantite_assignee'] ?? 0)); ?>" 
                                   required 
                                   value="<?php echo min($total_disponible, $besoin['quantite_requise'] - ($besoin['quantite_assignee'] ?? 0)); ?>"
                                   placeholder="Quantité à dispatcher">
                            <div class="form-text">
                                Maximum: <?php echo number_format(min($total_disponible, $besoin['quantite_requise'] - ($besoin['quantite_assignee'] ?? 0))); ?> unités
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aperçu de l'allocation</label>
                            <div id="allocation_preview" class="alert alert-info">
                                <strong>Allocation automatique:</strong><br>
                                <span id="allocation_details">Sélectionnez une quantité pour voir l'aperçu</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-secondary me-md-2" onclick="previewAllocation()">
                            <i class="fas fa-eye"></i> Aperçu
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-truck"></i> Confirmer le Dispatch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
const stockData = <?php echo json_encode(array_values($stock_disponible)); ?>;
const besoinQuantiteRestante = <?php echo $besoin['quantite_requise'] - ($besoin['quantite_assignee'] ?? 0); ?>;

function previewAllocation() {
    const quantite = parseInt(document.getElementById('quantite').value);
    const allocationDetails = document.getElementById('allocation_details');
    
    if (!quantite || quantite <= 0) {
        allocationDetails.innerHTML = '<span class="text-danger">Veuillez spécifier une quantité valide</span>';
        return;
    }
    
    let quantiteRestante = quantite;
    let allocationHtml = '';
    
    for (const stock of stockData) {
        if (quantiteRestante <= 0) break;
        
        const quantiteAllouee = Math.min(quantiteRestante, stock.stock_disponible);
        allocationHtml += `• ${stock.besoin_libelle}: ${quantiteAllouee} unités<br>`;
        quantiteRestante -= quantiteAllouee;
    }
    
    if (quantiteRestante > 0) {
        allocationHtml += `<br><span class="text-danger">⚠️ Stock insuffisant: il manque ${quantiteRestante} unités</span>`;
    } else {
        allocationHtml += `<br><span class="text-success">✅ Allocation possible</span>`;
    }
    
    allocationDetails.innerHTML = allocationHtml;
}

// Mettre à jour l'aperçu quand la quantité change
document.getElementById('quantite').addEventListener('input', function() {
    if (this.value) {
        previewAllocation();
    }
});

// Aperçu initial au chargement
document.addEventListener('DOMContentLoaded', function() {
    previewAllocation();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
