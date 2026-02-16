# BNGRC - Système de Gestion des Besoins et Dons

## Fonctionnalités Implémentées

### MODULE D'ACHAT DES BESOINS VIA DONS EN ARGENT

#### Base de Données
- Table configuration  : Stockage des frais d'achat (défaut: 10%)
- Table `achat` : Enregistrement complet des transactions d'achat
  - id, id_besoin, id_ville, quantité, prix_unitaire
  - montant_brut, frais_percent, montant_frais, montant_total
  - date_achat, created_at
- Index optimisés : idx_achat_besoin, idx_achat_ville, idx_achat_date

#### Pages Web Créées

##### 1. Liste des Besoins Disponibles (`/achats/besoins-restants`)
- Affichage des besoins restants par ville
- Filtre par ville dynamique
- Bouton "Acheter" pour chaque besoin
- Résumé statistique en temps réel
- Désactivation automatique si quantité = 0

##### 2. Page de Simulation (`/achats/simulation`)
- Calcul dynamique des montants
- Affichage détaillé : Montant brut + Frais + Total
- Simulation en temps réel sans insertion en base
- Validation des quantités disponibles
- Interface interactive avec JavaScript

##### 3. Liste des Achats (`/achats/liste`)
- Historique complet des achats effectués
- Filtres multiples : par ville, date, catégorie
- Statistiques : total achats, quantités, montants
- Répartition visuelle par ville
- Tableaux de bord récapitulatifs

##### 4. Configuration des Frais (`/achats/configuration`)
- Modification du pourcentage des frais (0-100%)
- Simulation en temps réel de l'impact
- Historique des modifications
- Validation des entrées utilisateur

#### Logique Métier

##### Calcul Automatisé
```
montant_brut = quantite × prix_unitaire
montant_frais = montant_brut × frais_percent / 100
montant_total = montant_brut + montant_frais
```

##### Vérifications Avant Achat
- Quantité demandée ≤ quantité restante
- Besoin existe dans les dons restants
- Ville correcte et valide
- Valeurs numériques positives

##### Sécurité & Transactions
- Transactions SQL (BEGIN/COMMIT/ROLLBACK)
- Protection contre double validation
- Validation des entrées utilisateur
- Gestion complète des erreurs

#### Fonctionnalités Avancées

##### Simulation API
- Endpoint `/achats/api/simuler` (POST)
- Calcul instantané via AJAX
- Compatible mobile et desktop
- Interface responsive

##### Tableaux de Bord
- Statistiques en temps réel
- Répartition par ville
- Total des frais collectés
- Historique des transactions

##### Expérience Utilisateur
- Messages de succès/erreur clairs
- Chargements dynamiques
- Design responsive Bootstrap
- Interface moderne et intuitive

#### Architecture Technique

##### Fichiers Créés
```
app/
├── controllers/AchatController.php     # Logique métier achats
├── models/BNGRCModel.php              # Méthodes BD mises à jour
└── views/achats/
    ├── besoins_restants.php           # Liste besoins disponibles
    ├── simulation.php                 # Page simulation
    ├── liste.php                      # Historique achats
    └── configuration.php              # Configuration frais

sql/
└── 2026_02_16-03-achat-tables-final.sql # Script création tables

app/config/
└── routes.php                         # Routes GET/POST ajoutées
```

##### Routes Implémentées
- `GET /achats/besoins-restants` - Liste besoins
- `GET /achats/simulation` - Page simulation
- `POST /achats/api/simuler` - API simulation
- `POST /achats/valider` - Validation achat
- `GET /achats/liste` - Historique achats
- `GET/POST /achats/configuration` - Configuration frais

#### Processus Complet

1. Consultation → Liste des besoins disponibles
2. Sélection → Clic sur "Acheter" 
3. Simulation → Calcul des montants avec frais
4. Validation → Vérifications automatiques
5. Enregistrement → Transaction sécurisée en base
6. Mise à jour → Déduction quantité restante
7. Confirmation → Message de succès

#### Points Techniques

- Performance : Requêtes SQL optimisées
- Sécurité : Transactions et validation
- Scalabilité : Architecture modulaire
- Maintenance : Code commenté et structuré
- UX : Interface moderne et responsive

---

## Prochaines Étapes Possibles

### Améliorations Optionnelles
- Export PDF/Excel des achats
- Graphiques avancés (Chart.js)
- Notifications par email
- Application mobile PWA
- Synchronisation multi-utilisateurs

### Sécurité Avancée
- Gestion des rôles et permissions
- Journal d'audit complet
- Authentification renforcée
- Validation CSRF




