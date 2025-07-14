<?php
$mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
if ($mysqli->connect_errno) {
    die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
}

$categories = [];
$sql_categories = 'SELECT * FROM categorie_objet';
$result_categories = $mysqli->query($sql_categories);
if ($result_categories) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
    $result_categories->free();
}

$id_categorie = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$where = $id_categorie ? 'WHERE objet.id_categorie = ' . $id_categorie : '';

$sql_objets = "SELECT objet.*, categorie_objet.nom_categorie,
        (SELECT date_retour FROM emprunt WHERE emprunt.id_objet = objet.id_objet ORDER BY date_retour DESC LIMIT 1) AS date_retour
        FROM objet
        JOIN categorie_objet ON objet.id_categorie = categorie_objet.id_categorie
        $where
        ORDER BY objet.id_objet";
$objets = [];
$result_objets = $mysqli->query($sql_objets);
if ($result_objets) {
    while ($row = $result_objets->fetch_assoc()) {
        $objets[] = $row;
    }
    $result_objets->free();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des objets</h2>
    <form method="get" class="mb-3">
        <label for="categorie" class="form-label">Filtrer par catégorie :</label>
        <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
            <option value="0">Toutes</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= $id_categorie == $cat['id_categorie'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom_categorie']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Date de retour (si emprunté)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($objets as $objet): ?>
            <tr>
                <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                <td><?= $objet['date_retour'] ? htmlspecialchars($objet['date_retour']) : '-' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
