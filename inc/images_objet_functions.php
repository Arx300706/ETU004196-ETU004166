<?php
function supprimerImage($mysqli, $id_objet, $img) {
    $img = $mysqli->real_escape_string($img);
    $mysqli->query("DELETE FROM images_objet WHERE id_objet = $id_objet AND nom_image = '$img'");
    $file = '../assets/images/' . $img;
    if ($img !== 'default.jpg' && file_exists($file)) {
        unlink($file);
    }
    return "Image supprimée.";
}

function ajouterImages($mysqli, $id_objet, $files) {
    $total = count($files['name']);
    $ajoutees = 0;
    for ($i = 0; $i < $total; $i++) {
        $tmp_name = $files['tmp_name'][$i];
        $name = basename($files['name'][$i]);
        $target = '../assets/images/' . $name;
        if (move_uploaded_file($tmp_name, $target)) {
            $mysqli->query("INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, '" . $mysqli->real_escape_string($name) . "')");
            $ajoutees++;
        }
    }
    return $ajoutees > 0 ? "Images ajoutées." : "Aucune image ajoutée.";
}

function getImages($mysqli, $id_objet) {
    $images = [];
    $res_img = $mysqli->query("SELECT nom_image FROM images_objet WHERE id_objet = $id_objet");
    if ($res_img) {
        while ($row = $res_img->fetch_assoc()) {
            $images[] = $row['nom_image'];
        }
        $res_img->free();
    }
    return $images;
}
?>
