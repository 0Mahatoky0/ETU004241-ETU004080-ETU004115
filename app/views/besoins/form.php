<?php include(BASE_URL . '/includes/header.php'); ?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 page-title"><i class="fas fa-exclamation-triangle me-2"></i> Saisir un Besoin</h2>
            <p class="text-muted mb-0">Enregistrer un nouveau besoin dans le système</p>
        </div>
        <a href="/besoins/liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-hands-helping me-2"></i> Informations du Besoin</h5>
                </div>
                <div class="card-body">
                    <form action="/besoin_sinistre/api/add" method="POST">
                        <!-- Ville -->
                        <div class="mb-4">
                            <label for="id_ville" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i> Ville <span class="text-danger">*</span>
                            </label>
                            <select name="id_ville" id="id_ville" class="form-select form-control-custom" required>
                                <option value="">-- Sélectionner une ville --</option>
                                <?php foreach ($villes as $ville) { ?>
                                    <option value="<?= $ville["id"] ?>"><?= $ville["libelle"] ?></option>
                                <?php } ?>
                            </select>
                            <small class="form-text text-muted">Choisissez la ville concernée par ce besoin</small>
                        </div>

                        <!-- Catégorie de Besoin (1er dropdown) -->
                        <div class="mb-4">
                            <label for="id_categorie" class="form-label">
                                <i class="fas fa-list-alt me-2"></i> Catégorie de Besoin <span class="text-danger">*</span>
                            </label>
                            <select name="id_categorie" id="id_categorie" class="form-select form-control-custom" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php foreach ($besoins as $categorie) { ?>
                                    <option value="<?= $categorie["id"] ?>"><?= $categorie["libelle"] ?></option>
                                <?php } ?>
                            </select>
                            <small class="form-text text-muted">Choisissez la catégorie, puis sélectionnez le besoin spécifique</small>
                        </div>

                        <!-- Besoin (2ème dropdown dépendant) -->
                        <div class="mb-4">
                            <label for="id_besoin" class="form-label">
                                <i class="fas fa-clipboard-list me-2"></i> Elements <span class="text-danger">*</span>
                            </label>
                            <select name="id_besoin" id="id_besoin" class="form-select form-control-custom" required>
                                <option value="">-- Sélectionner un besoin --</option>
                                <!-- options remplies dynamiquement via JS -->
                            </select>
                            <small class="form-text text-muted">Les options sont filtrées selon la catégorie sélectionnée</small>
                        </div>

                        <!-- Quantité -->
                        <div class="mb-4">
                            <label for="quantite" class="form-label">
                                <i class="fas fa-box me-2"></i> Quantité <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="quantite" 
                                   id="quantite" 
                                   class="form-control form-control-custom" 
                                   placeholder="Entrez la quantité nécessaire"
                                   min="1"
                                   required>
                            <small class="form-text text-muted">Indiquez la quantité requise pour ce besoin</small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-2"></i> Enregistrer le Besoin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information supplémentaire -->
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note :</strong> Tous les champs marqués d'un astérisque (<span class="text-danger">*</span>) sont obligatoires.
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

    /* Alert */
    .alert {
        border-radius: 6px;
        border: none;
    }

    .alert-info {
        background-color: #e8f4f8;
        color: #0c5460;
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

<?php include(BASE_URL . '/includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('id_categorie');
    const besoinSelect = document.getElementById('id_besoin');

    categorieSelect && categorieSelect.addEventListener('change', function() {
        const catId = this.value;
        // vider le select besoin
        besoinSelect.innerHTML = '<option value="">-- Sélectionner un besoin --</option>';
        if (!catId) return;

        // appeler l'API (on envoie aussi en query pour compatibilité)
        fetch('/api/besoins/categorie/' + encodeURIComponent(catId) + '?id_categorie=' + encodeURIComponent(catId))
            .then(resp => resp.json())
            .then(data => {
                if (!Array.isArray(data)) return;
                data.forEach(function(item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.libelle || item.libelle_besoin || item.nom || item.label;
                    besoinSelect.appendChild(opt);
                });
            })
            .catch(err => console.error('Erreur chargement besoins:', err));
    });
});
</script>