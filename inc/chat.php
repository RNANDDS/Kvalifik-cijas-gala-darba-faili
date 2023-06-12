<?php
include("./db.php");
include_once("./utils.php");

//iegūst čata ziņū un ievieto to datubāzē
if (isset($_POST["chatText"]) || isset($_POST["chatSend"])) {
    $chatQuery = "INSERT INTO `cms_chat` (`author`, `message`) VALUES ('" . $_SESSION["data"]["id"] . "', '" . $_POST["chatText"] . "');";
    $conn->query($chatQuery);
}

//iegūst čata ziņas
$query1 = "SELECT * FROM `cms_chat` ORDER BY `posted_at` DESC;";
$result1 = $conn->query($query1);

if ($result1->num_rows >= 1) {
    //ievieto iegūtās ziņas čata laukumā
    while ($row1 = mysqli_fetch_array($result1)) {
        //iegūst ziņas autora datus
        $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
        $result2 = $conn->query($query2);
        $user = mysqli_fetch_array($result2);
?>
        <div class="cat-item">
            <div class="cat-row">
                <a href="./lietotajs.php?id=<?php echo $user["id"]; ?>"><p><?php echo getUserName($user["id"]); ?></p></a>
                <?php echo $row1["message"]; ?>
            </div>
            <div class="cat-row">
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
