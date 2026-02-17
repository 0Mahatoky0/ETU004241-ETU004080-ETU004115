
-- Données pour la table region
INSERT INTO region (libelle) VALUES
	('Analamanga'),
	('Atsinanana'),
    ('Vakinankaratra');

-- Données pour la table ville
INSERT INTO ville (id_region, libelle) VALUES
	(1, 'Antananarivo'),
	(2, 'Toamasina'),
    (3, 'Antsirabe');

-- Données pour la table categorie_besoin
INSERT INTO categorie_besoin (libelle) VALUES
	('Nature'),
	('Materiaux'),
    ('Argent');

-- Données pour la table status_besoin_sinistre
INSERT INTO status_besoin_sinistre (code, libelle) VALUES
	('ACP', 'Accepte'),
	('ATT', 'En attente');

-- Données pour la table besoin
INSERT INTO besoin (id_categorie, libelle, prix_unitaire) VALUES
	(1,'Riz', 2500.00),
    (1,'Huile', 5000.00),
    (2,'Bois', 15000.00),
    (2,'Tôle', 20000.00),
	(2,'Clou', 50000.00);

-- Données pour la table besoin_sinistre (SANS id_categorie)
INSERT INTO besoin_sinistre (id, id_ville, id_besoin, quantite, id_status_besoin_sinistre) VALUES
	(1, 1, 1, 100, 1),
	(2, 2, 2, 10, 2);

-- Données pour la table dons
INSERT INTO dons (id, id_besoin, quantite, source, date) VALUES
	(1, 1, 50, 'ONG A', '2026-02-15 10:00:00'),
	(2, 2, 5, 'Particulier B', '2026-02-16 09:30:00');

