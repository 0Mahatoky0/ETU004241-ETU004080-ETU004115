CREATE DATABASE IF NOT EXISTS bngrc;

use bngrc;

CREATE TABLE region (
	id INT,
	libelle VARCHAR(255)
);

CREATE TABLE ville (
	id INT,
	id_region INT,
	libelle VARCHAR(255),
	FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE categorie_besoin (
	id INT,
	code VARCHAR(10),
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
	prix_unitaire DECIMAL(10,2),
	FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id)
);

CREATE TABLE besoin_sinistre (
	id INT,
	id_region INT,
	id_ville INT,
	id_besoin INT,
	id_categorie INT,
	quantite INT,
	id_status_besoin_sinistre INT,
	FOREIGN KEY (id_region) REFERENCES region(id),
	FOREIGN KEY (id_ville) REFERENCES ville(id),
	FOREIGN KEY (id_besoin) REFERENCES besoin(id),
	FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id),
	FOREIGN KEY (id_status_besoin_sinistre) REFERENCES status_besoin_sinistre(id)
);

CREATE TABLE dons (
	id INT,
	id_besoin INT,
	quantite INT,
	source VARCHAR(255),
	date DATETIME,
	FOREIGN KEY (id_besoin) REFERENCES besoin(id)
);

CREATE TABLE mvt_dons (
	id INT,
	id_dons INT,
	entrer INT,
	sortie INT,
	id_besoin_sinistre INT,
	date DATETIME,
	FOREIGN KEY (id_dons) REFERENCES dons(id),
	FOREIGN KEY (id_besoin_sinistre) REFERENCES besoin_sinistre(id)
);
