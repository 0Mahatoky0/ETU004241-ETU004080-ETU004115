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

    - [] Fonctionnalite v1
        - [] mouvement_dons (distribution)
            - [ok] fonction
                - [ok] insertionMvtDons()
                    - [] creation views "v_dispatch_dons"
                        - mvt_dons et dons en add colonne reste => somme (entre - sortie) group by id_dons
                - [ok] recupereBesoinSinistre() // non-distribuer
                - [ok] listeDonsDansVille(id_ville)

            - [] views
                - dossier (besoinSinistre)
                    - liste.php


    - [] Fonctionnalite v2
        - [] Ajout Tables
            - [] achat_dons(id, id_categorie, id_besoin, quantite, prix_reelle(quantite * prix_unitaire) )
                - fonction 
                    ajoutDons()
                    getlisteAchatFiltrables() par ville

                - checkListeBesoinRestant
                    si il y a un reste (message d erreur)
                    si non  achats

            
            

