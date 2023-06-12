<?php
include_once("./inc/utils.php");
include_once("./inc/db.php");
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
<div class="post-container">
    <?php
    //ievieto header
    include iegutUrl() . "/inc/header.php";

    if ($_SERVER["REQUEST_METHOD"] != "GET") {
        header("Location: " . iegutUrl() . "/index.php");
        die();
    }

    $id = test_input($_GET["id"]);

    //vai parametrs ir cipars
    if (!preg_match("/\d+/m", $id)) {
        header("Location: " . iegutUrl() . "/index.php");
        die();
    }

    //iegūst lietotāja datus
    $query = "SELECT * FROM `cms_accounts` WHERE `id`=$id";
    $result = $conn->query($query);

    $user = mysqli_fetch_array($result);

    //ja nav atrasts, novirza uz sākumlapu
    if ($user == null) {
        header("Location: " . iegutUrl() . "/index.php");
        die();
    }

    ?>

    <div class="item large">
        <h3 class="button-text">Lietotāja informācija</h3>
    </div>

    <div class="post-item2">
        <div class="post-row">
            <?php

            //iegūst lietotāja ierakstu skaitu
            $ierakstiSql = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $user["id"] . "'";
            $ierakstiQuery = $conn->query($ierakstiSql);
            $ierakstuSkaits = mysqli_fetch_array($ierakstiQuery);

            ?>

            <p class="center"><?php echo $user["title"]; ?></p>
            <img src="<?php echo iegutUrl(); ?>/lietotaju_bildes/<?php echo $user['id']; ?>.png">
            <h3><?php echo getUserName($user["id"]); ?></h3>
            <p><?php echo getUserGroup($user["id"], true); ?></p>
            <p>Ieraksti: <?php echo $ierakstuSkaits[0]; ?></p>
            <p>Reģistrējās: <?php echo laikuAtpakal($user["created_at"]); ?></p>
        </div>
    </div>

    <div class="post-item2">
        <div class="profile-container">
            <div class="item bg-white">
                <h3>Lietotāja tēmas</h3>

                <?php
                //iegūst lietotāja veidotos rakstus
                $query1 = "SELECT * FROM `cms_topics` WHERE `author`=" . $user["id"] . " ORDER BY `posted_at` DESC LIMIT 5;";
                $result1 = $conn->query($query1);

                if ($result1->num_rows >= 1) {
                    while ($row2 = mysqli_fetch_array($result1)) {
                        ?>
                        <div class="profile-item">
                            <a href="<?php echo iegutUrl() . "/view_topic.php?id=" . $row2["id"]; ?>"><?php echo $row2["name"]; ?></a>
                            <p><?php echo laikuAtpakal($row2["posted_at"]); ?></p>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="profile-item">
                        Lietotājs nav izveidojis nevienu tēmu
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="item bg-white">
                <h3>Ieraksti</h3>

                <?php
                //iegūst lietotāja veidotos ierakstus
                $query2 = "SELECT * FROM `cms_posts` WHERE `author`=" . $user["id"] . " ORDER BY `posted_at` LIMIT 5;";
                $result2 = $conn->query($query2);

                if ($result2->num_rows >= 1) {
                    while ($row2 = mysqli_fetch_array($result2)) {
                        ?>
                        <div class="profile-item">
                            <a href="<?php echo iegutUrl() . "/view_topic.php?id=" . $row2["topic"] . "#{$row2['id']}"; ?>">
                                <?php echo $row2["content"]; ?>
                            </a>
                            <p><?php echo laikuAtpakal($row2["posted_at"]); ?></p>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="profile-item">
                        Lietotājs nav izveidojis nevienu ierakstu
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="item large">
    </div>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>
</body>

</html>
