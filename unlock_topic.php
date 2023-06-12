<?php
session_start();
include_once("./inc/utils.php");
include_once("./inc/db.php");

//vai lietotajs ir ielogojies
if (!isset($_SESSION["data"]["name"])) {
    header("Location: " . iegutUrl() . "/login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // iegūst ieraksta datus
    $id = $_GET["id"];
    $check_query = "SELECT * FROM `cms_topics`;";
    $result = $conn->query($check_query);
    $data = mysqli_fetch_array($result);

    //pārvauda lietotāja grupu
    $isAdminQuery = "SELECT * FROM `cms_accounts` WHERE `id`='" . $_SESSION["data"]["id"] . "';";
    $isAdminResult = $conn->query($isAdminQuery);
    $isAdminData = mysqli_fetch_array($isAdminResult);

    //if ($isAdminData["group"] != 1 || $isAdminData["group"] != 2 || $isAdminData["group"] != 3) {
    //    header("Location: ./index.php");
    //    die();
    //}

    //ja nav administrators, nosūta uz sākumlapu
    switch ($isAdminData["group"]) {
        case 1:
        case 2:
        case 3:
            break;
        default:
            header("Location: ./index.php");
            die();
    }

    //atslēdz rakstu
    $topic_query = "UPDATE `cms_topics` SET `locked`=0 WHERE `id`=$id;";
    $conn->query($topic_query);

    header("Location: ./view_topic.php?id=$id");
}