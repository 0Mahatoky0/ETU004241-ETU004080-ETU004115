CREATE DATABASE IF NOT EXISTS bngrc;

use bngrc;

CREATE TABLE region (
	id INT,
	libelle VARCHAR(255)
);

CREATE TABLE ville (
	id INT,
	id_region INT,
	libelle VARCHAR(255)
);

CREATE TABLE categorie_besoin (
	id INT,
	libelle VARCHAR(255)
);

CREATE TABLE status_besoin_sinistre (
	id INT,
	code VARCHAR(10),
	libelle VARCHAR(255)
);

CREATE TABLE besoin (
	id INT,
	id_categorie INT,
	libelle VARCHAR(255),
	prix_unitaire DECIMAL(10,2)
);

CREATE TABLE besoin_sinistre (
	id INT,
	id_region INT,
	id_ville INT,
	id_besoin INT,
	id_categorie INT,
	quantite INT,
	id_status_besoin_ville INT
);

CREATE TABLE dons (
	id INT,
	id_besoin INT,
	quantite INT,
	source VARCHAR(255),
	date DATETIME
);

CREATE TABLE mvt_dons (
	id INT,
	id_dons INT,
	entrer INT,
	sortie INT,
	id_besoin_ville INT,
	date DATETIME
);
