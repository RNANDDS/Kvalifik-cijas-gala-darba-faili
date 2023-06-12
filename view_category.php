<?php
include_once("./inc/utils.php");
include("./inc/db.php");

?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums</title>

    <link href="<?php echo iegutUrl() ?>/assets/style.css" rel="stylesheet" />
</head>

<body>
<div class="container">
    <?php
    //ievieto header lapā
    include iegutUrl() . "/inc/header.php";
    ?>

    <div class="item large">
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["id"])) {
            //iegūst kategorijas
            $query = "SELECT * FROM `cms_categories` WHERE `id`=" . $_GET["id"];
            $result = $conn->query($query);

            //pārbauda lietotaja grupu
            $isAdminQuery = "SELECT * FROM `cms_accounts` WHERE `id`='" . $_SESSION["data"]["id"] . "';";
            $isAdminResult = $conn->query($isAdminQuery);
            $isAdminData = mysqli_fetch_array($isAdminResult);

            $data = mysqli_fetch_array($result);
            $name = $data["name"];
            ?>
            <div class="item2 large" style="width: 100%;">
                <h3 class="button-text"><?php echo $name; ?></h3>
            </div>
            <div class="item large" style="width: 100%;">
                <h3 class="button-text"></h3>
                <?php
                //vai lietotājs var izveidot rakstu
                if ($data["adminzona"] == 1 && ($isAdminData["group"] == 1 || $isAdminData["group"] == 2 || $isAdminData["group"] == 3)) {
                    ?>
                    <p>
                        <a href="<?php echo iegutUrl() ?>/create_topic.php?id=<?php echo $_GET["id"]; ?>" class="button">Izveidot rakstu</a></li>
                    </p>
                    <?php
                } elseif ($data["adminzona"] != 1) {
                    ?>
                    <p>
                        <a href="<?php echo iegutUrl() ?>/create_topic.php?id=<?php echo $_GET["id"]; ?>" class="button">Izveidot rakstu</a></li>
                    </p>
                    <?php
                }
                ?>
            </div>

            <div class="cat-container">

                <?php
                //iegūst kategorijas rakstus
                $query1 = "SELECT * FROM `cms_topics` WHERE `parent_cat`=" . $_GET["id"] . " ORDER BY `posted_at` DESC";
                $result1 = $conn->query($query1);

                if ($result1->num_rows >= 1) {
                    while ($row1 = mysqli_fetch_array($result1)) {
                        //ievieto lapā kategorijas rakstus
                        $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                        $result2 = $conn->query($query2);
                        $user = mysqli_fetch_array($result2);

                        ?>
                        <div class="cat-item" onClick="window.location.href = '<?php echo iegutUrl() . "/view_topic.php?id=" . $row1["id"]; ?>';">
                            <div class="cat-row float-left">
                                <img width="52" height="52" src="lietotaju_bildes/<?php echo $user["id"]; ?>.png" />
                            </div>
                            <div class="cat-row float-left">
                                <?php echo $row1["name"]; ?>
                                <p><?php echo getUserName($user["id"]); ?></p>
                            </div>
                            <div class="cat-row float-right">
                                Pievienots: <?php echo laikuAtpakal($row1["posted_at"]); ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>

                    <div class="cat-item">
                        <div class="cat-row">
                            Šajā kategorijā nav neviena ieraksta!
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php
            return;
        }
        ?>
    </div>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>
</body>

</html>
