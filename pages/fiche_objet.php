<?php
session_start();
if (!isset($_GET['id_objet'])) {
    header('Location: profil.php');
    exit();
}
$id_objet = intval($_GET['id_objet']);
$mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
if ($mysqli->connect_errno) {
    die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
}
$sql = "SELECT objet.*, categorie_objet.nom_categorie, membre.nom AS proprietaire FROM objet JOIN categorie_objet ON objet.id_categorie = categorie_objet.id_categorie JOIN membre ON objet.id_membre = membre.id_membre WHERE objet.id_objet = $id_objet";
$res = $mysqli->query($sql);
$objet = $res && $res->num_rows ? $res->fetch_assoc() : null;
$images = [];
$res_img = $mysqli->query("SELECT nom_image FROM images_objet WHERE id_objet = $id_objet");
if ($res_img) {
    while ($row = $res_img->fetch_assoc()) {
        $images[] = $row['nom_image'];
    }
    $res_img->free();
}

$emprunts = [];
$res_emp = $mysqli->query("SELECT e.*, m.nom AS emprunteur FROM emprunt e JOIN membre m ON e.id_membre = m.id_membre WHERE e.id_objet = $id_objet ORDER BY e.date_emprunt DESC");
if ($res_emp) {
    while ($row = $res_emp->fetch_assoc()) {
        $emprunts[] = $row;
    }
    $res_emp->free();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche du bien immobilier</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <?php if ($objet): ?>
    <div class="card shadow-lg mb-4">
        <div class="card-body">
            <h3 class="card-title text-success mb-3"><i class="bi bi-building"></i> <?= htmlspecialchars($objet['nom_objet']) ?></h3>
            <span class="badge bg-primary mb-2">Propriétaire : <?= htmlspecialchars($objet['proprietaire']) ?></span>
            <span class="badge bg-secondary mb-2">Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?></span>
            <div class="row g-3 mb-3">
                <?php if ($images): ?>
                    <div class="col-md-6">
                        <img src="../assets/images/<?= htmlspecialchars($images[0]) ?>" class="img-fluid rounded shadow" alt="Image principale">
                        <p class="mt-2 text-center text-muted">Image principale</p>
                    </div>
                    <div class="col-md-6 d-flex flex-wrap align-items-start">
                        <?php foreach (array_slice($images, 1) as $img): ?>
                            <img src="../assets/images/<?= htmlspecialchars($img) ?>" class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;" alt="Autre image">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <img src="../assets/images/default.jpg" class="img-fluid rounded shadow" alt="Image par défaut">
                        <p class="mt-2 text-center text-muted">Image par défaut</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-clock-history"></i> Historique des emprunts</h5>
            <?php if ($emprunts): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Emprunteur</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($emprunts as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['emprunteur']) ?></td>
                        <td><?= htmlspecialchars($emp['date_emprunt']) ?></td>
                        <td><?= htmlspecialchars($emp['date_retour']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-muted">Aucun emprunt pour ce bien.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-danger">Bien immobilier introuvable.</div>
    <?php endif; ?>
    <div class="text-center">
        <a href="profil.php" class="btn btn-outline-secondary">Retour au profil</a>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
