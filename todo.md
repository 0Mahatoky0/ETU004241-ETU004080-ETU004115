# BNGRC

# Conception de Base de Donnee
    - [] Tables
        - [] Region (id, libelle)
        - [] Ville (id, id_region, libelle)
        - [] categorie_besoin (id, libelle) 
        - [] status_besoin_sinistre(id, code, libelle) (ACP, ATT)
        - [] Besoin (id, id_categorie, libelle, prix_unitaire)
        - [] Besoin_sinistre(id, id_region, id_ville, id_besoin, id_categorie, quantite, id_status_besoin_ville)
        - [] Dons (id, id_besoin, quantite, source, date)
        - [] Mvt_Dons (id, id_dons, entrer, sortie, id_besoin_ville, date)

