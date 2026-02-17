<?php include(BASE_URL . 'includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title"><i class="fas fa-shopping-cart me-2"></i> Acheter avec les Dons</h2>
            <p class="text-muted mb-0">Utiliser les dons en argent pour acheter des biens nécessaires</p>
        </div>
        <a href="/dons/liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
        </a>
    </div>

    <!-- Formulaire d'achat -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cash-register me-2"></i> Informations d'Achat</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Utilisez cette fonction pour acheter des biens nécessaires en utilisant les dons en argent reçus.
                    </div>
                    
                    <form method="POST" action="/dons/process-achat">
                        <!-- Catégorie et Besoin -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_categorie" class="form-label">
                                    <i class="fas fa-folder me-2"></i> Catégorie de Besoin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control-custom" id="id_categorie" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?php echo $categorie['id']; ?>">
                                            <?php echo htmlspecialchars($categorie['libelle']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Choisissez la catégorie du bien à acheter</small>
                            </div>
                            <div class="col-md-6">
                                <label for="id_besoin" class="form-label">
                                    <i class="fas fa-list-alt me-2"></i> Bien à Acheter <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control-custom" id="id_besoin" name="id_besoin" required disabled>
                                    <option value="">Sélectionner d'abord une catégorie</option>
                                </select>
                                <small class="form-text text-muted">Sélectionnez le bien à acheter</small>
                            </div>
                        </div>
                        
                        <!-- Quantité et Montant -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantite" class="form-label">
                                    <i class="fas fa-box me-2"></i> Quantité à Acheter <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-custom" 
                                       id="quantite" 
                                       name="quantite" 
                                       min="1" 
                                       required 
                                       placeholder="Ex: 25">
                                <small class="form-text text-muted">Indiquez la quantité à acheter</small>
                            </div>
                            <div class="col-md-6">
                                <label for="montant_disponible" class="form-label">
                                    <i class="fas fa-money-bill-wave me-2"></i> Montant Disponible (MGA) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-custom" 
                                       id="montant_disponible" 
                                       name="montant_disponible" 
                                       min="0" 
                                       step="0.01" 
                                        required 
                                        placeholder="Ex: 100000"
                                           value="<?php echo isset($montant_disponible_total) ? number_format($montant_disponible_total, 2, '.', '') : ''; ?>" readonly>
                                <small class="form-text text-muted">Montant disponible pour l'achat</small>
                            </div>
                        </div>
                        
                        <!-- Informations calculées -->
                        <div class="mb-4">
                            <div id="achat_info" class="alert alert-warning-custom" style="display: none;">
                                <i class="fas fa-calculator me-2"></i>
                                <strong>Calcul d'achat:</strong> <span id="achat_details"></span>
                            </div>
                            <div id="stock_info" class="alert alert-success-custom" style="display: none;">
                                <i class="fas fa-warehouse me-2"></i>
                                <strong>Stock actuel:</strong> <span id="stock_details"></span>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/dons/liste" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-warning-custom">
                                <i class="fas fa-shopping-cart me-2"></i> Confirmer l'Achat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock disponible -->
    <?php if (!empty($stock_disponible)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card list-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i> Stock Actuel Disponible</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table stock-table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-folder me-2"></i>Catégorie</th>
                                        <th><i class="fas fa-box me-2"></i>Bien</th>
                                        <th class="text-end"><i class="fas fa-tag me-2"></i>Prix Unitaire</th>
                                        <th class="text-center"><i class="fas fa-boxes me-2"></i>Quantité Disponible</th>
                                        <th class="text-end"><i class="fas fa-calculator me-2"></i>Valeur Totale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stock_disponible as $stock): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-category">
                                                    <?php echo htmlspecialchars($stock['categorie_libelle']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($stock['besoin_libelle']); ?></strong>
                                            </td>
                                            <td class="text-end text-muted">
                                                <?php echo number_format($stock['prix_unitaire'], 2); ?> MGA
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    <?php echo number_format($stock['stock_disponible']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-primary">
                                                    <?php echo number_format($stock['stock_disponible'] * $stock['prix_unitaire'], 2); ?> MGA
                                                </strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Page title */
    .page-title {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Form card */
    .form-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .form-card .card-header {
        background-color: #2c3e50;
        color: #fff;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }

    .form-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .form-card .card-body {
        padding: 2rem 1.5rem;
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
        padding: 1.5rem;
    }

    /* Form labels */
    .form-label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-label i {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Form controls */
    .form-control-custom {
        border: 1px solid #d0d0d0;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #34495e;
        box-shadow: 0 0 0 0.2rem rgba(52, 73, 94, 0.15);
        outline: none;
    }

    .form-control-custom::placeholder {
        color: #adb5bd;
    }

    .form-select.form-control-custom {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    .form-control-custom:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    /* Form text */
    .form-text {
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }

    /* Alerts */
    .alert-info-custom {
        background-color: #e3f2fd;
        color: #1565c0;
        border: 1px solid #90caf9;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    .alert-warning-custom {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    .alert-success-custom {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
    }

    /* Buttons */
    .btn-warning-custom {
        background-color: #f39c12;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-warning-custom:hover {
        background-color: #e67e22;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-outline-secondary {
        color: #6c757d;
        border: 1px solid #d0d0d0;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #6c757d;
        color: #495057;
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

    /* Stock table */
    .stock-table {
        margin-bottom: 0;
    }

    .stock-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .stock-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #495057;
    }

    .stock-table tbody tr:hover {
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

    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card .card-body {
            padding: 1.5rem 1rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
        }

        .d-flex.gap-2 .btn {
            width: 100%;
        }

        .stock-table thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
        }

        .stock-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('id_categorie');
    const besoinSelect = document.getElementById('id_besoin');
    const quantiteInput = document.getElementById('quantite');
    const montantInput = document.getElementById('montant_disponible');
    const achatInfo = document.getElementById('achat_info');
    const achatDetails = document.getElementById('achat_details');
    const stockInfo = document.getElementById('stock_info');
    const stockDetails = document.getElementById('stock_details');
    
    let stockData = [];
    
    // Charger le stock disponible au chargement
    fetch('/api/stock/disponible')
        .then(response => response.json())
        .then(data => {
            stockData = data;
        })
        .catch(error => console.error('Erreur:', error));

    // Mettre à jour l'affichage initial si la valeur PHP est présente
    updateAchatInfo();
    
    // Charger les besoins quand une catégorie est sélectionnée
    categorieSelect.addEventListener('change', function() {
        const idCategorie = this.value;
        if (idCategorie) {
            fetch(`/api/besoins/categorie/${idCategorie}`)
                .then(response => response.json())
                .then(data => {
                    besoinSelect.innerHTML = '<option value="">-- Sélectionner un besoin --</option>';
                    besoinSelect.disabled = false;
                    data.forEach(besoin => {
                        besoinSelect.innerHTML += `<option value="${besoin.id}" data-prix="${besoin.prix_unitaire}">${besoin.libelle}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    besoinSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            besoinSelect.innerHTML = '<option value="">Sélectionner d\'abord une catégorie</option>';
            besoinSelect.disabled = true;
        }
    });
    
    // Mettre à jour les informations d'achat
    function updateAchatInfo() {
        const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
        const prix = selectedOption ? parseFloat(selectedOption.getAttribute('data-prix')) : null;
        const quantite = parseInt(quantiteInput.value) || 0;
        const montant = parseFloat(montantInput.value) || 0;
        
        if (prix && quantite > 0) {
            const coutTotal = prix * quantite;
            const reste = montant - coutTotal;
            
            let details = `Coût total: ${coutTotal.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})}`;
            
            if (montant > 0) {
                details += ` | Reste: ${reste.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})}`;
                
                if (reste >= 0) {
                    achatInfo.className = 'alert alert-success-custom';
                } else {
                    achatInfo.className = 'alert alert-danger';
                }
            }
            
            achatDetails.textContent = details;
            achatInfo.style.display = 'block';
            
            // Afficher le stock actuel pour ce besoin
            const besoinId = selectedOption.value;
            const stockActuel = stockData.find(s => s.besoin_id == besoinId);
            if (stockActuel) {
                stockDetails.textContent = `${stockActuel.stock_disponible} unités disponibles`;
                stockInfo.style.display = 'block';
            } else {
                stockInfo.style.display = 'none';
            }
        } else {
            achatInfo.style.display = 'none';
            stockInfo.style.display = 'none';
        }
    }
    
    besoinSelect.addEventListener('change', updateAchatInfo);
    quantiteInput.addEventListener('input', updateAchatInfo);
    montantInput.addEventListener('input', updateAchatInfo);
});
</script>

<?php include(BASE_URL . 'includes/footer.php'); ?>