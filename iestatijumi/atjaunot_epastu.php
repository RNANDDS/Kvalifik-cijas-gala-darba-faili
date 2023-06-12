<?php
include_once("../inc/utils.php");
include_once("../inc/db.php");
session_start();

//vai lietotajs ir ielogojies
if (!isset($_SESSION["data"]["id"])) {
    header("Location: ../index.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["epasts"])) {
    //izveido mainīgos
    $lietotaja_id = $_POST["lietotaja_id"];
    $epasts = $_POST["epasts"];

    //vai lietotaja sesijas id sakrīt ar norādīto lietotāju
    if ($_SESSION["data"]["id"] != $lietotaja_id) {
        header("Location: ../index.php");
        die();
    }

    //vai ir epasts
    $irEpasts = filter_var($epasts, FILTER_VALIDATE_EMAIL);
    if (str_contains($irEpasts, "@")) {
        $epastsSql = "SELECT COUNT(*) FROM `cms_accounts` WHERE `email`='$epasts';";
        $epastsQuery = $conn->query($epastsSql);
        $epastuSkaits = mysqli_fetch_array($epastsQuery);

        //vai ir lietotaji ar ādu epastu
        if ($epastuSkaits[0] > 0) {
            header("Location: ../iestatijumi.php?id=$lietotaja_id&err=32");
            die();
        }

        //atjauno lietotāja epastu
        $query = "UPDATE `cms_accounts` SET `email`='$epasts' WHERE `id`='$lietotaja_id';";
        $conn->query($query);

        //atjauno lietotaja sesijas epastu
        $_SESSION["data"]["email"] = $epasts;

        header("Location: ../iestatijumi.php?id=$lietotaja_id&err=0");
        die();
    }

    header("Location: ../iestatijumi.php?id=$lietotaja_id&err=31");
    die();
}