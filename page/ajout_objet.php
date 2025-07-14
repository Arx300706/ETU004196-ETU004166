<?php
require_once '../inc/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = dbconnect();
    $nom_objet = mysqli_real_escape_string($conn, $_POST['nom_objet']);
    $id_categorie = (int)$_POST['id_categorie'];
    $id_membre = 1; 
    $sql = "INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES ('$nom_objet', $id_categorie, $id_membre)";
    if (mysqli_query($conn, $sql)) {
        $id_objet = mysqli_insert_id($conn);
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = '../asset/img/objets/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === 0) {
                    $fileName = uniqid('img_') . '_' . basename($_FILES['images']['name'][$key]);
                    $targetPath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $imgName = $fileName;
                        mysqli_query($conn, "INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, '$imgName')");
                    }
                }
            }
        }
        header('Location: list_objet.php');
        exit;
    } else {
        $error = "Erreur lors de l'ajout de l'objet.";
    }
}

// Récupérer les catégories
$conn = dbconnect();
$catResult = mysqli_query($conn, "SELECT * FROM categorie_objet");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un objet</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../inc/header.php';?>
<div class="container mt-5">
    <h2 class="mb-4">Ajouter un objet</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom de l'objet</label>
            <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
        </div>
        <div class="mb-3">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="id_categorie" name="id_categorie" required>
                <option value="">Sélectionner</option>
                <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images (vous pouvez en sélectionner plusieurs)</label>
            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="list_objet.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>
