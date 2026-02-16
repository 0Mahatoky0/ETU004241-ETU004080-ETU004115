<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-hand-holding-heart"></i> Saisie des Dons</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/dons/store">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_categorie" class="form-label">Catégorie de Besoin *</label>
                                <select class="form-select" id="id_categorie" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?php echo $categorie['id']; ?>">
                                            <?php echo htmlspecialchars($categorie['libelle']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="id_besoin" class="form-label">Besoin Spécifique *</label>
                                <select class="form-select" id="id_besoin" name="id_besoin" required disabled>
                                    <option value="">Sélectionner d'abord une catégorie</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantite" class="form-label">Quantité Donnée *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" 
                                       min="1" required placeholder="Ex: 50">
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label">Source du Don *</label>
                                <input type="text" class="form-control" id="source" name="source" 
                                       required placeholder="Ex: ONG ABC, Particulier XYZ">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div id="don_info" class="alert alert-info" style="display: none;">
                                <strong>Information:</strong> <span id="don_details"></span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/dons/liste" class="btn btn-secondary me-md-2">
                                <i class="fas fa-list"></i> Voir la Liste
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Enregistrer le Don
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('id_categorie');
    const besoinSelect = document.getElementById('id_besoin');
    const quantiteInput = document.getElementById('quantite');
    const donInfo = document.getElementById('don_info');
    const donDetails = document.getElementById('don_details');
    
    // Charger les besoins quand une catégorie est sélectionnée
    categorieSelect.addEventListener('change', function() {
        const idCategorie = this.value;
        if (idCategorie) {
            fetch(`/api/besoins/categorie/${idCategorie}`)
                .then(response => response.json())
                .then(data => {
                    besoinSelect.innerHTML = '<option value="">Sélectionner un besoin</option>';
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
        const source = document.getElementById('source').value;
        
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
    document.getElementById('source').addEventListener('input', updateDonInfo);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
