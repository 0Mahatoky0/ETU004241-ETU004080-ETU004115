<?php include(BASE_URL . '/includes/header.php'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-info-circle"></i> Détails du Besoin</h2>
        <a href="/dispatch/etat" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5>Informations du Besoin</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Région:</strong><br><?php echo htmlspecialchars($besoin['region_libelle']); ?></div>
                <div class="col-md-3"><strong>Ville:</strong><br><?php echo htmlspecialchars($besoin['ville_libelle']); ?></div>
                <div class="col-md-3"><strong>Besoin:</strong><br><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></div>
                <div class="col-md-3"><strong>Statut:</strong><br>
                    <?php 
                    $restante = $besoin['quantite'] - ($besoin['quantite_assignee'] ?? 0);
                    echo $restante == 0 ? '<span class="badge bg-success">Satisfait</span>' : '<span class="badge bg-warning">En attente</span>';
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>Mouvements de Don</h5>
        </div>
        <div class="card-body">
            <?php if (empty($mouvements)): ?>
                <p class="text-muted">Aucun mouvement enregistré.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Type</th>
                                <th>Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mouvements as $mvt): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($mvt['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($mvt['don_source']); ?></td>
                                    <td>
                                        <?php if ($mvt['entrer'] > 0): ?>
                                            <span class="badge bg-success">Entrée</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Sortie</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo number_format($mvt['entrer'] + $mvt['sortie']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include(BASE_URL . '/includes/footer.php'); ?>
