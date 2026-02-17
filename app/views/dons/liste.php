<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title"><i class="fas fa-hand-holding-heart me-2"></i> Liste des Dons</h2>
            <p class="text-muted mb-0">Gestion et suivi de tous les dons enregistrés</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/dons/achat" class="btn btn-warning-custom">
                <i class="fas fa-shopping-cart me-2"></i> Acheter avec dons
            </a>
            <a href="/dons/saisie" class="btn btn-primary-custom">
                <i class="fas fa-plus me-2"></i> Nouveau Don
            </a>
        </div>
    </div>
    
    <!-- Messages de succès -->
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'achat'): ?>
            <div class="alert alert-success-custom alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> 
                <strong>Succès !</strong> Achat effectué avec succès.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-success-custom alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> 
                <strong>Succès !</strong> Don enregistré avec succès.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Tableau des dons -->
    <div class="card list-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-gift me-2"></i> Dons Enregistrés</h5>
        </div>
        <div class="card-body">
            <?php if (empty($dons)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun don enregistré pour le moment.</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="/dons/saisie" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i> Saisir un don
                        </a>
                        <a href="/dons/achat" class="btn btn-warning-custom">
                            <i class="fas fa-shopping-cart me-2"></i> Acheter avec dons
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table list-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                <th><i class="fas fa-folder me-2"></i>Catégorie</th>
                                <th><i class="fas fa-list-alt me-2"></i>Besoin</th>
                                <th class="text-center"><i class="fas fa-box me-2"></i>Quantité</th>
                                <th class="text-end"><i class="fas fa-tag me-2"></i>Prix Unitaire</th>
                                <th class="text-end"><i class="fas fa-calculator me-2"></i>Valeur Estimée</th>
                                <th><i class="fas fa-user-tag me-2"></i>Source</th>
                                <th class="text-center"><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td>
                                        <span class="text-nowrap">
                                            <?php echo date('d/m/Y', strtotime($don['date'])); ?>
                                        </span>
                                        <br>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($don['date'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-category">
                                            <?php echo htmlspecialchars($don['categorie_libelle']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($don['besoin_libelle']); ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="quantity-badge">
                                            <?php echo isset($don['quantite']) && $don['quantite'] !== null ? (int)$don['quantite'] : (isset($don['montant']) ? number_format($don['montant'], 2) . ' MGA' : '-'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end text-muted">
                                        <?php echo number_format($don['prix_unitaire'], 2); ?> MGA
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-primary">
                                            <?php
                                                if (isset($don['quantite']) && $don['quantite'] !== null) {
                                                    echo number_format($don['quantite'] * $don['prix_unitaire'], 2) . ' MGA';
                                                } elseif (isset($don['montant']) && $don['montant'] !== null) {
                                                    echo number_format($don['montant'], 2) . ' MGA';
                                                } else {
                                                    echo '-';
                                                }
                                            ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-source">
                                            <?php echo htmlspecialchars($don['source']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-action" 
                                                title="Voir les détails" 
                                                onclick="showDonDetails(<?php echo $don['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
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

<!-- Modal pour les détails du don -->
<div class="modal fade" id="donDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i> Détails du Don</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="donDetailsContent">
                <!-- Contenu chargé via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Fermer
                </button>
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

    .badge-source {
        background-color: #6c757d;
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

    .btn-warning-custom {
        background-color: #f39c12;
        color: #fff;
        border: none;
        padding: 0.65rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-warning-custom:hover {
        background-color: #e67e22;
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
    }

    .btn-action:hover {
        background-color: #2c3e50;
        transform: scale(1.05);
    }

    .btn-outline-secondary {
        color: #6c757d;
        border: 1px solid #d0d0d0;
        padding: 0.65rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #6c757d;
        color: #495057;
    }

    /* Alerts */
    .alert-success-custom {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    /* Modal */
    .modal-custom .modal-header {
        background-color: #2c3e50;
        color: #fff;
        border-bottom: none;
    }

    .modal-custom .modal-header .btn-close {
        filter: invert(1);
    }

    .detail-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-item strong {
        color: #2c3e50;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
            width: 100%;
        }

        .d-flex.gap-2 .btn {
            width: 100%;
        }

        .list-table thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
        }

        .list-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
    }
</style>

<script>
function showDonDetails(donId) {
    const modal = new bootstrap.Modal(document.getElementById('donDetailsModal'));
    document.getElementById('donDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="text-muted mt-2">Chargement des détails...</p>
        </div>
    `;
    modal.show();
    
    // Simuler le chargement des détails
    setTimeout(() => {
        document.getElementById('donDetailsContent').innerHTML = `
            <div class="detail-item">
                <strong><i class="fas fa-hashtag me-2"></i> ID:</strong> #${donId}
            </div>
            <div class="detail-item">
                <strong><i class="fas fa-check-circle me-2"></i> Statut:</strong> 
                <span class="badge bg-success">Disponible</span>
            </div>
            <div class="detail-item">
                <strong><i class="fas fa-calendar-alt me-2"></i> Date d'entrée:</strong> 
                ${new Date().toLocaleDateString('fr-MG')}
            </div>
            <div class="detail-item">
                <strong><i class="fas fa-exchange-alt me-2"></i> Mouvements:</strong> 
                1 entrée enregistrée
            </div>
        `;
    }, 500);
}
</script>

<?php include('includes/footer.php'); ?>