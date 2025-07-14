<?php
require_once '../inc/dbconnect.php';

if (!isset($_GET['id_membre']) || !is_numeric($_GET['id_membre'])) {
    header('Location: list_objet.php');
    exit();
}

$id_membre = (int)$_GET['id_membre'];
$conn = dbconnect();

$query = "SELECT nom, date_naissance, genre, email, ville FROM membre WHERE id_membre = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_membre);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nom, $date_naissance, $genre, $email, $ville);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: list_objet.php');
    exit();
}
mysqli_stmt_close($stmt);

$objectsQuery = "
SELECT c.nom_categorie, o.id_objet, o.nom_objet
FROM objet o
JOIN categorie_objet c ON o.id_categorie = c.id_categorie
WHERE o.id_membre = ?
ORDER BY c.nom_categorie, o.nom_objet
";
$objectsStmt = mysqli_prepare($conn, $objectsQuery);
mysqli_stmt_bind_param($objectsStmt, "i", $id_membre);
mysqli_stmt_execute($objectsStmt);
$objectsResult = mysqli_stmt_get_result($objectsStmt);

$objectsByCategory = [];
while ($row = mysqli_fetch_assoc($objectsResult)) {
    $objectsByCategory[$row['nom_categorie']][] = $row;
}
mysqli_stmt_close($objectsStmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du membre</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../inc/header.php'; ?>
<div class="container mt-5">
    <h2>Détails du membre : <?= htmlspecialchars($nom) ?></h2>
    <p><strong>Date de naissance :</strong> <?= htmlspecialchars($date_naissance) ?></p>
    <p><strong>Genre :</strong> <?= htmlspecialchars($genre) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Ville :</strong> <?= htmlspecialchars($ville) ?></p>

    <h4>Objets du membre par catégorie</h4>
    <?php if (empty($objectsByCategory)): ?>
        <p>Ce membre n'a aucun objet enregistré.</p>
    <?php else: ?>
        <?php foreach ($objectsByCategory as $category => $objects): ?>
            <h5><?= htmlspecialchars($category) ?></h5>
            <ul>
                <?php foreach ($objects as $obj): ?>
                    <li>
                        <a href="detail_objet.php?id_objet=<?= (int)$obj['id_objet'] ?>">
                            <?= htmlspecialchars($obj['nom_objet']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="list_objet.php" class="btn btn-secondary mt-3">Retour à la liste des objets</a>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>
