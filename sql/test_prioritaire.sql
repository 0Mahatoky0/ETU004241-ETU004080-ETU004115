-- Fichier SQL pour insérer des données de test pour la distribution prioritaire
-- S'assurer que les tables sont bien configurées pour la distribution

USE bngrc;

-- 1️⃣ Réinitialiser complètement les données existantes
UPDATE besoin_sinistre SET quantite_restante = quantite_initiale;
UPDATE stock_bngrc SET quantite = quantite_initiale;

-- 2️⃣ Insérer des besoins de test pour distribution PRIORITAIRE
-- Les besoins sont triés par quantité croissante pour tester la logique prioritaire
INSERT INTO besoin_sinistre (id_ville, id_besoin, quantite, quantite_initiale, quantite_restante, id_status_besoin_sinistre, date) VALUES
-- Très petits besoins (devraient être 100% satisfaits)
(1, 1, 3, 3, 3, 2, '2026-02-17 09:00:00'),  -- Riz - Très petit
(2, 2, 4, 4, 4, 2, '2026-02-17 09:00:00'),  -- Clou - Très petit
(1, 1, 5, 5, 5, 2, '2026-02-17 09:00:00'),  -- Riz - Petit
(2, 2, 6, 6, 6, 2, '2026-02-17 09:00:00'),  -- Clou - Petit
(1, 1, 8, 8, 8, 2, '2026-02-17 09:00:00'),  -- Riz - Petit moyen
(2, 2, 10, 10, 10, 2, '2026-02-17 09:00:00'), -- Clou - Petit moyen
(1, 1, 12, 12, 12, 2, '2026-02-17 09:00:00'), -- Riz - Moyen
(2, 2, 15, 15, 15, 2, '2026-02-17 09:00:00'), -- Clou - Moyen
(1, 1, 20, 20, 20, 2, '2026-02-17 09:00:00'), -- Riz - Moyen-grand
(2, 2, 25, 25, 25, 2, '2026-02-17 09:00:00'), -- Clou - Moyen-grand
(1, 1, 30, 30, 30, 2, '2026-02-17 09:00:00'), -- Riz - Grand
(2, 2, 35, 35, 35, 2, '2026-02-17 09:00:00'), -- Clou - Grand
(1, 1, 50, 50, 50, 2, '2026-02-17 09:00:00'), -- Riz - Très grand
(2, 2, 60, 60, 60, 2, '2026-02-17 09:00:00'), -- Clou - Très grand

-- 3️⃣ Mettre à jour le stock pour avoir suffisamment pour les tests
-- Stock Riz : 80 unités (doit couvrir les besoins de Riz)
-- Stock Clou : 60 unités (doit couvrir les besoins de Clou)
UPDATE stock_bngrc SET quantite = 80 WHERE id_besoin = 1;  -- Riz
UPDATE stock_bngrc SET quantite = 60 WHERE id_besoin = 2;  -- Clou

-- 4️⃣ Insérer des dons pour le suivi
INSERT INTO dons (id_besoin, quantite, source, date) VALUES
(1, 80, 'ONG Test Riz - Distribution Prioritaire', NOW()),
(2, 60, 'ONG Test Clou - Distribution Prioritaire', NOW());

-- 5️⃣ Vérifier les données insérées
SELECT 
    'VÉRIFICATION BESOINS PRIORITAIRES' as info,
    b.libelle as besoin,
    v.libelle as ville,
    bs.quantite_restante,
    bs.date
FROM besoin_sinistre bs
JOIN besoin b ON bs.id_besoin = b.id
JOIN ville v ON bs.id_ville = v.id
WHERE bs.quantite_restante > 0
ORDER BY bs.quantite_restante ASC;

SELECT 
    'VÉRIFICATION STOCK' as info,
    b.libelle as besoin,
    s.quantite as stock_disponible
FROM stock_bngrc s
JOIN besoin b ON s.id_besoin = b.id;

SELECT 
    'RÉSUMÉ' as info,
    COUNT(*) as nb_besoins,
    SUM(bs.quantite_restante) as total_besoins,
    (SELECT SUM(quantite) FROM stock_bngrc) as stock_total
FROM besoin_sinistre bs
WHERE bs.quantite_restante > 0;
