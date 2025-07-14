<?php
require_once '../inc/connexion.php';
require_once '../inc/images_objet_functions.php';
session_start();
if (!isset($_SESSION['id_membre']) || !isset($_GET['id_objet'])) {
    header('Location: profil.php');
    exit();
}

$id_objet = intval($_GET['id_objet']);
$message = '';
if (isset($_GET['delete']) && isset($_GET['img'])) {
    $message = supprimerImage($mysqli, $id_objet, $_GET['img']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['images']['name'][0])) {
    $message = ajouterImages($mysqli, $id_objet, $_FILES['images']);
}
$images = getImages($mysqli, $id_objet);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_single']) && !empty($_FILES['single_image']['name'])) {
    $file = $_FILES['single_image'];
    $tmp_name = $file['tmp_name'];
    $name = basename($file['name']);
    $target = '../assets/images/' . $name;
    if (move_uploaded_file($tmp_name, $target)) {
        $mysqli->query("INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, '" . $mysqli->real_escape_string($name) . "')");
        $message = "Image ajoutée via le formulaire.";
    } else {
        $message = "Erreur lors de l'upload de l'image.";
    }
}?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les images du bien</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4 text-success">Gérer les images du bien</h3>
            <?php if ($message): ?>
                <div class="alert alert-info text-center"> <?= htmlspecialchars($message) ?> </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="mb-4">
                <label for="images" class="form-label">Ajouter des images</label>
                <input type="file" class="form-control mb-2" id="images" name="images[]" multiple accept="image/*">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
            <h5>Images actuelles :</h5>
            <div class="row">
            <?php foreach ($images as $img): ?>
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="../assets/images/<?= htmlspecialchars($img) ?>" class="card-img-top" style="height:120px;object-fit:cover;">
                        <div class="card-body text-center">
                            <?php if ($img !== 'default.jpg'): ?>
                                <a href="edit_images.php?id_objet=<?= $id_objet ?>&delete=1&img=<?= urlencode($img) ?>" class="btn btn-outline-danger btn-sm">Supprimer</a>
                            <?php else: ?>
                                <span class="badge bg-secondary">Image par défaut</span>
                            <?php endif; ?>
                        </div>
            <hr>
            <h5 class="mt-4">Ajouter une image via formulaire séparé :</h5>
            <form method="post" enctype="multipart/form-data" class="mb-4" action="edit_images.php?id_objet=<?= $id_objet ?>">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <td><label for="single_image" class="form-label">Image à uploader</label></td>
                            <td><input type="file" class="form-control" id="single_image" name="single_image" accept="image/*"></td>
                            <td><button type="submit" name="upload_single" class="btn btn-primary">Uploader</button></td>
                        </tr>
                    </table>
                </div>
            </form>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="fiche_objet.php?id_objet=<?= $id_objet ?>" class="btn btn-outline-secondary">Retour à la fiche</a>
    </div>
</div>
</body>
</html>
