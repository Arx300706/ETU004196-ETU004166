<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

require_once '../inc/connexion.php';

$id_membre = $_SESSION['id_membre'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profil_image']) && !empty($_FILES['profil_image']['name'])) {
    $file = $_FILES['profil_image'];
    $name = basename($file['name']);
    $target = '../assets/images/' . $name;
    if (move_uploaded_file($file['tmp_name'], $target)) {
        $mysqli->query("UPDATE membre SET image_profil = '" . $mysqli->real_escape_string($name) . "' WHERE id_membre = $id_membre");
    }
}
$sql_membre = "SELECT * FROM membre WHERE id_membre = $id_membre";
$result_membre = $mysqli->query($sql_membre);
$membre = $result_membre ? $result_membre->fetch_assoc() : null;
$sql_objets = "SELECT objet.*, categorie_objet.nom_categorie
    FROM objet
    JOIN categorie_objet ON objet.id_categorie = categorie_objet.id_categorie
    WHERE objet.id_objet NOT IN (
        SELECT id_objet FROM emprunt WHERE date_retour > CURDATE()
    )
    ORDER BY objet.id_objet";
$result_objets = $mysqli->query($sql_objets);
$objets = [];
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
    <title>Profil</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <style>
        .profil-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 1000;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 0.5rem 0;
        }
        .profil-bar .profil-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #198754;
            margin-right: 12px;
        }
        .profil-bar .profil-nom {
            font-weight: bold;
            color: #198754;
            font-size: 1.2rem;
        }
        .main-content {
            margin-top: 80px;
        }
        .immobilier-card:hover {
            box-shadow: 0 0 16px #19875455;
            transform: translateY(-4px) scale(1.02);
            transition: all 0.2s;
        }
    </style>
</head>
<body class="bg-light">
<?php if ($membre): ?>
    <div class="profil-bar d-flex align-items-center px-4 justify-content-between">
        <div class="d-flex align-items-center">
            <img src="../assets/images/<?= htmlspecialchars($membre['image_profil']) ?>" alt="Photo de profil" class="profil-img shadow">
            <span class="profil-nom ms-2"><?= htmlspecialchars($membre['nom']) ?></span>
            <form method="post" enctype="multipart/form-data" class="d-inline ms-3">
                <input type="file" name="profil_image" accept="image/*" style="display:none;" id="profil_image_input" onchange="this.form.submit()">
                <label for="profil_image_input" class="btn btn-outline-success btn-sm mb-0">Changer photo</label>
            </form>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
    </div>
<?php endif; ?>
<div class="container main-content">
    <div class="card mb-4 shadow-lg border-success">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <?php if ($membre): ?>
                <h3 class="card-title mb-3 fw-bold text-success" style="font-size: 2rem; margin-top: 1rem;"><i class="bi bi-person-circle"></i> Profil</h3>
                <p class="mb-1"><strong>Email :</strong> <span class="text-primary"><?= htmlspecialchars($membre['email']) ?></span></p>
                <p class="mb-1"><strong>Ville :</strong> <span class="text-secondary"><?= htmlspecialchars($membre['ville']) ?></span></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="card shadow-lg border-info">
        <div class="card-body">
            <h4 class="card-title mb-4 text-info"><i class="bi bi-house-door"></i> Biens immobiliers disponibles à emprunter</h4>
            <div class="row g-4">
            <?php foreach ($objets as $objet): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm position-relative immobilier-card">
                        <?php
                        $img_result = $mysqli->query("SELECT nom_image FROM images_objet WHERE id_objet = " . intval($objet['id_objet']) . " LIMIT 1");
                        $img = $img_result && $img_result->num_rows ? $img_result->fetch_assoc()['nom_image'] : 'default.jpg';
                        $prop_result = $mysqli->query("SELECT nom FROM membre WHERE id_membre = " . intval($objet['id_membre']) . " LIMIT 1");
                        $proprietaire = $prop_result && $prop_result->num_rows ? $prop_result->fetch_assoc()['nom'] : 'Inconnu';
                        $retour_result = $mysqli->query("SELECT date_retour FROM emprunt WHERE id_objet = " . intval($objet['id_objet']) . " ORDER BY date_retour DESC LIMIT 1");
                        $date_retour = $retour_result && $retour_result->num_rows ? $retour_result->fetch_assoc()['date_retour'] : null;
                        ?>
                        <img src="../assets/images/<?= htmlspecialchars($img) ?>" class="card-img-top rounded-top" alt="Photo bien" style="height:180px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success mb-2"><i class="bi bi-building"></i> <?= htmlspecialchars($objet['nom_objet']) ?></h5>
                            <span class="badge bg-primary mb-2">Propriétaire : <?= htmlspecialchars($proprietaire) ?></span>
                            <p class="card-text mb-2"><span class="badge bg-secondary">Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?></span></p>
                            <p class="mb-2"><i class="bi bi-calendar-check"></i> Date de retour : <span class="text-danger"><?= $date_retour ? htmlspecialchars($date_retour) : 'Disponible' ?></span></p>
                            <form method="post" action="emprunter.php">
                                <input type="hidden" name="id_objet" value="<?= $objet['id_objet'] ?>">
                                <button type="submit" class="btn btn-outline-success btn-sm w-100"><i class="bi bi-box-arrow-in-right"></i> Emprunter</button>
                            </form>
                        </div>
                        <a href="fiche_objet.php?id_objet=<?= $objet['id_objet'] ?>" class="stretched-link"></a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
