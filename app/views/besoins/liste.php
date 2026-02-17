<?php include(BASE_URL . '/includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title"><i class="fas fa-list me-2"></i> Liste des Besoins</h2>
            <p class="text-muted mb-0">Gestion et suivi de tous les besoins par ville</p>
        </div>
        <a href="/besoins/saisie" class="btn btn-primary-custom">
            <i class="fas fa-plus me-2"></i> Nouveau Besoin
        </a>
    </div>
    
    <!-- Messages de succès -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success-custom alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> 
            <strong>Succès !</strong> Besoin enregistré avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Tableau catalogue des besoins -->
    <div class="card list-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-folder me-2"></i> Catalogue des Besoins</h5>
        </div>
        <div class="card-body">
            <?php if (empty($besoins)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun besoin enregistré pour le moment.</p>
                    <a href="/besoins/saisie" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-plus me-2"></i> Saisir un besoin
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table list-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-folder me-2"></i>Catégorie</th>
                                <th><i class="fas fa-list-alt me-2"></i>Besoin</th>
                                <th class="text-end"><i class="fas fa-tag me-2"></i>Prix Unitaire</th>
                                <th class="text-center"><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-category">
                                            <?php echo htmlspecialchars($besoin['categorie_libelle'] ?? $besoin['libelle'] ?? ''); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($besoin['libelle'] ?? $besoin['besoin_libelle'] ?? ''); ?></td>
                                    <td class="text-end text-muted"><?php echo number_format($besoin['prix_unitaire'] ?? 0, 2); ?> MGA</td>
                                    <td class="text-center">
                                        <a href="/dons/achat/<?php echo $besoin['id']; ?>" class="btn btn-sm btn-primary-custom">
                                            <i class="fas fa-shopping-cart me-1"></i> Acheter
                                        </a>
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

<style>
    /* Page title */
    .page-title {
        color: #2c3e50;
        font-weight: 600;
    }

    /* List card */
    .list-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .list-card .card-header {
        background-color: #2c3e50;
        color: #fff;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.25rem;
    }

    .list-card .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
    }

    .list-card .card-body {
        padding: 0;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
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

    /* List table */
    .list-table {
        margin-bottom: 0;
    }

    .list-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .list-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #495057;
    }

    .list-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Badges */
    .badge-category {
        background-color: #34495e;
        color: #fff;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .quantity-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.85rem;
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
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-action {
        background-color: #34495e;
        color: #fff;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        transition: all 0.2s ease;
        margin-right: 0.25rem;
    }

    .btn-action:hover {
        background-color: #2c3e50;
        transform: scale(1.05);
        color: #fff;
    }

    .btn-action-success {
        background-color: #27ae60;
        color: #fff;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .btn-action-success:hover {
        background-color: #229954;
        transform: scale(1.05);
        color: #fff;
    }

    /* Alerts */
    .alert-success-custom {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .list-table thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
        }

        .list-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-action,
        .btn-action-success {
            margin-right: 0;
            width: 100%;
        }
    }
</style>

<?php include(BASE_URL . '/includes/footer.php'); ?>