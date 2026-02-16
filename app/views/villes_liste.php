<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Villes - Gestion des Dons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .city-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .city-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .stats-card {
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
        }
        .progress-custom {
            height: 8px;
            border-radius: 4px;
        }
        .badge-custom {
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-geo-alt-fill"></i> Liste des Villes</h1>
                    <p class="mb-0">Consultez les besoins et dons pour chaque ville</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="/dashboard" class="btn btn-light">
                        <i class="bi bi-speedometer2"></i> Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Statistiques générales -->
        <section class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt-fill fs-2"></i>
                        <h3 class="card-title mt-2"><?= htmlspecialchars($stats['total_villes']) ?></h3>
                        <p class="card-text">Villes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                        <h3 class="card-title mt-2"><?= htmlspecialchars($stats['total_besoins']) ?></h3>
                        <p class="card-text">Besoins totaux</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-gift-fill fs-2"></i>
                        <h3 class="card-title mt-2"><?= htmlspecialchars($stats['total_dons']) ?></h3>
                        <p class="card-text">Dons reçus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                        <h3 class="card-title mt-2"><?= htmlspecialchars($stats['total_distribue']) ?></h3>
                        <p class="card-text">Unités distribuées</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filtres et recherche -->
        <section class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="searchVille" class="form-control" placeholder="Rechercher une ville...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="filterRegion" class="form-select">
                                    <option value="">Toutes les régions</option>
                                    <!-- Les régions seront ajoutées dynamiquement -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="sortBy" class="form-select">
                                    <option value="name">Nom de ville</option>
                                    <option value="needs">Besoins croissants</option>
                                    <option value="donations">Dons croissants</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Liste des villes -->
        <section class="row" id="villesContainer">
            <?php foreach ($villes as $ville): ?>
                <div class="col-md-6 col-lg-4 mb-4 ville-item" 
                     data-ville="<?= htmlspecialchars($ville['libelle']) ?>" 
                     data-region="<?= htmlspecialchars($ville['region']) ?>">
                    <div class="card city-card h-100" onclick="window.location.href='/villes/<?= $ville['id'] ?>'">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt"></i> 
                                <?= htmlspecialchars($ville['libelle']) ?>
                            </h5>
                            <small><?= htmlspecialchars($ville['region']) ?></small>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="text-primary">
                                        <i class="bi bi-exclamation-triangle fs-4"></i>
                                        <div class="fw-bold"><?= $ville['total_besoins'] ?></div>
                                        <small class="text-muted">Besoins</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-success">
                                        <i class="bi bi-gift fs-4"></i>
                                        <div class="fw-bold"><?= $ville['total_dons'] ?></div>
                                        <small class="text-muted">Dons</small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($ville['total_besoins'] > 0): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Taux de couverture</small>
                                        <small class="fw-bold">
                                            <?= round(($ville['total_dons'] / $ville['total_besoins']) * 100, 1) ?>%
                                        </small>
                                    </div>
                                    <div class="progress progress-custom">
                                        <?php 
                                        $percentage = min(($ville['total_dons'] / $ville['total_besoins']) * 100, 100);
                                        $colorClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                                        ?>
                                        <div class="progress-bar <?= $colorClass ?>" 
                                             style="width: <?= $percentage ?>%" 
                                             role="progressbar"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($ville['besoins'])): ?>
                                <div class="mb-2">
                                    <small class="text-muted">Besoins principaux:</small>
                                    <div>
                                        <?php 
                                        $topBesoins = array_slice($ville['besoins'], 0, 2);
                                        foreach ($topBesoins as $besoin): 
                                        ?>
                                            <span class="badge bg-light text-dark badge-custom me-1">
                                                <?= htmlspecialchars($besoin['libelle']) ?> (<?= $besoin['quantite'] ?>)
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="bi bi-arrow-right-circle"></i> Cliquez pour voir les détails
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Message si aucune ville -->
        <?php if (empty($villes)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <h4 class="mt-3">Aucune ville trouvée</h4>
                <p class="text-muted">Commencez par ajouter des villes dans le système.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-c-circle"></i> 2026 - Système de Gestion des Dons et Besoins
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonctionnalités de recherche et filtrage
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchVille');
            const regionFilter = document.getElementById('filterRegion');
            const sortBy = document.getElementById('sortBy');
            const villesContainer = document.getElementById('villesContainer');
            
            // Extraire les régions uniques
            const regions = [...new Set(Array.from(document.querySelectorAll('.ville-item')).map(item => 
                item.dataset.region
            ))];
            
            // Remplir le filtre de régions
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region;
                option.textContent = region;
                regionFilter.appendChild(option);
            });
            
            // Fonction de filtrage
            function filterVilles() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedRegion = regionFilter.value;
                const sortValue = sortBy.value;
                
                let villes = Array.from(document.querySelectorAll('.ville-item'));
                
                // Filtrage
                villes.forEach(ville => {
                    const villeName = ville.dataset.ville.toLowerCase();
                    const villeRegion = ville.dataset.region;
                    
                    const matchesSearch = villeName.includes(searchTerm);
                    const matchesRegion = !selectedRegion || villeRegion === selectedRegion;
                    
                    ville.style.display = matchesSearch && matchesRegion ? '' : 'none';
                });
                
                // Tri
                const visibleVilles = villes.filter(v => v.style.display !== '');
                visibleVilles.sort((a, b) => {
                    if (sortValue === 'name') {
                        return a.dataset.ville.localeCompare(b.dataset.ville);
                    } else if (sortValue === 'needs') {
                        // Logique de tri par besoins (à implémenter avec les données)
                        return 0;
                    } else if (sortValue === 'donations') {
                        // Logique de tri par dons (à implémenter avec les données)
                        return 0;
                    }
                });
                
                // Réorganiser les éléments
                visibleVilles.forEach(ville => {
                    villesContainer.appendChild(ville);
                });
            }
            
            // Écouteurs d'événements
            searchInput.addEventListener('input', filterVilles);
            regionFilter.addEventListener('change', filterVilles);
            sortBy.addEventListener('change', filterVilles);
        });
    </script>
</body>
</html>
