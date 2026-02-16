<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="fas fa-shopping-cart"></i> Acheter avec les Dons en Argent</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Utilisez cette fonction pour acheter des biens nécessaires en utilisant les dons en argent reçus.
                    </div>
                    
                    <form method="POST" action="/dons/process-achat">
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
                                <label for="id_besoin" class="form-label">Bien à Acheter *</label>
                                <select class="form-select" id="id_besoin" name="id_besoin" required disabled>
                                    <option value="">Sélectionner d'abord une catégorie</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantite" class="form-label">Quantité à Acheter *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" 
                                       min="1" required placeholder="Ex: 25">
                            </div>
                            <div class="col-md-6">
                                <label for="montant_disponible" class="form-label">Montant Disponible (MGA) *</label>
                                <input type="number" class="form-control" id="montant_disponible" name="montant_disponible" 
                                       min="0" step="0.01" required placeholder="Ex: 100000">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div id="achat_info" class="alert alert-warning" style="display: none;">
                                <strong>Calcul d'achat:</strong> <span id="achat_details"></span>
                            </div>
                            <div id="stock_info" class="alert alert-success" style="display: none;">
                                <strong>Stock actuel:</strong> <span id="stock_details"></span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/dons/liste" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-shopping-cart"></i> Confirmer l'Achat
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
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5><i class="fas fa-warehouse"></i> Stock Actuel Disponible</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Bien</th>
                                        <th>Prix Unitaire</th>
                                        <th>Quantité Disponible</th>
                                        <th>Valeur Totale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stock_disponible as $stock): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars($stock['categorie_libelle']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($stock['besoin_libelle']); ?></td>
                                            <td><?php echo number_format($stock['prix_unitaire'], 2); ?> MGA</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo number_format($stock['stock_disponible']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?php echo number_format($stock['stock_disponible'] * $stock['prix_unitaire'], 2); ?> MGA</strong>
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
                    achatInfo.className = 'alert alert-success';
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

<?php include('includes/footer.php'); ?>
