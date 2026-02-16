<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list"></i> Liste des Besoins par Ville</h2>
        <a href="/besoins/saisie" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Besoin
        </a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Besoin enregistré avec succès!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($besoins)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun besoin enregistré pour le moment.</p>
                    <a href="/besoins/saisie" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Saisir un besoin
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Région</th>
                                <th>Ville</th>
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
                                    <td><?php echo htmlspecialchars($besoin['region_libelle']); ?></td>
                                    <td><?php echo htmlspecialchars($besoin['ville_libelle']); ?></td>
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
                                            <a href="/besoins/ville/<?php echo $besoin['id_ville']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Voir les besoins de cette ville">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Besoins</h5>
                                    <h3><?php echo count($besoins); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Acceptés</h5>
                                    <h3><?php echo count(array_filter($besoins, fn($b) => $b['status_libelle'] == 'Accepte')); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">En attente</h5>
                                    <h3><?php echo count(array_filter($besoins, fn($b) => $b['status_libelle'] == 'En attente')); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
