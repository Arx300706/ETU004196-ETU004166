<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_objet'])) {
    $id_objet = intval($_POST['id_objet']);
    $id_membre = $_SESSION['id_membre'];
    $date_emprunt = date('Y-m-d');
    $date_retour = date('Y-m-d', strtotime('+7 days'));

    $mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
    if ($mysqli->connect_errno) {
        die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
    }
    

    // Vérifier si l'objet est déjà emprunté
    $verif = $mysqli->query("SELECT * FROM emprunt WHERE id_objet = $id_objet AND date_retour > CURDATE()");
    if ($verif && $verif->num_rows > 0) {
        $message = "Cet objet est déjà emprunté.";
    } else {
        $sql = "INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES ($id_objet, $id_membre, '$date_emprunt', '$date_retour')";
        if ($mysqli->query($sql)) {
            $message = "Emprunt effectué avec succès !";
        } else {
            $message = "Erreur lors de l'emprunt.";
        }
    }
} else {
    $message = "Aucun objet sélectionné.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter un objet</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="alert alert-info text-center">
        <?= htmlspecialchars($message) ?>
    </div>
    <div class="text-center">
        <a href="profil.php" class="btn btn-success">Retour au profil</a>
    </div>
</div>
</body>
</html>
