<?php
include("./inc/db.php");
include_once("./inc/utils.php");
include_once("./inc/config.php");
?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums</title>

    <link href="./assets/style.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <?php include "./inc/header.php"; ?>

        <div class="item large">
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["id"])) {
                $query = "SELECT * FROM `cms_categories` WHERE `id`=" . $_GET["id"];
                $result = $conn->query($query);

                $name = mysqli_fetch_array($result)["name"];
            ?>
                <div class="item large" style="width: 100%;">
                    <h3 class="button-text"><?php echo $name; ?></h3>
                    <p>
                        <a href="<?php echo $lapas_saite; ?>/create_topic.php?id=<?php echo $_GET["id"]; ?>" class="button">Izveidot rakstu</a></li>
                    </p>
                </div>

                <div class="cat-container">

                    <?php
                    $query1 = "SELECT * FROM `cms_topics` WHERE `parent_cat`=" . $_GET["id"] . " ORDER BY `posted_at` DESC";
                    $result1 = $conn->query($query1);

                    if ($result1->num_rows >= 1) {
                        while ($row1 = mysqli_fetch_array($result1)) {
                            $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                            $result2 = $conn->query($query2);
                            $user = mysqli_fetch_array($result2);

                    ?>
                            <div class="cat-item" onClick="window.location.href = '<?php echo $lapas_saite . '/view_topic.php?id=' . $row1["id"]; ?>';">
                                <div class="cat-row">
                                    <?php echo $row1["name"]; ?>
                                    <p><?php echo $user["name"] ?></p>
                                </div>
                                <div class="cat-row">
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

        <?php include "./inc/footer.php"; ?>
    </div>
</body>

</html>