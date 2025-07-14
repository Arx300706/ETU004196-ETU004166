<?php
require_once '../inc/dbconnect.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if ($email && $mdp) {
        $conn = dbconnect();

        $stmt = mysqli_prepare($conn, "SELECT id_membre, mdp FROM membre WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id_membre, $mdps);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

if ($id_membre) {
    if ($mdp === $mdps) { 
        $_SESSION['id_membre'] = $id_membre;
        header('Location: objets_list.php');
        exit();
    } else {
        $message = "Mot de passe incorrect";
    }
} else {
    $message = "Email introuvable";
}
    } else {
        $message = "Veuillez remplir tous les champs";
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
    <h2 class="mb-4">Connexion</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="list_objet.php" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <p class="mt-3">Pas de compte ?<a href="inscription.php">Inscrivez vous ici</a></p>

</body>
</html>
