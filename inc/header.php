<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbconnect.php';

$user_name = '';
if (isset($_SESSION['id_membre'])) {
    $conn = dbconnect();
    $stmt = mysqli_prepare($conn, "SELECT nom FROM membre WHERE id_membre = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_membre']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Application</title>
    <link href="/asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/asset/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/index.php">Mon Application</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/page/objets_list.php">Objets</a>
        </li>
        <li class="nav-item">
    <a class="nav-link" href="../page/etat_objet.php">État des objets</a>
  </li>
        <li class="nav-item">
          <a class="nav-link" href="../page/inscription.php">Inscription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../inc/logout.php">Deconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
</body>
</html>