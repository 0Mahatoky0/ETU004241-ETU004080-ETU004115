-- Données pour la table region
INSERT INTO region (id, libelle) VALUES
	(1, 'Analamanga'),
	(2, 'Atsinanana');

-- Données pour la table ville
INSERT INTO ville (id, id_region, libelle) VALUES
	(1, 1, 'Antananarivo'),
	(2, 2, 'Toamasina');

-- Données pour la table categorie_besoin
INSERT INTO categorie_besoin (id, libelle) VALUES
	(1, 'Nature'),
	(2, 'Matériaux');

-- Données pour la table status_besoin_sinistre
INSERT INTO status_besoin_sinistre (id, code, libelle) VALUES
	(1, 'ACP', 'Accepte'),
	(2, 'ATT', 'En attente');

-- Données pour la table besoin
INSERT INTO besoin (id, id_categorie, libelle, prix_unitaire) VALUES
	(1, 1, 'Riz', 2500.00),
	(2, 2, 'Clou', 50000.00);

-- Données pour la table besoin_sinistre (SANS id_categorie)
INSERT INTO besoin_sinistre (id, id_region, id_ville, id_besoin, quantite, id_status_besoin_sinistre) VALUES
	(1, 1, 1, 1, 100, 1),
	(2, 2, 2, 2, 10, 2);

-- Données pour la table dons
INSERT INTO dons (id, id_besoin, quantite, source, date) VALUES
	(1, 1, 50, 'ONG A', '2026-02-15 10:00:00'),
	(2, 2, 5, 'Particulier B', '2026-02-16 09:30:00');

-- Données pour la table mvt_dons
INSERT INTO mvt_dons (id, id_dons, entrer, sortie, id_besoin_sinistre, date) VALUES
	(1, 1, 50, 0, 1, '2026-02-15 11:00:00'),
	(2, 2, 5, 0, 2, '2026-02-16 10:00:00');