<?php
require_once '../inc/dbconnect.php';
$conn = dbconnect();

// Récupérer les états distincts
$etatResult = mysqli_query($conn, "SELECT DISTINCT etat FROM objet");
$etats = [];
while ($row = mysqli_fetch_assoc($etatResult)) {
    $etats[] = $row['etat'];
}

// Filtrer par état si demandé
$filter_etat = isset($_GET['etat']) ? $_GET['etat'] : '';
$query = "
SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS nom_membre, o.etat
FROM objet o
JOIN categorie_objet c ON o.id_categorie = c.id_categorie
JOIN membre m ON o.id_membre = m.id_membre
";
if ($filter_etat !== '') {
    $etat_escaped = mysqli_real_escape_string($conn, $filter_etat);
    $query .= " WHERE o.etat = '$etat_escaped'";
}
$query .= " ORDER BY o.etat DESC, o.nom_objet ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État des objets</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../inc/header.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Objets par état</h2>
    <form method="get" class="mb-4">
        <label for="etat" class="form-label">Filtrer par état :</label>
        <select name="etat" id="etat" class="form-select" style="max-width:200px; display:inline-block;">
            <option value="">Tous</option>
            <?php foreach ($etats as $etat): ?>
                <option value="<?= htmlspecialchars($etat) ?>" <?= $filter_etat === $etat ? 'selected' : '' ?>>
                    <?= ucfirst(htmlspecialchars($etat)) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary ms-2">Filtrer</button>
    </form>
    <table class="table table-hover table-container">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Propriétaire</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($obj = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($obj['nom_objet']) ?></td>
                    <td><?= htmlspecialchars($obj['nom_categorie']) ?></td>
                    <td><?= htmlspecialchars($obj['nom_membre']) ?></td>
                    <td>
                        <?php if ($obj['etat'] === 'abime'): ?>
                            <span class="text-danger fw-bold">Abîmé</span>
                        <?php else: ?>
                            <span class="text-success fw-bold">Bon état</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="list_objet.php" class="btn btn-secondary mt-3">Retour à la liste des objets</a>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>