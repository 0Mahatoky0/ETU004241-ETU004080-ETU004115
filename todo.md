# BNGRC

# Conception de Base de Donnee
    - [ok] Tables
        - [ok] Region (id, libelle)
        - [ok] Ville (id, id_region, libelle)
        - [ok] categorie_besoin (id, libelle) 
        - [ok] status_besoin_sinistre(id, code, libelle) (ACP, ATT)
        - [ok] Besoin (id, id_categorie, libelle, prix_unitaire)
        - [ok] Besoin_sinistre(id, id_region, id_ville, id_besoin, id_categorie, quantite, id_status_besoin_ville)
        - [ok] Dons (id, id_besoin, quantite, source, date)
        - [ok] Mvt_Dons (id, id_dons, entrer, sortie, id_besoin_ville, date)

    - [] Fonctionnalite
        - [] mouvement_dons (distribution)

            - [] fonction
                - [] insertionMvtDons()
                    - [] creation views "v_dispatch_dons"
                        - mvt_dons et dons en add colonne reste => somme (entre - sortie) group by id_dons
                - [] recupereBesoinSinistre() // non-distribuer
                - [] listeDonsDansVille(id_ville)

            - [] views
                - dossier (besoinSinistre)
                    - liste.php
            
            

