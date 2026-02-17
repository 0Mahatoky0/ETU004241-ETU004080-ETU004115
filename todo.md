# BNGRC

# Conception de Base de Donnee
  - [ok] Tables
    - [ok] Region (id, libelle)
    - [ok] Ville (id, id_region, libelle)
    - [ok] categorie_besoin (id, libelle)
    - [ok] status_besoin_sinistre (id, code, libelle) (ACP, ATT)
    - [ok] Besoin (id, id_categorie, libelle, prix_unitaire)
    - [ok] Besoin_sinistre (id, id_ville, id_besoin, quantite, id_status_besoin_sinistre)
    - [ok] Dons (id, id_besoin, quantite, source, date, montant?)
    - [ok] Mvt_Dons (id, id_dons, entrer, sortie, id_besoin_sinistre, date)

# [ok] Fonctionnalite v1
    - [ok] mouvement_dons (distribution)
      - [ok] fonction
        - [ok] insertionMvtDons()
          - [] creation views "v_dispatch_dons"
            - mvt_dons et dons en add colonne reste => somme (entre - sortie) group by id_dons
        - [ok] recupereBesoinSinistre() // non-distribuer
        - [ok] listeDonsDansVille(id_ville)
        - [ok] insertionDon()
        - [ok] insertionBesoin()
        - [ok] listAllBesoin()
        - [ok] listAllDon()

# [] Fonctionnalite v2
    - [ok] Ajout Tables
      - [ok] achat_dons (id, id_ville, id_besoin, quantite, prix_unitaire, frais_percent, montant_total)
        - fonction
          - [ok] simulerAchat()
          - [ok] validerAchat()
          - [ok] createAchat()/createAchat in model
          - [ok] ajoutDons()
          - [ok] getlisteAchat() par ville

        - [] checkListeBesoinRestant (logique presente dans validerAchat)

# [] Fonctionnalite v3
    - []


# [] views
    - [ok] besoins
      - liste.php
      - details.php
      - form.php
    - [ok] dashboard
      - index.php
    - [ok] dispatch
      - details.php
      - simulation.php
      - simuler.php
    - [ok] dons
      - achat.php
      - form.php
      - liste.php
      - saisie.php

