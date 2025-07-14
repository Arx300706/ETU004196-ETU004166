<?php
session_start();
$erreur = '';
$success = '';

// Connexion à la base
$mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
if ($mysqli->connect_errno) {
    die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
}

// Traitement du formulaire de connexion
if (isset($_POST['login'])) {
    $email = $_POST['email_login'];
    $mdp = $_POST['mdp_login'];
    $sql = "SELECT * FROM membre WHERE email = '" . $mysqli->real_escape_string($email) . "' AND mdp = '" . $mysqli->real_escape_string($mdp) . "'";
    $result = $mysqli->query($sql);
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['id_membre'] = $user['id_membre'];
        header('Location: profil.php');
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}

// Traitement du formulaire d'inscription
if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $ville = $_POST['ville'];
    $image = 'default.jpg'; // image par défaut
    $sql = "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES ('" .
        $mysqli->real_escape_string($nom) . "', '2000-01-01', 'M', '" . $mysqli->real_escape_string($email) . "', '" . $mysqli->real_escape_string($ville) . "', '" . $mysqli->real_escape_string($mdp) . "', '" . $mysqli->real_escape_string($image) . "')" ;
    if ($mysqli->query($sql)) {
        $success = "Inscription réussie ! Vous pouvez vous connecter.";
    } else {
        $erreur = "Erreur lors de l'inscription.";
    }
}

// Liste des objets disponibles à emprunter
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
    <title>Connexion & Inscription</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Connexion</h2>
                    <?php if ($erreur): ?>
                        <div class="alert alert-danger text-center"> <?= htmlspecialchars($erreur) ?> </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="email_login" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email_login" name="email_login" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mdp_login" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="mdp_login" name="mdp_login" required>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Inscription</h2>
                    <?php if ($success): ?>
                        <div class="alert alert-success text-center"> <?= htmlspecialchars($success) ?> </div>
                    <?php endif; ?>
                    <?php if ($erreur): ?>
                        <div class="alert alert-danger text-center"> <?= htmlspecialchars($erreur) ?> </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mdp" name="mdp" required>
                        </div>
                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-success w-100">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h4 class="card-title">Objets disponibles à emprunter</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($objets as $objet): ?>
                            <tr>
                                <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                                <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
