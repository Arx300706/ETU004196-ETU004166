<?php
session_start();
if (!isset($_SESSION['id_membre']) || !isset($_GET['id_membre'])) {
    header('Location: profil.php');
    exit();
}
require_once '../inc/connexion.php';
$id_membre = intval($_GET['id_membre']);
$sql_membre = "SELECT * FROM membre WHERE id_membre = $id_membre";
$result_membre = $mysqli->query($sql_membre);
$membre = $result_membre ? $result_membre->fetch_assoc() : null;
$sql_objets = "SELECT objet.*, categorie_objet.nom_categorie
    FROM objet
    JOIN categorie_objet ON objet.id_categorie = categorie_objet.id_categorie
    WHERE objet.id_membre = $id_membre
    ORDER BY categorie_objet.nom_categorie, objet.nom_objet";
$result_objets = $mysqli->query($sql_objets);
$objets_par_cat = [];
if ($result_objets) {
    while ($row = $result_objets->fetch_assoc()) {
        $cat = $row['nom_categorie'];
        if (!isset($objets_par_cat[$cat])) $objets_par_cat[$cat] = [];
        $objets_par_cat[$cat][] = $row;
    }
    $result_objets->free();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche membre</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mb-4 shadow-lg border-success">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <?php if ($membre): ?>
                <img src="../assets/images/<?= htmlspecialchars($membre['image_profil']) ?>" alt="Photo de profil" class="rounded-circle mb-3" style="width:80px;height:80px;object-fit:cover;">
                <h3 class="card-title mb-3 fw-bold text-success"><?= htmlspecialchars($membre['nom']) ?></h3>
                <p class="mb-1"><strong>Email :</strong> <span class="text-primary"><?= htmlspecialchars($membre['email']) ?></span></p>
                <p class="mb-1"><strong>Ville :</strong> <span class="text-secondary"><?= htmlspecialchars($membre['ville']) ?></span></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="card shadow-lg border-info">
        <div class="card-body">
            <h4 class="card-title mb-4 text-info">Objets du membre par catégorie</h4>
            <?php foreach ($objets_par_cat as $cat => $objets): ?>
                <h5 class="mt-4 text-success">Catégorie : <?= htmlspecialchars($cat) ?></h5>
                <ul class="list-group mb-3">
                <?php foreach ($objets as $objet): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($objet['nom_objet']) ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="profil.php" class="btn btn-outline-secondary">Retour au profil</a>
    </div>
</div>
</body>
</html>
