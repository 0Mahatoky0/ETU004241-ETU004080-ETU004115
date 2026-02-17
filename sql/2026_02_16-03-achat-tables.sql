USE bngrc;

-- Table configuration pour les frais d'achat
CREATE TABLE configuration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_achat_percent DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion de la configuration par défaut
INSERT INTO configuration (frais_achat_percent) VALUES (10.00);

-- Table achats
CREATE TABLE achat (
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
CREATE INDEX idx_achat_besoin ON achat(id_besoin);
CREATE INDEX idx_achat_ville ON achat(id_ville);
CREATE INDEX idx_achat_date ON achat(date_achat);
