<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chart-line"></i> État des Besoins</h2>
        <a href="/dispatch/simulation" class="btn btn-primary">
            <i class="fas fa-truck"></i> Simulation de Dispatch
        </a>
    </div>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h5>Total Besoins</h5>
                    <h3><?php echo count($tous_besoins); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5>Satisfaits</h5>
                    <h3><?php echo count($besoins_satisfaits); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h5>Non Satisfaits</h5>
                    <h3><?php echo count($besoins_non_satisfaits); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-2"></i>
                    <h5>Taux Satisfaction</h5>
                    <h3>
                        <?php 
                        $taux = count($tous_besoins) > 0 ? (count($besoins_satisfaits) / count($tous_besoins)) * 100 : 0;
                        echo number_format($taux, 1); ?>%
                    </h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets pour les différents états -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="etatTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tous-tab" data-bs-toggle="tab" data-bs-target="#tous" type="button">
                        <i class="fas fa-list"></i> Tous les Besoins (<?php echo count($tous_besoins); ?>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="satisfaits-tab" data-bs-toggle="tab" data-bs-target="#satisfaits" type="button">
                        <i class="fas fa-check-circle text-success"></i> Satisfaits (<?php echo count($besoins_satisfaits); ?>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="non-satisfaits-tab" data-bs-toggle="tab" data-bs-target="#non-satisfaits" type="button">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Non Satisfaits (<?php echo count($besoins_non_satisfaits); ?>)
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="etatTabsContent">
                <!-- Onglet: Tous les besoins -->
                <div class="tab-pane fade show active" id="tous" role="tabpanel">
                    <?php if (empty($tous_besoins)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun besoin enregistré.</p>
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
                                        <th>Besoin</th>
                                        <th>Quantité</th>
                                        <th>Assignée</th>
                                        <th>Restante</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tous_besoins as $besoin): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($besoin['region_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['ville_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></td>
                                            <td><?php echo number_format($besoin['quantite']); ?></td>
                                            <td><?php echo number_format($besoin['quantite_assignee'] ?? 0); ?></td>
                                            <td>
                                                <?php 
                                                $restante = $besoin['quantite'] - ($besoin['quantite_assignee'] ?? 0);
                                                if ($restante == 0) {
                                                    echo '<span class="badge bg-success">0</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">' . number_format($restante) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $restante = $besoin['quantite'] - ($besoin['quantite_assignee'] ?? 0);
                                                if ($restante == 0) {
                                                    echo '<span class="badge bg-success">Satisfait</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">En attente</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/dispatch/details/<?php echo $besoin['id']; ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($restante > 0): ?>
                                                        <a href="/dispatch/simuler/<?php echo $besoin['id']; ?>" 
                                                           class="btn btn-sm btn-outline-success" title="Dispatch">
                                                            <i class="fas fa-truck"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Onglet: Besoins satisfaits -->
                <div class="tab-pane fade" id="satisfaits" role="tabpanel">
                    <?php if (empty($besoins_satisfaits)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun besoin satisfait pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Quantité Totale</th>
                                        <th>Quantité Assignée</th>
                                        <th>Date Satisfaction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins_satisfaits as $besoin): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($besoin['region_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['ville_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></td>
                                            <td><?php echo number_format($besoin['quantite']); ?></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo number_format($besoin['quantite_assignee'] ?? 0); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php 
                                                    // Simuler une date de satisfaction (dans une vraie app, viendrait de la BDD)
                                                    echo date('d/m/Y H:i', strtotime('-' . rand(1, 30) . ' days')); 
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <a href="/dispatch/details/<?php echo $besoin['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Onglet: Besoins non satisfaits -->
                <div class="tab-pane fade" id="non-satisfaits" role="tabpanel">
                    <?php if (empty($besoins_non_satisfaits)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success">Tous les besoins sont satisfaits! 🎉</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Quantité Requise</th>
                                        <th>Quantité Assignée</th>
                                        <th>Quantité Restante</th>
                                        <th>Priorité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins_non_satisfaits as $besoin): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($besoin['region_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['ville_libelle']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></td>
                                            <td><?php echo number_format($besoin['quantite_requise']); ?></td>
                                            <td><?php echo number_format($besoin['quantite_assignee']); ?></td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <?php echo number_format($besoin['quantite_restante']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                $pourcentage = ($besoin['quantite_restante'] / $besoin['quantite_requise']) * 100;
                                                if ($pourcentage > 75) {
                                                    echo '<span class="badge bg-danger">Urgent</span>';
                                                } elseif ($pourcentage > 50) {
                                                    echo '<span class="badge bg-warning">Moyen</span>';
                                                } else {
                                                    echo '<span class="badge bg-info">Faible</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/dispatch/details/<?php echo $besoin['id']; ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="/dispatch/simuler/<?php echo $besoin['id']; ?>" 
                                                       class="btn btn-sm btn-outline-success" title="Dispatch maintenant">
                                                        <i class="fas fa-truck"></i>
                                                    </a>
                                                </div>
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
</div>

<?php include('includes/footer.php'); ?>
