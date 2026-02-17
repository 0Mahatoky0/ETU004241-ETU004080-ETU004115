# BNGRC - Todo Liste des Fonctionnalités

## Base de Données
- [x] Tables
    - [x] Region (id, libelle)
    - [x] Ville (id, id_region, libelle)
    - [x] categorie_besoin (id, libelle) 
    - [x] status_besoin_sinistre(id, code, libelle) (ACP, ATT)
    - [x] Besoin (id, id_categorie, libelle, prix_unitaire)
    - [x] Besoin_sinistre(id, id_region, id_ville, id_besoin, id_categorie, quantite, id_status_besoin_ville)
    - [x] Dons (id, id_besoin, quantite, source, date)
    - [x] Mvt_Dons (id, id_dons, entrer, sortie, id_besoin_ville, date)

## Gestion des Besoins
- [x] Saisie des besoins par ville
    - [x] Formulaire de saisie avec sélection région/ville/catégorie/besoin
    - [x] Validation des données (quantité > 0)
    - [x] Insertion en base de données
    - [x] Affichage de la liste des besoins
- [x] Liste des besoins par ville
- [x] Détails d'une ville (besoins + dons)
- [x] API Ajax pour charger les villes par région
- [x] API Ajax pour charger les besoins par catégorie

## Gestion des Dons
- [x] Saisie des dons
    - [x] Formulaire de saisie avec catégorie/besoin/quantité/source
    - [x] Validation des données
    - [x] Création automatique du mouvement d'entrée
- [x] Liste des dons
- [x] Achat avec dons en argent
    - [x] Vérification du montant disponible
    - [x] Calcul du coût total
    - [x] Création du don et mouvement d'entrée
- [x] API Ajax pour le stock disponible

## Dispatch/Distribution
- [x] Simulation de dispatch
    - [x] Affichage du stock disponible
    - [x] Affichage des besoins non satisfaits
    - [x] Simulation par besoin
- [x] État des besoins
    - [x] Liste des besoins satisfaits
    - [x] Liste des besoins non satisfaits
    - [x] Détails par besoin
- [x] API Ajax pour preview du dispatch
- [x] Processus de dispatch (simulation)

## Tableau de Bord
- [x] Dashboard principal
    - [x] Statistiques générales (total besoins, total dons)
    - [x] Stock disponible
    - [x] Besoins non satisfaits
    - [x] Graphiques et statistiques par ville
- [x] API pour les graphiques
- [x] Service Dashboard pour les calculs

## Fonctionnalités en Cours
- [ ] Page de récapitulation avec Ajax
    - [ ] Afficher les besoins totaux en montant
    - [ ] Afficher les besoins satisfaits en montant  
    - [ ] Afficher le montant des besoins restants
    - [ ] Bouton d'actualisation Ajax

## Routes et Contrôleurs
- [x] BesoinController (saisie, liste, détails, API)
- [x] DonController (saisie, liste, achat, API)
- [x] DispatchController (simulation, état, API)
- [x] DashboardController (tableau de bord, API)
- [x] Configuration des routes dans routes.php
- [ ] Route pour la page de récapitulation

## Vues et Interface
- [x] Interface Bootstrap responsive
- [x] Formulaires de saisie
- [x] Tableaux de données
- [x] Dashboard avec graphiques
- [ ] Vue récapitulative avec Ajax

## Services et Modèles
- [x] BNGRCModel (accès aux données)
- [x] DashboardService (calculs statistiques)
- [x] Validation des formulaires
- [ ] Service pour calculs récapitulatifs

---

# MODULE D'ACHAT DES BESOINS VIA DONS EN ARGENT

## Base de Données
- [x] Table configuration : Stockage des frais d'achat (défaut: 10%)
- [x] Table `achat` : Enregistrement complet des transactions d'achat
  - [x] id, id_besoin, id_ville, quantité, prix_unitaire
  - [x] montant_brut, frais_percent, montant_frais, montant_total
  - [x] date_achat, created_at
- [x] Index optimisés : idx_achat_besoin, idx_achat_ville, idx_achat_date

## Pages Web Créées
- [x] Liste des Besoins Disponibles (`/achats/besoins-restants`)
  - [x] Affichage des besoins restants par ville
  - [x] Filtre par ville dynamique
  - [x] Bouton "Acheter" pour chaque besoin
  - [x] Résumé statistique en temps réel
  - [x] Désactivation automatique si quantité = 0
- [x] Page de Simulation (`/achats/simulation`)
  - [x] Calcul dynamique des montants
  - [x] Affichage détaillé : Montant brut + Frais + Total
  - [x] Simulation en temps réel sans insertion en base
  - [x] Validation des quantités disponibles
  - [x] Interface interactive avec JavaScript
- [x] Liste des Achats (`/achats/liste`)
  - [x] Historique complet des achats effectués
  - [x] Filtres multiples : par ville, date, catégorie
  - [x] Statistiques : total achats, quantités, montants
  - [x] Répartition visuelle par ville
  - [x] Tableaux de bord récapitulatifs
- [x] Configuration des Frais (`/achats/configuration`)
  - [x] Modification du pourcentage des frais (0-100%)
  - [x] Simulation en temps réel de l'impact
  - [x] Historique des modifications
  - [x] Validation des entrées utilisateur

