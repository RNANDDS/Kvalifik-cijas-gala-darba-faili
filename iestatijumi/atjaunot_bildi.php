<?php
session_start();
if (!isset($_SESSION["data"]["id"])) {
    header("Location: ../login.php");
    die();
}

print_r($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["bilde"])) {
    $lietotaja_id = $_POST["lietotaja_id"];
    $image_file = $_FILES["bilde"];

    if (!isset($image_file)) {
        die('nav norādīta bilde');
    }

    if (filesize($image_file["tmp_name"]) <= 0) {
        die('bildei nav satura');
    }

    $image_type = exif_imagetype($image_file["tmp_name"]);
    if (!$image_type) {
        die('fails nav bilde');
    }

    $image_name = "$lietotaja_id.png";

    //saglabā bildi
    move_uploaded_file($image_file["tmp_name"], __DIR__ . "/../lietotaju_bildes/" . $image_name);

    //novirza lietotāju uz iestatījumu lapu
    header("Location: ../iestatijumi.php?id=$lietotaja_id&err=0");
}