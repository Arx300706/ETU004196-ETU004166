<?php
require_once '../inc/dbconnect.php';
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $email = $_POST['email'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $mdp_confirm = $_POST['mdp_confirm'] ?? '';

    if ($nom && $date_naissance && $genre && $email && $ville && $mdp && $mdp_confirm) {
        if ($mdp !== $mdp_confirm) {
            $message = "Les mots de passe ne correspondent pas";
        } else {
            $conn = dbconnect();

            $stmt = mysqli_prepare($conn, "SELECT id_membre FROM membre WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $count = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);

            if ($count > 0) {
                $message = "Email déjà existant";
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssss", $nom, $date_naissance, $genre, $email, $ville, $mdp);
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                if ($success) {
                    $message = "Inscription réussie";
                    header("refresh:3;url=login.php");
                } else {
                    $message = "Erreur lors de l'inscription";
                }
            }
        }
    } else {
        $message = "Remplir tous les champs";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link href="../asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/styles.css" rel="stylesheet">
</head>
<body>
    <h2 class="mb-4">Inscription</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="inscription.php" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select class="form-select" id="genre" name="genre" required>
                <option value="">Choisissez</option>
                <option value="M">Homme</option>
                <option value="F">Femme</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <div class="mb-3">
            <label for="mdp_confirm" class="form-label">Confirmation mot de passe</label>
            <input type="password" class="form-control" id="mdp_confirm" name="mdp_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    <p class="mt-3">Vous avez deja un compte ?<a href="login.php">Connexion</a></p>

</body>
</html>

