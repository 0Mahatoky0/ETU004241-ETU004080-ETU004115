-- 3️⃣ Mettre à jour le stock pour avoir suffisamment pour les tests
-- Stock Riz : 80 unités (doit couvrir les besoins de Riz)
-- Stock Clou : 60 unités (doit couvrir les besoins de Clou)
UPDATE stock_bngrc SET quantite = 80 WHERE id_besoin = 1;  -- Riz
UPDATE stock_bngrc SET quantite = 60 WHERE id_besoin = 2;  -- Clou
