<?php
include_once("../inc/utils.php");
include_once("../inc/db.php");
session_start();

//vai ir ielogojies
if (!isset($_SESSION["data"]["id"])) {
    header("Location: ../index.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["lietotajvards"])) {
    //izveido mainīgos
    $lietotaja_id = $_POST["lietotaja_id"];
    $lietotajvards = $_POST["lietotajvards"];

    //vai lietotāja sesijas id sakrīt ar norādīto id
    if ($_SESSION["data"]["id"] != $lietotaja_id) {
        header("Location: ../index.php");
        die();
    }

    $garums = strlen($lietotajvards);
    if ($garums >= 3 && $garums <= 16) {
        //iegūst lietotajus ar šādu lietotājvārdu
        $niksSql = "SELECT COUNT(*) FROM `cms_accounts` WHERE `name`='$lietotajvards';";
        $niksQuery = $conn->query($niksSql);
        $nikuSkaits = mysqli_fetch_array($niksQuery);

        //ja ir lietotāji ar šādu lietotajvārdu, izvada kļūdu
        if ($nikuSkaits[0] > 0) {
            header("Location: ../iestatijumi.php?id=$lietotaja_id&err=22");
            die();
        }

        //atjauno lietotājvārdu
        $query = "UPDATE `cms_accounts` SET `name`='$lietotajvards' WHERE `id`='$lietotaja_id';";
        $conn->query($query);

        //atjauno sesijas datus
        $_SESSION["data"]["name"] = $lietotajvards;

        header("Location: ../iestatijumi.php?id=$lietotaja_id&err=0");
        die();
    }

    header("Location: ../iestatijumi.php?id=$lietotaja_id&err=21");
    die();
}