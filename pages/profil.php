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

$id_membre = $_SESSION['id_membre'];
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
    </style>
</head>
<body class="bg-light">
<?php if ($membre): ?>
    <div class="profil-bar d-flex align-items-center px-4 justify-content-between">
        <div class="d-flex align-items-center">
            <img src="../assets/images/<?= htmlspecialchars($membre['image_profil']) ?>" alt="Photo de profil" class="profil-img shadow">
            <span class="profil-nom ms-2"><?= htmlspecialchars($membre['nom']) ?></span>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
    </div>
<?php endif; ?>
<div class="container main-content">
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <?php if ($membre): ?>
                <h3 class="card-title mb-3 fw-bold text-success" style="font-size: 2rem; margin-top: 1rem;">Profil</h3>
                <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
                <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Objets disponibles à emprunter</h4>
            <div class="row">
            <?php foreach ($objets as $objet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php
                        // Récupérer la photo de l'objet
                        $img_result = $mysqli->query("SELECT nom_image FROM images_objet WHERE id_objet = " . intval($objet['id_objet']) . " LIMIT 1");
                        $img = $img_result && $img_result->num_rows ? $img_result->fetch_assoc()['nom_image'] : 'default.jpg';
                        ?>
                        <img src="../assets/images/<?= htmlspecialchars($img) ?>" class="card-img-top" alt="Photo objet" style="height:180px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
                            <p class="card-text"><span class="badge bg-secondary"><?= htmlspecialchars($objet['nom_categorie']) ?></span></p>
                            <form method="post" action="emprunter.php">
                                <input type="hidden" name="id_objet" value="<?= $objet['id_objet'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Emprunter</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
