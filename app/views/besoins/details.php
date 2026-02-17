<?php include(BASE_URL . '/includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title">
                <i class="fas fa-city me-2"></i> <?php echo htmlspecialchars($ville['libelle'] ?? ''); ?>
            </h2>
            <p class="text-muted mb-0">
                <i class="fas fa-map-marked-alt me-2"></i>Région: <?php echo htmlspecialchars($ville['region_libelle'] ?? ''); ?>
            </p>
        </div>
        <a href="/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Liste des besoins -->
        <div class="col-lg-8">
            <div class="card detail-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-hands-helping me-2"></i> Liste des besoins</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($besoins)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Aucun besoin enregistré pour cette ville.</p>
                            <a href="/besoins/saisie" class="btn btn-primary-custom mt-2">
                                <i class="fas fa-plus me-2"></i> Saisir un besoin
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table detail-table">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Besoin</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Total Estimé</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins as $besoin): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-category">
                                                    <?php echo htmlspecialchars($besoin['categorie_libelle']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($besoin['besoin_libelle']); ?></strong>
                                            </td>
                                            <td><?php echo number_format($besoin['quantite']); ?></td>
                                            <td class="text-muted"><?php echo number_format($besoin['prix_unitaire'], 2); ?> MGA</td>
                                            <td>
                                                <strong class="text-primary">
                                                    <?php echo number_format($besoin['quantite'] * $besoin['prix_unitaire'], 2); ?> MGA
                                                </strong>
                                            </td>
                                            <td>
                                                <?php if ($besoin['status_libelle'] == 'Accepte'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Accepté
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> En attente
                                                    </span>
                                                <?php endif; ?>
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

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Dons associés -->
            <div class="card detail-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-gift me-2"></i> Dons associés</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($dons)): ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p class="mb-0">Aucun don enregistré pour cette ville.</p>
                        </div>
                    <?php else: ?>
                        <div class="dons-list">
                            <?php foreach ($dons as $don): ?>
                                <div class="don-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="don-title">
                                                <?php echo htmlspecialchars($don['besoin_libelle']); ?>
                                            </div>
                                            <div class="don-category">
                                                <?php echo htmlspecialchars($don['categorie_libelle']); ?>
                                            </div>
                                        </div>
                                        <div class="text-end ms-3">
                                            <div class="don-quantity">
                                                <?php echo isset($don['quantite']) && $don['quantite'] !== null ? (int)$don['quantite'] : (isset($don['montant']) ? number_format($don['montant'], 2) . ' MGA' : '-'); ?>
                                            </div>
                                            <div class="don-date">
                                                <?php echo date('d/m/Y', strtotime($don['date'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Résumé -->
            <div class="card detail-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">Total Besoins</div>
                            <div class="summary-value"><?php echo count($besoins); ?></div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">Total Dons</div>
                            <div class="summary-value"><?php echo count($dons); ?></div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">Quantité Totale</div>
                            <div class="summary-value">
                                <?php echo number_format(array_sum(array_column($besoins, 'quantite'))); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Page title */
    .page-title {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Detail card */
    .detail-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .detail-card .card-header {
        background-color: #2c3e50;
        color: #fff;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.25rem;
    }

    .detail-card .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
    }

    .detail-card .card-body {
        padding: 1.5rem;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d0d0d0;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Detail table */
    .detail-table {
        margin-bottom: 0;
    }

    .detail-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #495057;
    }

    .detail-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Badge */
    .badge-category {
        background-color: #34495e;
        color: #fff;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    /* Dons list */
    .dons-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .don-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s ease;
    }

    .don-item:last-child {
        border-bottom: none;
    }

    .don-item:hover {
        background-color: #f8f9fa;
    }

    .don-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .don-category {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .don-quantity {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }

    .don-date {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Summary */
    .summary-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        background-color: #34495e;
        color: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .summary-content {
        flex: 1;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Buttons */
    .btn-primary-custom {
        background-color: #34495e;
        color: #fff;
        border: none;
        padding: 0.65rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #2c3e50;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        border: none;
        padding: 0.65rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    /* Scrollbar */
    .dons-list::-webkit-scrollbar {
        width: 6px;
    }

    .dons-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .dons-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .dons-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-value {
            font-size: 1.25rem;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
    }
</style>

<?php include(BASE_URL . '/includes/footer.php'); ?>