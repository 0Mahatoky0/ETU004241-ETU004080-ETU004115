<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion de Besoin Sinistre</title>
</head>

<body>
    <h1>Formulaire d'insertion de Besoin Sinistre</h1>
    <form action="/besoin_sinistre/api/add" method="POST">
        <label for="id_ville">Ville :</label>
        <select name="id_ville" id="id_ville" required>
            <?php foreach ($villes as $ville) { ?>
                <option value="<?= $ville["id"] ?>"><?= $ville["libelle"] ?></option>
            <?php } ?>
        </select>
        <br><br>

        <label for="id_besoin">Besoin :</label>
        <select name="id_besoin" id="id_besoin" required>
            <?php foreach ($besoins as $besoin) { ?>
                <option value="<?= $besoin["id"] ?>"><?= $besoin["libelle"] ?></option>
            <?php } ?>
        </select>
        <br><br>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" required>
        <br><br>

        <label for="id_status_besoin_sinistre">Statut :</label>
        <input type="number" name="id_status_besoin_sinistre" id="id_status_besoin_sinistre" required>
        <br><br>

        <button type="submit">Ajouter le Besoin Sinistre</button>
    </form>
</body>

</html>