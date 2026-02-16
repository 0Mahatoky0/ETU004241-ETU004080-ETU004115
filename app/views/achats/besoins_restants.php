<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-cart-plus"></i> Achats des Besoins</h2>
                <div>
                    <a href="/achats/liste" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul"></i> Voir les achats
                    </a>
                    <a href="/achats/configuration" class="btn btn-outline-secondary">
                        <i class="bi bi-gear"></i> Configuration
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/achats/besoins-restants">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="id_ville" class="form-label">Filtrer par ville</label>
                                <select name="id_ville" id="id_ville" class="form-select">
                                    <option value="">Toutes les villes</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>" <?= ($id_ville_selected == $ville['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ville['libelle']) ?> (<?= htmlspecialchars($ville['region_libelle']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Liste des besoins restants -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Besoins Disponibles à l'Achat</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($besoins)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun besoin disponible à l'achat</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Catégorie</th>
                                        <th>Besoin</th>
                                        <th>Quantité Restante</th>
                                        <th>Prix Unitaire</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins as $besoin): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($besoin['region_libelle']) ?></td>
                                            <td><?= htmlspecialchars($besoin['ville_libelle']) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($besoin['categorie_libelle']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($besoin['besoin_libelle']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($besoin['quantite_restante'] > 10) ? 'success' : 'warning' ?>">
                                                    <?= number_format($besoin['quantite_restante']) ?>
                                                </span>
                                            </td>
                                            <td><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                            <td>
                                                <a href="/achats/simulation?id=<?= $besoin['besoin_sinistre_id'] ?>" 
                                                   class="btn btn-<?= ($besoin['quantite_restante'] > 0) ? 'primary' : 'secondary' ?> btn-sm"
                                                   <?= ($besoin['quantite_restante'] <= 0) ? 'disabled' : '' ?>>
                                                    <i class="bi bi-cart-plus"></i> Acheter
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Résumé -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5><?= count($besoins) ?></h5>
                                        <small>Besoins disponibles</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5><?= number_format(array_sum(array_column($besoins, 'quantite_restante'))) ?></h5>
                                        <small>Quantité totale restante</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5><?= count(array_unique(array_column($besoins, 'ville_libelle'))) ?></h5>
                                        <small>Villes concernées</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5><?= number_format(array_sum(array_map(function($b) { return $b['quantite_restante'] * $b['prix_unitaire']; }, $besoins)), 0, ',', ' ') ?></h5>
                                        <small>Valeur totale (Ar)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
