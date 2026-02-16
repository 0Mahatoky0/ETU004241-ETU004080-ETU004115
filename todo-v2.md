# sujet :
## p1 :
achat par les dons en argent des :
    - besoin en nature
    - besoin en materiaux
fraix d achat congigurable
    - x%
page de besoin restant pour une ville
message d erreur si on veux acheter un besoin alors qu on a cette besoin disponible en stock

# todo :
## base :
- table :
    - config
        - frais_achat
    - achat_mvt_dons
        - id
        - id_mvt_argent
        - id_mvt_dons
        - date
- requete :
    - recupearation de la config
    - recuperer l argent la total d argent qu on a
    - recuperer la liste des besoins restant pour chaque ville
    - recuperer le quantite de dons , pour un categorie de besoin specifique
    - insertion achat_mvt_dons

- effectuer une achat :
    - sortir l argent necessaire (achat du mvt de stock)
        - quantite * prix unitaire du besoin + 10% *(quantite * prix unitaire du besoin)
    - faire un mvt pour la distribution du dons achete 
            - sortie : totalite du dons
    - inseret une achat de mvt