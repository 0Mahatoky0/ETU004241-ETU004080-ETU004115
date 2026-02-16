<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-receipt"></i> Liste des Achats</h2>
                <div>
                    <a href="/achats/besoins-restants" class="btn btn-primary">
                        <i class="bi bi-cart-plus"></i> Nouvel achat
                    </a>
                    <a href="/achats/configuration" class="btn btn-outline-secondary">
                        <i class="bi bi-gear"></i> Configuration
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="/achats/liste">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="id_ville" class="form-label">Ville</label>
                                <select name="id_ville" id="id_ville" class="form-select">
                                    <option value="">Toutes les villes</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>" <?= ($id_ville_selected == $ville['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ville['libelle']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="id_categorie" class="form-label">Catégorie</label>
                                <select name="id_categorie" id="id_categorie" class="form-select">
                                    <option value="">Toutes les catégories</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?= $categorie['id'] ?>" <?= ($id_categorie_selected == $categorie['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($categorie['libelle']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_debut" class="form-label">Date début</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control" 
                                       value="<?= htmlspecialchars($date_debut_selected ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control" 
                                       value="<?= htmlspecialchars($date_fin_selected ?? '') ?>">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-funnel"></i> Filtrer
                                </button>
                                <a href="/achats/liste" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
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

            <!-- Liste des achats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Historique des Achats</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($achats)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun achat trouvé</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Catégorie</th>
                                        <th>Besoin</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant Total</th>
                                        <th>Frais</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($achats as $achat): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?></td>
                                            <td><?= htmlspecialchars($achat['region_libelle']) ?></td>
                                            <td><?= htmlspecialchars($achat['ville_libelle']) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($achat['categorie_libelle']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($achat['besoin_libelle']) ?></td>
                                            <td><?= number_format($achat['quantite']) ?></td>
                                            <td><?= number_format($achat['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                            <td>
                                                <strong class="text-success">
                                                    <?= number_format($achat['montant_total'], 2, ',', ' ') ?> Ar
                                                </strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= number_format($achat['montant_frais'], 2, ',', ' ') ?> Ar
                                                    (<?= number_format($achat['frais_percent'], 1) ?>%)
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Résumé statistique -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5><?= count($achats) ?></h5>
                                        <small>Total des achats</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5><?= number_format(array_sum(array_column($achats, 'quantite'))) ?></h5>
                                        <small>Quantité totale achetée</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5><?= number_format(array_sum(array_column($achats, 'montant_total')), 0, ',', ' ') ?></h5>
                                        <small>Montant total (Ar)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5><?= number_format(array_sum(array_column($achats, 'montant_frais')), 0, ',', ' ') ?></h5>
                                        <small>Total des frais (Ar)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Graphique des achats par ville -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Répartition des achats par ville</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $achats_par_ville = [];
                                        foreach ($achats as $achat) {
                                            if (!isset($achats_par_ville[$achat['ville_libelle']])) {
                                                $achats_par_ville[$achat['ville_libelle']] = [
                                                    'montant_total' => 0,
                                                    'quantite' => 0,
                                                    'nombre_achats' => 0
                                                ];
                                            }
                                            $achats_par_ville[$achat['ville_libelle']]['montant_total'] += $achat['montant_total'];
                                            $achats_par_ville[$achat['ville_libelle']]['quantite'] += $achat['quantite'];
                                            $achats_par_ville[$achat['ville_libelle']]['nombre_achats']++;
                                        }
                                        ?>
                                        
                                        <div class="row">
                                            <?php foreach ($achats_par_ville as $ville => $stats): ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="card border-primary">
                                                        <div class="card-body">
                                                            <h6 class="card-title"><?= htmlspecialchars($ville) ?></h6>
                                                            <div class="row text-center">
                                                                <div class="col-4">
                                                                    <small class="text-muted">Achats</small>
                                                                    <div class="fw-bold"><?= $stats['nombre_achats'] ?></div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <small class="text-muted">Quantité</small>
                                                                    <div class="fw-bold"><?= number_format($stats['quantite']) ?></div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <small class="text-muted">Montant</small>
                                                                    <div class="fw-bold text-primary"><?= number_format($stats['montant_total'], 0, ',', ' ') ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
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
