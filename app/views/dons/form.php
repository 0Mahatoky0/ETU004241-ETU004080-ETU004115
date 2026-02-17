<?php include('includes/header.php'); ?>

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
        <div class="col-lg-8">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-gift me-2"></i> Informations du Don</h5>
                </div>
                <div class="card-body">
                    <form action="/dons/api/add" method="POST">
                        <!-- Catégorie -->
                        <div class="mb-4">
                            <label for="id_categorie" class="form-label">
                                <i class="fas fa-tags me-2"></i> Catégorie <span class="text-danger">*</span>
                            </label>
                            <select name="id_categorie" id="id_categorie" class="form-select form-control-custom" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php if (!empty($categories)) { foreach ($categories as $c) { ?>
                                    <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["libelle"]) ?></option>
                                <?php } } ?>
                            </select>
                            <small class="form-text text-muted">Choisissez la catégorie pour filtrer les besoins</small>
                        </div>

                        <!-- Besoin -->
                        <div class="mb-4">
                            <label for="id_besoin" class="form-label">
                                <i class="fas fa-list-alt me-2"></i> Besoin <span class="text-danger">*</span>
                            </label>
                            <select name="id_besoin" id="id_besoin" class="form-select form-control-custom" required disabled>
                                <option value="">-- Sélectionner d'abord une catégorie --</option>
                            </select>
                            <small class="form-text text-muted">Choisissez le besoin auquel ce don est destiné</small>
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
                                   placeholder="Entrez la quantité"
                                   min="1"
                                   required>
                            <small class="form-text text-muted">Indiquez la quantité offerte</small>
                        </div>

                        <!-- Source -->
                        <div class="mb-4">
                            <label for="source" class="form-label">
                                <i class="fas fa-user-tag me-2"></i> Source <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="source" 
                                   id="source" 
                                   class="form-control form-control-custom" 
                                   placeholder="Nom du donateur ou de l'organisation"
                                   required>
                            <small class="form-text text-muted">Nom du donateur ou de l'organisation donatrice</small>
                        </div>

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i> Date et Heure <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="date" 
                                   id="date" 
                                   class="form-control form-control-custom" 
                                   required>
                            <small class="form-text text-muted">Date et heure de réception du don</small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Annuler
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

<script>
    // Set default date to now
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dateInput.value = now.toISOString().slice(0, 16);
    });

    // Charger les besoins selon la catégorie sélectionnée
    document.addEventListener('DOMContentLoaded', function() {
        const categorieSelect = document.getElementById('id_categorie');
        const besoinSelect = document.getElementById('id_besoin');

        if (!categorieSelect) return;

        categorieSelect.addEventListener('change', function() {
            const id = this.value;
            besoinSelect.disabled = true;
            besoinSelect.innerHTML = '<option>Chargement...</option>';

            if (!id) {
                besoinSelect.innerHTML = '<option value="">-- Sélectionner d\'abord une catégorie --</option>';
                besoinSelect.disabled = true;
                return;
            }

            fetch(`/api/besoins/categorie/${id}`)
                .then(res => res.json())
                .then(data => {
                    besoinSelect.innerHTML = '<option value="">-- Sélectionner un besoin --</option>';
                    if (Array.isArray(data)) {
                        data.forEach(b => {
                            const opt = document.createElement('option');
                            opt.value = b.id;
                            opt.textContent = b.libelle;
                            besoinSelect.appendChild(opt);
                        });
                        besoinSelect.disabled = false;
                    } else if (data.error) {
                        besoinSelect.innerHTML = `<option value="">Erreur: ${data.error}</option>`;
                        besoinSelect.disabled = true;
                    } else {
                        besoinSelect.innerHTML = '<option value="">Aucun besoin trouvé</option>';
                        besoinSelect.disabled = true;
                    }
                })
                .catch(err => {
                    console.error(err);
                    besoinSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    besoinSelect.disabled = true;
                });
        });
    });
</script>

<?php include('includes/footer.php'); ?>