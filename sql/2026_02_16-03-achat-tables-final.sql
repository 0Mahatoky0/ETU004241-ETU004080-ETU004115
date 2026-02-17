USE bngrc;

-- Corriger les contraintes PRIMARY KEY manquantes
ALTER TABLE besoin MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE ville MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE region MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE categorie_besoin MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE status_besoin_sinistre MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

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

-- Index pour optimiser les performances
CREATE INDEX IF NOT EXISTS idx_achat_besoin ON achat(id_besoin);
CREATE INDEX IF NOT EXISTS idx_achat_ville ON achat(id_ville);
CREATE INDEX IF NOT EXISTS idx_achat_date ON achat(date_achat);

-- Afficher un message de confirmation
SELECT 'Tables d\'achat créées avec succès!' as message;
