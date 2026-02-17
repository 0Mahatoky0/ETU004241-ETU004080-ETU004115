<?php include(BASE_URL . 'includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title"><i class="fas fa-hand-holding-heart me-2"></i> Saisir un Don</h2>
            <p class="text-muted mb-0">Enregistrer un nouveau don dans le système</p>
        </div>
        <a href="/dons/liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-gift me-2"></i> Informations du Don</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/dons/store">
                        <!-- Catégorie et Besoin -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_categorie" class="form-label">
                                    <i class="fas fa-folder me-2"></i> Catégorie de Besoin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control-custom" id="id_categorie" name="id_categorie" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?php echo $categorie['id']; ?>">
                                            <?php echo htmlspecialchars($categorie['libelle']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Choisissez la catégorie du besoin</small>
                            </div>
                            <div class="col-md-6">
                                <label for="id_besoin" class="form-label">
                                    <i class="fas fa-list-alt me-2"></i> Besoin Spécifique <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control-custom" id="id_besoin" name="id_besoin" required disabled>
                                    <option value="">Sélectionner d'abord une catégorie</option>
                                </select>
                                <small class="form-text text-muted">Sélectionnez le besoin concerné</small>
                            </div>
                        </div>
                        
                        <!-- Quantité/Montant et Source -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantite" class="form-label">
                                    <i class="fas fa-box me-2"></i> Quantité Donnée <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-custom" 
                                       id="quantite" 
                                       name="quantite" 
                                       min="1" 
                                       required 
                                       placeholder="Ex: 50">
                                <small class="form-text text-muted">Indiquez la quantité offerte</small>

                                <div id="montant_wrap" style="display:none; margin-top:1.5rem;">
                                    <label for="montant" class="form-label">
                                        <i class="fas fa-money-bill-wave me-2"></i> Montant (MGA) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control form-control-custom" 
                                           id="montant" 
                                           name="montant" 
                                           placeholder="Ex: 150000">
                                    <small class="form-text text-muted">Montant en Ariary</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label">
                                    <i class="fas fa-user-tag me-2"></i> Source du Don <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-custom" 
                                       id="source" 
                                       name="source" 
                                       required 
                                       placeholder="Ex: ONG ABC, Particulier XYZ">
                                <small class="form-text text-muted">Nom du donateur ou de l'organisation</small>
                            </div>
                        </div>
                        
                        <!-- Information du don -->
                        <div class="mb-4">
                            <div id="don_info" class="alert alert-info-custom" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Information:</strong> <span id="don_details"></span>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/dons/liste" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i> Voir la Liste
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-2"></i> Enregistrer le Don
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information supplémentaire -->
            <div class="alert alert-info mt-3">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Astuce :</strong> Pour les dons en argent, sélectionnez une catégorie contenant "argent" pour afficher le champ montant.
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

    /* Buttons */
    .btn-primary-custom {
        background-color: #34495e;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #2c3e50;
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

    /* Alerts */
    .alert {
        border-radius: 6px;
        border: none;
    }

    .alert-info {
        background-color: #e8f4f8;
        color: #0c5460;
    }

    .alert-info-custom {
        background-color: #e3f2fd;
        color: #1565c0;
        border: 1px solid #90caf9;
        border-radius: 6px;
        padding: 1rem 1.25rem;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('id_categorie');
    const besoinSelect = document.getElementById('id_besoin');
    const quantiteInput = document.getElementById('quantite');
    const montantWrap = document.getElementById('montant_wrap');
    const montantInput = document.getElementById('montant');
    const donInfo = document.getElementById('don_info');
    const donDetails = document.getElementById('don_details');
    
    // Charger les besoins quand une catégorie est sélectionnée
    categorieSelect.addEventListener('change', function() {
        const idCategorie = this.value;
        const selectedText = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text.toLowerCase() : '';
        const isArgent = selectedText.includes('argent');

        // toggle montant / quantite
        if (isArgent) {
            montantWrap.style.display = 'block';
            montantInput.required = true;
            quantiteInput.required = false;
            quantiteInput.value = '';
            quantiteInput.disabled = true;
        } else {
            montantWrap.style.display = 'none';
            montantInput.required = false;
            montantInput.value = '';
            quantiteInput.disabled = false;
            quantiteInput.required = true;
        }

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
    
    // Afficher les informations du don quand un besoin est sélectionné
    function updateDonInfo() {
        const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
        const prix = selectedOption ? selectedOption.getAttribute('data-prix') : null;
        const quantite = quantiteInput.value;
        const montant = montantInput ? montantInput.value : null;
        const source = document.getElementById('source').value;

        // if montant visible -> show montant info
        if (montant && montant.trim() !== '' && source) {
            const m = parseFloat(montant);
            donDetails.textContent = `Montant: ${m.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})} | Source: ${source}`;
            donInfo.style.display = 'block';
            return;
        }

        if (prix && quantite && source) {
            const total = parseFloat(prix) * parseInt(quantite);
            donDetails.textContent = `Valeur estimée: ${total.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})} | Source: ${source}`;
            donInfo.style.display = 'block';
        } else {
            donInfo.style.display = 'none';
        }
    }
    
    besoinSelect.addEventListener('change', updateDonInfo);
    quantiteInput.addEventListener('input', updateDonInfo);
    if (montantInput) {
        montantInput.addEventListener('input', updateDonInfo);
    }
    document.getElementById('source').addEventListener('input', updateDonInfo);
});
</script>

<?php include(BASE_URL . 'includes/footer.php'); ?>