-- Migration pour ajouter les fonctionnalités de distribution
USE bngrc;

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
