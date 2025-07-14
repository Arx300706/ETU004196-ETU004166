<?php
require_once '../inc/dbconnect.php';

$conn = dbconnect();

$categoriesResult = mysqli_query($conn, "SELECT id_categorie, nom_categorie FROM categorie_objet ORDER BY nom_categorie ASC");
$categories = [];
while ($row = mysqli_fetch_assoc($categoriesResult)) {
    $categories[] = $row;
}

$filter_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$filter_name = isset($_GET['name']) ? trim($_GET['name']) : '';
$filter_available = isset($_GET['available']) ? true : false;

$query = "
SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS nom_membre, e.date_retour
FROM objet o
JOIN categorie_objet c ON o.id_categorie = c.id_categorie
JOIN membre m ON o.id_membre = m.id_membre
LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
WHERE 1=1
";

if ($filter_category > 0) {
    $query .= " AND o.id_categorie = $filter_category";
}

if ($filter_name !== '') {
    $name_escaped = mysqli_real_escape_string($conn, $filter_name);
    $query .= " AND o.nom_objet LIKE '%$name_escaped%'";
}

if ($filter_available) {
    $query .= " AND (e.date_retour IS NOT NULL OR e.date_retour IS NULL AND e.id_objet IS NULL)";
}

$query .= " ORDER BY o.nom_objet ASC";

$result = mysqli_query($conn, $query);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../inc/header.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Liste des objets :</h2>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="category" class="form-label">Catégorie</label>
            <select id="category" name="category" class="form-select">
                <option value="0">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id_categorie'] ?>"<?= $filter_category === (int)$cat['id_categorie'] ? ' selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <label for="name" class="form-label">Nom de l'objet</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($filter_name) ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="available" name="available" <?= $filter_available ? 'checked' : '' ?>>
                <label class="form-check-label" for="available">
                    Disponible uniquement
                </label>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </form>

    <div class="mb-4">
        <a href="ajout_objet.php" class="btn btn-primary">Ajouter un objet</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Nom objet</th>
                <th>Catégorie</th>
                <th>Propriétaire</th>
                <th>Date de retour</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            mysqli_data_seek($result, 0);
            while ($objet = mysqli_fetch_assoc($result)): 
                $imgResult = mysqli_query($conn, "SELECT nom_image FROM images_objet WHERE id_objet = " . (int)$objet['id_objet'] . " ORDER BY id_image ASC");
                $images = [];
                while ($imgRow = mysqli_fetch_assoc($imgResult)) {
                    if (!empty($imgRow['nom_image'])) {
                        $images[] = '/asset/img/objets/' . htmlspecialchars($imgRow['nom_image']);
                    }
                }
                if (empty($images)) {
                    $images[] = '/asset/img/objets/default.png';
                }
            ?>
                <tr>
                    <td style="width:90px">
                        <?php foreach ($images as $imgSrc): ?>
                            <img src="<?= $imgSrc ?>" alt="image objet" style="max-width:40px; max-height:40px; object-fit:cover; border-radius:6px; margin-right:4px;">
                        <?php endforeach; ?>
                </td>
                <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                <td>
                    <a href="detail_membre.php?id_membre=<?= (int)$objet['id_membre'] ?>">
                        <?= htmlspecialchars($objet['nom_membre']) ?>
                    </a>
                </td>
                <td>
                    <?php
                    if ($objet['date_retour']) {
                        echo htmlspecialchars($objet['date_retour']);
                    } else {
                        echo "<span class='text-success'>Disponible</span>";
                    }
                    ?>
                </td>
                <td>
                    <a href="detail_objet.php?id_objet=<?= (int)$objet['id_objet'] ?>" class="btn btn-primary btn-sm">Voir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>