## Logique Métier
- [x] Calcul Automatisé : montant_brut = quantite × prix_unitaire, montant_frais = montant_brut × frais_percent / 100, montant_total = montant_brut + montant_frais
- [x] Vérifications Avant Achat
  - [x] Quantité demandée ≤ quantité restante
  - [x] Besoin existe dans les dons restants
  - [x] Ville correcte et valide
  - [x] Valeurs numériques positives
- [x] Sécurité & Transactions
  - [x] Transactions SQL (BEGIN/COMMIT/ROLLBACK)
  - [x] Protection contre double validation
  - [x] Validation des entrées utilisateur
  - [x] Gestion complète des erreurs

## Fonctionnalités Avancées
- [x] Simulation API : Endpoint `/achats/api/simuler` (POST)
  - [x] Calcul instantané via AJAX
  - [x] Compatible mobile et desktop
  - [x] Interface responsive
- [x] Tableaux de Bord
  - [x] Statistiques en temps réel
  - [x] Répartition par ville
  - [x] Total des frais collectés
  - [x] Historique des transactions
- [x] Expérience Utilisateur
  - [x] Messages de succès/erreur clairs
  - [x] Chargements dynamiques
  - [x] Design responsive Bootstrap
  - [x] Interface moderne et intuitive

## Architecture Technique
- [x] Fichiers Créés
  - [x] app/controllers/AchatController.php - Logique métier achats
  - [x] app/models/BNGRCModel.php - Méthodes BD mises à jour
  - [x] app/views/achats/besoins_restants.php - Liste besoins disponibles
  - [x] app/views/achats/simulation.php - Page simulation
  - [x] app/views/achats/liste.php - Historique achats
  - [x] app/views/achats/configuration.php - Configuration frais
  - [x] sql/2026_02_16-03-achat-tables-final.sql - Script création tables
  - [x] app/config/routes.php - Routes GET/POST ajoutées
- [x] Routes Implémentées
  - [x] GET /achats/besoins-restants - Liste besoins
  - [x] GET /achats/simulation - Page simulation
  - [x] POST /achats/api/simuler - API simulation
  - [x] POST /achats/valider - Validation achat
  - [x] GET /achats/liste - Historique achats
  - [x] GET/POST /achats/configuration - Configuration frais

## Processus Complet
1. [x] Consultation → Liste des besoins disponibles
2. [x] Sélection → Clic sur "Acheter"
3. [x] Simulation → Calcul des montants avec frais
4. [x] Validation → Vérifications automatiques
5. [x] Enregistrement → Transaction sécurisée en base
6. [x] Mise à jour → Déduction quantité restante
7. [x] Confirmation → Message de succès

## Points Techniques
- [x] Performance : Requêtes SQL optimisées
- [x] Sécurité : Transactions et validation
- [x] Scalabilité : Architecture modulaire
- [x] Maintenance : Code commenté et structuré
- [x] UX : Interface moderne et responsive

---

## Gestion de distribution 

-I-Arrangement par ordre croissante c'est a dire on distribue les dons pour la ville ayant le besoin le plus petit par exemple : tana a besoin de 3materiaux , majunga 5 materiaux et tamatave 10 materiaux donc on distribue le dons a tana en premier et il y peux que les autre ne recevront plus de dons si elle est terminer a majunga par exemple 
-pour l'affichage on utilise un boutton pour distribuer et un bouton pour reinitialiser c'est a dire les dons revient a la bngrc les donnees redevienne comme l'insertion au depart 
  
✅ TO-DO FINAL COMPLET
🗄 1️⃣ Base de données

 Vérifier quantite_initiale dans besoin

 Vérifier quantite_initiale dans stock_bngrc

📝 2️⃣ Insertion via formulaire

 Insérer quantite_restante

 Insérer aussi quantite_initiale

 Tester insertion

🔄 3️⃣ Bouton DISTRIBUER (Simulation)

 Trier besoins par ASC

 Calculer répartition

 Stocker résultat en session (ex: $_SESSION['distribution'])

 Afficher résultat

🟢 4️⃣ Bouton VALIDER (Confirmation réelle)

 Lire les données simulées

 Mettre à jour la table besoin

 Mettre à jour le stock

 Utiliser TRANSACTION

 Faire COMMIT

🔴 5️⃣ Bouton RÉINITIALISER

 UPDATE besoin SET quantite_restante = quantite_initiale

 UPDATE stock_bngrc SET quantite = quantite_initiale

 Supprimer session distribution

📊 6️⃣ Page Récap

 Afficher :

Besoins totaux

Besoins satisfaits

Besoins restants

Stock restant

 Ajouter bouton Actualiser (si demandé)

🧠 Logique complète du système

Insertion besoin

Cliquer Distribuer → simulation

Cliquer Valider → enregistrement officiel

Cliquer Réinitialiser → retour à l’état initial        

II- la deuxieme fonctionnalite pour la distribution
on fais le partage par proportionnalite c'est a dire : si on a 6 materiaux on distribue toujours par ordre croissante et pour la calcul on fait : 3/6 pour tana , 5/6 majunga et 10/6 tamatave et si il y a des restes on ne les distribue plus 
            

