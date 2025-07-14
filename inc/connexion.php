<?php
$mysqli = new mysqli('localhost', 'root', '', 'Examenfinal');
if ($mysqli->connect_errno) {
    die('Erreur de connexion à la base de données : ' . $mysqli->connect_error);
}
?>
