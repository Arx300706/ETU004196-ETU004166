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

// Critères de recherche
$id_categorie = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$nom_objet = isset($_GET['nom_objet']) ? trim($_GET['nom_objet']) : '';
$disponible = isset($_GET['disponible']) ? true : false;

$where = [];
if ($id_categorie) {
    $where[] = 'objet.id_categorie = ' . $id_categorie;
}
if ($nom_objet !== '') {
    $where[] = "objet.nom_objet LIKE '%" . $mysqli->real_escape_string($nom_objet) . "%'";
}
if ($disponible) {
    $where[] = "objet.id_objet NOT IN (SELECT id_objet FROM emprunt WHERE date_retour > CURDATE())";
}
$where_sql = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

$sql_objets = "SELECT objet.*, categorie_objet.nom_categorie,
        (SELECT date_retour FROM emprunt WHERE emprunt.id_objet = objet.id_objet ORDER BY date_retour DESC LIMIT 1) AS date_retour
        FROM objet
        JOIN categorie_objet ON objet.id_categorie = categorie_objet.id_categorie
        $where_sql
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
    <form method="get" class="mb-3 row g-2 align-items-end">
        <div class="col-md-4">
            <label for="categorie" class="form-label">Catégorie :</label>
            <select name="categorie" id="categorie" class="form-select">
                <option value="0">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= $id_categorie == $cat['id_categorie'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="nom_objet" class="form-label">Nom de l'objet :</label>
            <input type="text" name="nom_objet" id="nom_objet" class="form-control" value="<?= htmlspecialchars($nom_objet) ?>">
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="disponible" id="disponible" <?= $disponible ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponible">Disponible</label>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Rechercher</button>
        </div>
    </form>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Date de retour (si emprunté)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($objets as $objet): ?>
            <?php
            // Récupérer toutes les images de l'objet
            $img_result = $mysqli->query("SELECT nom_image FROM images_objet WHERE id_objet = " . intval($objet['id_objet']) . " ORDER BY id_image ASC");
            $images = [];
            if ($img_result && $img_result->num_rows) {
                while ($img_row = $img_result->fetch_assoc()) {
                    $images[] = $img_row['nom_image'];
                }
            } else {
                $images[] = 'default.jpg';
            }
            // Ajout img10.jpg pour les objets de Tiavina (id_membre = 2)
            if ($objet['id_membre'] == 2 && !in_array('img10.jpg', $images)) {
                $images[] = 'img10.jpg';
            }
            ?>
            <tr>
                <td>
                    <?php foreach ($images as $img): ?>
                        <img src="../assets/images/<?= htmlspecialchars($img) ?>" alt="photo" style="width:40px;height:40px;object-fit:cover;border-radius:8px;margin-right:2px;">
                    <?php endforeach; ?>
                    <a href="edit_images.php?id_objet=<?= $objet['id_objet'] ?>" class="btn btn-outline-primary btn-sm ms-2">Ajouter des images</a>
                </td>
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
