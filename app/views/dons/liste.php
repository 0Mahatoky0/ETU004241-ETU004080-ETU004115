<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-hand-holding-heart"></i> Liste des Dons</h2>
        <div>
            <a href="/dons/achat" class="btn btn-warning me-2">
                <i class="fas fa-shopping-cart"></i> Acheter avec dons
            </a>
            <a href="/dons/saisie" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouveau Don
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'achat'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Achat effectué avec succès!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Don enregistré avec succès!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($dons)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun don enregistré pour le moment.</p>
                    <div class="btn-group" role="group">
                        <a href="/dons/saisie" class="btn btn-success">
                            <i class="fas fa-plus"></i> Saisir un don
                        </a>
                        <a href="/dons/achat" class="btn btn-warning">
                            <i class="fas fa-shopping-cart"></i> Acheter avec dons
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Besoin</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Valeur Estimée</th>
                                <th>Source</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($don['date'])); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($don['categorie_libelle']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($don['besoin_libelle']); ?></td>
                                    <td><?php echo number_format($don['quantite']); ?></td>
                                    <td><?php echo number_format($don['prix_unitaire'], 2); ?> MGA</td>
                                    <td>
                                        <strong><?php echo number_format($don['quantite'] * $don['prix_unitaire'], 2); ?> MGA</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($don['source']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Voir les détails" 
                                                    onclick="showDonDetails(<?php echo $don['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Dons</h5>
                                    <h3><?php echo count($dons); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Quantité Totale</h5>
                                    <h3><?php echo number_format(array_sum(array_column($dons, 'quantite'))); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Valeur Totale</h5>
                                    <h3><?php echo number_format(array_sum(array_map(fn($d) => $d['quantite'] * $d['prix_unitaire'], $dons)), 0); ?> MGA</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Valeur Moyenne</h5>
                                    <h3><?php echo number_format(array_sum(array_map(fn($d) => $d['quantite'] * $d['prix_unitaire'], $dons)) / count($dons), 0); ?> MGA</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal pour les détails du don -->
<div class="modal fade" id="donDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Don</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="donDetailsContent">
                <!-- Contenu chargé via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDonDetails(donId) {
    // Simulation - dans une vraie application, charger les détails via AJAX
    const modal = new bootstrap.Modal(document.getElementById('donDetailsModal'));
    document.getElementById('donDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
    modal.show();
    
    // Simuler le chargement des détails
    setTimeout(() => {
        document.getElementById('donDetailsContent').innerHTML = `
            <p><strong>ID:</strong> #${donId}</p>
            <p><strong>Statut:</strong> <span class="badge bg-success">Disponible</span></p>
            <p><strong>Date d'entrée:</strong> ${new Date().toLocaleDateString('fr-MG')}</p>
            <p><strong>Mouvements:</strong> 1 entrée enregistrée</p>
        `;
    }, 500);
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
