<?php
session_start();
include_once("./inc/utils.php");
include_once("./inc/db.php");

//vai ir ielogjies
if (!isset($_SESSION["data"]["name"])) {
    header("Location: " . iegutUrl() . "/login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //izveido mainīgos
    $id = $_GET["id"];
    $topic = $_GET["topic"];
    $check_query = "SELECT * FROM `cms_posts`;";
    $result = $conn->query($check_query);
    $data = mysqli_fetch_array($result);

    //vai ir administrators
    $isAdminQuery = "SELECT * FROM `cms_accounts` WHERE `id`='" . $_SESSION["data"]["id"] . "';";
    $isAdminResult = $conn->query($isAdminQuery);
    $isAdminData = mysqli_fetch_array($isAdminResult);

    //ja nav autors vai administrators
    if ($data["author"] != $_SESSION["data"]["id"] && ($isAdminData["group"] == 1 || $isAdminData["group"] == 2 || $isAdminData["group"] == 3))  {
        header("Location: ./index.php");
        die();
    }

    //dzēš ierakstu
    $post_query = "DELETE FROM `cms_posts` WHERE `id`=$id";

    $conn->query($post_query);

    header("Location: ./view_topic.php?id=$topic");
}