<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion de Dons</title>
</head>

<body>
    <h1>Formulaire d'insertion de Dons</h1>
    <form action="/dons/api/add" method="POST">
        <label for="id_besoin">Besoin :</label>
        <select name="id_besoin" id="id_besoin" required>
            <!-- Les options doivent être générées dynamiquement depuis la table 'besoin' -->
            <?php foreach ($besoins as $b) { ?>
                <option value="<?= $b["id"] ?> "><?= $b["libelle"] ?></option>
            <?php } ?>
        </select>
        <br><br>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" required>
        <br><br>

        <label for="source">Source :</label>
        <input type="text" name="source" id="source" required>
        <br><br>

        <label for="date">Date :</label>
        <input type="datetime-local" name="date" id="date" required>
        <br><br>

        <button type="submit">Ajouter le Don</button>
    </form>
</body>

</html>