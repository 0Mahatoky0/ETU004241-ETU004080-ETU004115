<?php include(BASE_URL . '/includes/header.php'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-plus-circle"></i> Saisie des Besoins par Ville</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/besoins/store">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_region" class="form-label">Région *</label>
                                <select class="form-select" id="id_region" name="id_region" required>
                                    <option value="">Sélectionner une région</option>
                                    <?php foreach ($regions as $region): ?>
                                        <option value="<?php echo $region['id']; ?>">
                                            <?php echo htmlspecialchars($region['libelle']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="id_ville" class="form-label">Ville *</label>
                                <select class="form-select" id="id_ville" name="id_ville" required disabled>
                                    <option value="">Sélectionner d'abord une région</option>
                                </select>
                            </div>
                        </div>
                        
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
                                <label for="quantite" class="form-label">Quantité Requise *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" 
                                       min="1" required placeholder="Ex: 100">
                            </div>
                            <div class="col-md-6">
                                <label for="id_status_besoin_sinistre" class="form-label">Statut</label>
                                <select class="form-select" id="id_status_besoin_sinistre" name="id_status_besoin_sinistre">
                                    <?php foreach ($status as $s): ?>
                                        <option value="<?php echo $s['id']; ?>" <?php echo $s['code'] == 'ATT' ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($s['libelle']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div id="besoin_info" class="alert alert-info" style="display: none;">
                                <strong>Information:</strong> <span id="besoin_details"></span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/besoins/liste" class="btn btn-secondary me-md-2">
                                <i class="fas fa-list"></i> Voir la Liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer le Besoin
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
    const regionSelect = document.getElementById('id_region');
    const villeSelect = document.getElementById('id_ville');
    const categorieSelect = document.getElementById('id_categorie');
    const besoinSelect = document.getElementById('id_besoin');
    const besoinInfo = document.getElementById('besoin_info');
    const besoinDetails = document.getElementById('besoin_details');
    
    // Charger les villes quand une région est sélectionnée
    regionSelect.addEventListener('change', function() {
        const idRegion = this.value;
        if (idRegion) {
            fetch(`/api/villes/region/${idRegion}`)
                .then(response => response.json())
                .then(data => {
                    villeSelect.innerHTML = '<option value="">Sélectionner une ville</option>';
                    villeSelect.disabled = false;
                    data.forEach(ville => {
                        villeSelect.innerHTML += `<option value="${ville.id}">${ville.libelle}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    villeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            villeSelect.innerHTML = '<option value="">Sélectionner d\'abord une région</option>';
            villeSelect.disabled = true;
        }
    });
    
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
    
    // Afficher les informations du besoin sélectionné
    besoinSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prix = selectedOption.getAttribute('data-prix');
        const quantite = document.getElementById('quantite').value;
        
        if (prix && quantite) {
            const total = parseFloat(prix) * parseInt(quantite);
            besoinDetails.textContent = `Prix unitaire: ${parseFloat(prix).toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})} | Total estimé: ${total.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})}`;
            besoinInfo.style.display = 'block';
        } else {
            besoinInfo.style.display = 'none';
        }
    });
    
    // Mettre à jour le total quand la quantité change
    document.getElementById('quantite').addEventListener('input', function() {
        const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
        const prix = selectedOption ? selectedOption.getAttribute('data-prix') : null;
        const quantite = this.value;
        
        if (prix && quantite) {
            const total = parseFloat(prix) * parseInt(quantite);
            besoinDetails.textContent = `Prix unitaire: ${parseFloat(prix).toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})} | Total estimé: ${total.toLocaleString('fr-MG', {style: 'currency', currency: 'MGA'})}`;
            besoinInfo.style.display = 'block';
        } else {
            besoinInfo.style.display = 'none';
        }
    });
});
</script>

<?php include(BASE_URL . '/includes/footer.php'); ?>
