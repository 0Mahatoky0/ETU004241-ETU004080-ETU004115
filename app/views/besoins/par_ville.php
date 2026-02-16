<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-city"></i> Besoins pour la ville de <?php echo htmlspecialchars($ville['libelle']); ?></h2>
            <p class="text-muted">Région: <?php echo htmlspecialchars($ville['region_libelle']); ?></p>
        </div>
        <a href="/besoins/liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-list"></i> Liste des besoins</h5>
        </div>
        <div class="card-body">
            <?php if (empty($besoins)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun besoin enregistré pour cette ville.</p>
                    <a href="/besoins/saisie" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Saisir un besoin
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Catégorie</th>
                                <th>Besoin</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total Estimé</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($besoin['categorie_libelle']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></td>
                                    <td><?php echo number_format($besoin['quantite']); ?></td>
                                    <td><?php echo number_format($besoin['prix_unitaire'], 2); ?> MGA</td>
                                    <td>
                                        <strong><?php echo number_format($besoin['quantite'] * $besoin['prix_unitaire'], 2); ?> MGA</strong>
                                    </td>
                                    <td>
                                        <?php if ($besoin['status_libelle'] == 'Accepte'): ?>
                                            <span class="badge bg-success">Accepté</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/dispatch/simuler/<?php echo $besoin['id']; ?>" 
                                               class="btn btn-sm btn-outline-success" title="Simuler dispatch">
                                                <i class="fas fa-truck"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Besoins</h5>
                                    <h3><?php echo count($besoins); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Quantité Totale</h5>
                                    <h3><?php echo number_format(array_sum(array_column($besoins, 'quantite'))); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Valeur Totale</h5>
                                    <h3><?php echo number_format(array_sum(array_map(fn($b) => $b['quantite'] * $b['prix_unitaire'], $besoins)), 0); ?> MGA</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
