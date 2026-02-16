-- vue de reste de dons
CREATE VIEW v_dons_reste as 
SELECT id_dons ,SUM(entrer - sortie) as reste FROM mvt_dons GROUP BY id_dons;

-- liste des besoins non compencer
CREATE VIEW v_dons_besoin_sinistre as ;
SELECT SUM(md.sortie) FROM besoin_sinistre bs
JOIN mvt_dons md
on bs.id = md.id_besoin_sinistre;