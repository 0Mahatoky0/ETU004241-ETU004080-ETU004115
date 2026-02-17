DROP DATABASE IF EXISTS bngrc;

CREATE DATABASE IF NOT EXISTS bngrc;

USE bngrc;

CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255)
);

CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT NOT NULL,
    libelle VARCHAR(255),
    FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE categorie_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10),
    libelle VARCHAR(255)
);

CREATE TABLE status_besoin_sinistre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10),
    libelle VARCHAR(255)
);


CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    libelle VARCHAR(255),
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id)
);


CREATE TABLE besoin_sinistre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT,
    montant DECIMAL(10,2),
    id_status_besoin_sinistre INT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id),
    FOREIGN KEY (id_status_besoin_sinistre) REFERENCES status_besoin_sinistre(id)
);


CREATE TABLE dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite INT,
    source VARCHAR(255),
    date DATETIME,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
);

ALTER TABLE dons ADD COLUMN montant DECIMAL(10,2);


CREATE TABLE mvt_dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dons INT NOT NULL,
    entrer INT,
    sortie INT,
    id_besoin_sinistre INT,
    date DATETIME,
    FOREIGN KEY (id_dons) REFERENCES dons(id),
    FOREIGN KEY (id_besoin_sinistre) REFERENCES besoin_sinistre(id)
);

CREATE TABLE achat_dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dons INT NOT NULL,
    quantite INT,
    montant DECIMAL(10,2),
    date DATETIME,
    FOREIGN KEY (id_dons) REFERENCES dons(id)
);

USE bngrc;

-USE bngrc;

-- Vérifier si les tables de base existent, sinon les créer
-- (au cas où le script 01 n'a pas été exécuté)

-- Table configuration pour les frais d'achat
CREATE TABLE IF NOT EXISTS configuration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_achat_percent DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insérer la configuration par défaut seulement si elle n'existe pas
INSERT IGNORE INTO configuration (frais_achat_percent) VALUES (10.00);

-- Table achats
CREATE TABLE IF NOT EXISTS achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    id_ville INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    montant_brut DECIMAL(10,2) NOT NULL,
    frais_percent DECIMAL(5,2) NOT NULL,
    montant_frais DECIMAL(10,2) NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id),
    FOREIGN KEY (id_ville) REFERENCES ville(id)
);

-- Index pour optimiser les performances (créés seulement s'ils n'existent pas)
CREATE INDEX IF NOT EXISTS idx_achat_besoin ON achat(id_besoin);
CREATE INDEX IF NOT EXISTS idx_achat_ville ON achat(id_ville);
CREATE INDEX IF NOT EXISTS idx_achat_date ON achat(date_achat);

-- Afficher un message de confirmation
SELECT 'Tables d\'achat créées avec succès!' as message;


-- 1️⃣ Ajouter les champs quantite_initiale et quantite_restante à la table besoin_sinistre
ALTER TABLE besoin_sinistre 
ADD COLUMN quantite_initiale INT NOT NULL DEFAULT 0 AFTER quantite,
ADD COLUMN quantite_restante INT NOT NULL DEFAULT 0 AFTER quantite_initiale;

-- Mettre à jour les données existantes
UPDATE besoin_sinistre 
SET quantite_initiale = quantite, 
    quantite_restante = quantite;

-- 2️⃣ Créer la table stock_bngrc
CREATE TABLE stock_bngrc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite_initiale INT NOT NULL DEFAULT 0,
    quantite INT NOT NULL DEFAULT 0,
    date_maj DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id),
    UNIQUE KEY unique_besoin_stock (id_besoin)
);

-- 3️⃣ Initialiser le stock avec les dons existants
INSERT INTO stock_bngrc (id_besoin, quantite_initiale, quantite)
SELECT 
    d.id_besoin,
    COALESCE(SUM(d.quantite), 0) as quantite_initiale,
    COALESCE(SUM(d.quantite), 0) as quantite
FROM dons d
GROUP BY d.id_besoin
ON DUPLICATE KEY UPDATE
    quantite_initiale = VALUES(quantite_initiale),
    quantite = VALUES(quantite);

-- 4️⃣ Index pour optimiser les performances
CREATE INDEX idx_besoin_sinistre_restante ON besoin_sinistre(quantite_restante);
CREATE INDEX idx_stock_bngrc_quantite ON stock_bngrc(quantite);
