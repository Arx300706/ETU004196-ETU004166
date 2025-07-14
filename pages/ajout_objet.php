<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
if ($mysqli->connect_errno) {
    die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = $_POST['nom_objet'];
    $id_categorie = intval($_POST['id_categorie']);
    $id_membre = $_SESSION['id_membre'];
    // Ajout de l'objet
    $sql = "INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES ('" . $mysqli->real_escape_string($nom_objet) . "', $id_categorie, $id_membre)";
    if ($mysqli->query($sql)) {
        $id_objet = $mysqli->insert_id;
        // Upload des images
        if (!empty($_FILES['images']['name'][0])) {
            $total = count($_FILES['images']['name']);
            for ($i = 0; $i < $total; $i++) {
                $tmp_name = $_FILES['images']['tmp_name'][$i];
                $name = basename($_FILES['images']['name'][$i]);
                $target = '../assets/images/' . $name;
                if (move_uploaded_file($tmp_name, $target)) {
                    $mysqli->query("INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, '" . $mysqli->real_escape_string($name) . "')");
                }
            }
        } else {
            // Image par défaut si aucune image uploadée
            $mysqli->query("INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, 'default.jpg')");
        }
        $message = "Objet ajouté avec succès !";
    } else {
        $message = "Erreur lors de l'ajout de l'objet.";
    }
}
// Récupération des catégories
$categories = [];
$res = $mysqli->query('SELECT * FROM categorie_objet');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $categories[] = $row;
    }
    $res->free();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un bien immobilier</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h3 class="card-title mb-4 text-success">Ajouter un bien immobilier</h3>
            <?php if ($message): ?>
                <div class="alert alert-info text-center"> <?= htmlspecialchars($message) ?> </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nom_objet" class="form-label">Nom du bien</label>
                    <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
                </div>
                <div class="mb-3">
                    <label for="id_categorie" class="form-label">Catégorie</label>
                    <select class="form-select" id="id_categorie" name="id_categorie" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Images (la 1ère sera l'image principale)</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                </div>
                <button type="submit" class="btn btn-success w-100">Ajouter</button>
            </form>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="profil.php" class="btn btn-outline-secondary">Retour au profil</a>
    </div>
</div>
</body>
</html>
