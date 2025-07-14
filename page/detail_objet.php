<?php
require_once '../inc/dbconnect.php';

if (!isset($_GET['id_objet']) || !is_numeric($_GET['id_objet'])) {
    header('Location: list_objet.php');
    exit();
}

$id_objet = (int)$_GET['id_objet'];
$conn = dbconnect();

$query = "
SELECT o.nom_objet, c.nom_categorie, m.nom AS nom_membre
FROM objet o
JOIN categorie_objet c ON o.id_categorie = c.id_categorie
JOIN membre m ON o.id_membre = m.id_membre
WHERE o.id_objet = ?
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_objet);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nom_objet, $nom_categorie, $nom_membre);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: list_objet.php');
    exit();
}
mysqli_stmt_close($stmt);

$imgQuery = "SELECT nom_image FROM images_objet WHERE id_objet = ? ORDER BY id_image ASC";
$imgStmt = mysqli_prepare($conn, $imgQuery);
mysqli_stmt_bind_param($imgStmt, "i", $id_objet);
mysqli_stmt_execute($imgStmt);
$imgResult = mysqli_stmt_get_result($imgStmt);
$images = [];
while ($row = mysqli_fetch_assoc($imgResult)) {
    if (!empty($row['nom_image'])) {
        $images[] = '/asset/img/objets/' . htmlspecialchars($row['nom_image']);
    }
}
mysqli_stmt_close($imgStmt);
if (empty($images)) {
    $images[] = '../asset/img/objets/default.png';
}

$historyQuery = "
SELECT m.nom AS emprunteur, e.date_emprunt, e.date_retour
FROM emprunt e
JOIN membre m ON e.id_membre = m.id_membre
WHERE e.id_objet = ?
ORDER BY e.date_emprunt DESC
";
$historyStmt = mysqli_prepare($conn, $historyQuery);
mysqli_stmt_bind_param($historyStmt, "i", $id_objet);
mysqli_stmt_execute($historyStmt);
$historyResult = mysqli_stmt_get_result($historyStmt);
$history = [];
while ($row = mysqli_fetch_assoc($historyResult)) {
    $history[] = $row;
}
mysqli_stmt_close($historyStmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'objet</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../inc/header.php'; ?>
<div class="container mt-5">
    <h2>Détails de l'objet : <?= htmlspecialchars($nom_objet) ?></h2>
    <p><strong>Catégorie :</strong> <?= htmlspecialchars($nom_categorie) ?></p>
    <p><strong>Propriétaire :</strong> <?= htmlspecialchars($nom_membre) ?></p>

    <h4>Images</h4>
    <div class="mb-4">
        <?php foreach ($images as $imgSrc): ?>
            <img src="<?= $imgSrc ?>" alt="Image objet" style="max-width:150px; max-height:150px; object-fit:cover; border-radius:6px; margin-right:10px; margin-bottom:10px;">
        <?php endforeach; ?>
    </div>

    <h4>Historique des emprunts</h4>
    <?php if (empty($history)): ?>
        <p>Aucun emprunt enregistré pour cet objet.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['emprunteur']) ?></td>
                        <td><?= htmlspecialchars($entry['date_emprunt']) ?></td>
                        <td><?= $entry['date_retour'] ? htmlspecialchars($entry['date_retour']) : '<span class="text-warning">Non retourné</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="list_objet.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>
