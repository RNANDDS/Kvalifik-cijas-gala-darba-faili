<?php
include_once("../inc/utils.php");
include_once("../inc/db.php");
session_start();

//vai ir ielogojies
if (!isset($_SESSION["data"]["id"])) {
    header("Location: ../index.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["parole1"])) {
    //norāda mainīgos
    $lietotaja_id = $_POST["lietotaja_id"];
    $par1 = $_POST["parole1"];
    $par2 = $_POST["parole2"];

    //vai lietotāja sesijas id sakrīt ar norādīto id
    if ($_SESSION["data"]["id"] != $lietotaja_id) {
        header("Location: ../index.php");
        die();
    }

    //vai abi paroļu lauki ir aizpildīti
    if (isset($par1) || isset($par2)) {
        //vai paroles ir vienādas
        if ($par1 != $par2) {
            header("Location: ../iestatijumi.php?id=$lietotaja_id&err=42");
            die();
        }

        //vai parole ir vismaz 8 rakstzīmju gara
        if (strlen($par1) < 8) {
            header("Location: ../iestatijumi.php?id=$lietotaja_id&err=43");
            die();
        }

        //atjauno paroli
        $query = "UPDATE `cms_accounts` SET `password`='" . md5($par1) . "' WHERE `id`='$lietotaja_id';";
        $conn->query($query);

        //iznīcina sesiju
        header("Location: ../inc/logout.php");
        die();
    }

    header("Location: ../iestatijumi.php?id=$lietotaja_id&err=41");
    die();
}