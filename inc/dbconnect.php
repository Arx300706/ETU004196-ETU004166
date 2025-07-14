<?php
ini_set('display_errors', 1);

function dbconnect()
{
    static $connect = null;

    if ($connect === null) {
        $connect = mysqli_connect('172.60.0.26', 'ETU004196', '9bJUBZVX', 'db_s2_ETU004196');

        if (!$connect) {
            die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
        }

        mysqli_set_charset($connect, 'utf8');
    }

    return $connect;
}
?>
