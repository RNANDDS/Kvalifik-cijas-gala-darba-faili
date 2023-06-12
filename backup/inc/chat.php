<?php
include("db.php");
include_once("utils.php");

if (isset($_POST["chatText"]) || isset($_POST["chatSend"])) {
    $chatQuery = "INSERT INTO `cms_chat` (`author`, `message`) VALUES ('" . $_SESSION["data"]["id"] . "', '" . $_POST["chatText"] . "');";
    $conn->query($chatQuery);
}

$query1 = "SELECT * FROM `cms_chat` ORDER BY `posted_at` DESC;";
$result1 = $conn->query($query1);

if ($result1->num_rows >= 1) {
    while ($row1 = mysqli_fetch_array($result1)) {
        $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
        $result2 = $conn->query($query2);
        $user = mysqli_fetch_array($result2);
?>
        <div class="cat-item">
            <div class="cat-row">
                <?php echo $row1["message"]; ?>
                <p><?php echo $user["name"]; ?></p>
            </div>
            <div class="cat-row">
                <!--<p><?php echo date("d/m h.i", strtotime($row1["posted_at"])); ?></p>-->
                <p><?php echo laikuAtpakal($row1["posted_at"]); ?></p>
            </div>
        </div>
    <?php
    }
} else {
    ?>

    <div class="cat-item">
        <div class="cat-row">
            Čats ir tukšs :(
        </div>
    </div>
<?php
}
?>