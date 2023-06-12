<?php
include_once("./inc/utils.php");
include("./inc/db.php");

// Pārbauda vai pieprasījums ir "post" un ieraksta lauks ir tukšs
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST["editor1"])) {
    // Ja ir, nosūta uz raksta lapu
    $topic_id = $_POST["topic"];
    header("Location: view_topic.php?id=$topic_id");
    die();
}

// Pārbauda vai pieprasījums ir "post" un ieraksta lauks nav tukšs
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["editor1"])) {
    // Iegūst mainīgo datus no pieprasījuma
    $lietotaja_id = $_POST["id"];
    $topic_id = $_POST["topic"];

    // Notīra liekās atstarpes un html tagus no satura
    $editor_data = $_POST["editor1"];

    $clear = strip_tags($editor_data);
    $clear = html_entity_decode($clear);
    $clear = urldecode($clear);
    $clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
    $clear = preg_replace('/ +/', ' ', $clear);
    $clear = trim($clear);

    // Ja saturs ir īsāks par 10 rakstzīmēm, pārvirsa uz raksta lapu
    if (strlen($clear) < 10) {
        header("Location: view_topic.php?id=$topic_id");
        die();
    }

    // Iegūst lietotāja ierakstu skaitu
    $query3 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $lietotaja_id . "'";
    $result3 = $conn->query($query3);
    $user1 = mysqli_fetch_array($result3);

    // Atkarībā no ierakstu skaita nomaina title
    switch ($user1[0]) {
        case 3:
            $updateQuery = "UPDATE `cms_accounts` SET `title`='Bebrs' WHERE `id`=$lietotaja_id;";
            $conn->query($updateQuery);
            break;
        case 30:
            $updateQuery = "UPDATE `cms_accounts` SET `title`='Aktīvais' WHERE `id`=$lietotaja_id;";
            $conn->query($updateQuery);
            break;
        case 80:
            $updateQuery = "UPDATE `cms_accounts` SET `title`='Savējais' WHERE `id`=$lietotaja_id;";
            $conn->query($updateQuery);
            break;
        case 130:
            $updateQuery = "UPDATE `cms_accounts` SET `title`='Pazīstamais' WHERE `id`=$lietotaja_id;";
            $conn->query($updateQuery);
            break;
        case 200:
            $updateQuery = "UPDATE `cms_accounts` SET `title`='Veterāns' WHERE `id`=$lietotaja_id;";
            $conn->query($updateQuery);
            break;
    }

    //Ievieto ieraksta datus datu bāzē un nosūta uz raksta lapu
    $conn->query("INSERT INTO `cms_posts`(`content`, `author`, `topic`) VALUES ('$editor_data', '$lietotaja_id', '$topic_id');");
    header("Location: view_topic.php?id=$topic_id");
}

?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums</title>

    <link href="<?php echo iegutUrl() ?>/assets/style.css" rel="stylesheet" />

    <!-- ckeditor ievietošana lapā -->
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
</head>

<body>
<div class="post-container">
    <?php
    //Header ievietošana lapā
    include iegutUrl() . "/inc/header.php";
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["id"])) {
        //raksta info iegūšana no pieprasījumā norādītā raksta id
        $query = "SELECT * FROM `cms_topics` WHERE `id`=" . $_GET["id"];
        $result = $conn->query($query);
        $data = mysqli_fetch_array($result);

        // raksta datu mainīgo izveide
        $name = $data["name"];
        $author = $data["author"];
        $locked = $data["locked"];

        // lietotāja grupas pārbaude
        $isAdminQuery = "SELECT * FROM `cms_accounts` WHERE `id`='" . $_SESSION["data"]["id"] . "';";
        $isAdminResult = $conn->query($isAdminQuery);
        $isAdminData = mysqli_fetch_array($isAdminResult);
        ?>

        <div class="item2 large" style="width: 100%;">
            <h3 class="button-text"><?php echo $name; ?></h3>
        </div>

        <?php
        //iegūst raksta ierakstus
        $query1 = "SELECT * FROM `cms_posts` WHERE `topic`=" . $_GET["id"] . " ORDER BY `posted_at` ASC";
        $result1 = $conn->query($query1);

        if ($result1->num_rows >= 1) {
            while ($row1 = mysqli_fetch_array($result1)) {
                //ievieto ierakstus lapā
                $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                $result2 = $conn->query($query2);
                $user = mysqli_fetch_array($result2);

                ?>
                <div class="post-item2">
                    <?php

                    //iegūst ieraksta autora datus
                    $query3 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $row1["author"] . "'";
                    $result3 = $conn->query($query3);
                    $user1 = mysqli_fetch_array($result3);

                    // $posts[n] = {lietotaja_id}:{ierakstu_skaits}
                    //$posts[] = "" . $row1["id"] . ":" . $user[0];
                    $posts = array();
                    $posts[] = array(
                        "id" => $row1["author"],
                        "ieraksti" => $user1[0]
                    );

                    usort($posts, function ($a, $b) {
                        return $b['ieraksti'] <=> $a['ieraksti'];
                    });

                    for ($i = 0; $i < count($posts); $i++) {
                        $lietotajaId = $posts[$i]["id"];
                        $ierakstuSkaits = $posts[$i]["ieraksti"];

                        // Iegūst lietotāja info un ievieto to lapā
                        $query1 = "SELECT * FROM `cms_accounts` WHERE `id`='$lietotajaId';";
                        $result = $conn->query($query1);
                        $user = mysqli_fetch_array($result);

                        ?>
                        <div class="post-row">
                            <p class="center"><?php echo $user["title"]; ?></p>
                            <img src="<?php echo iegutUrl() ?>/lietotaju_bildes/<?php echo $user['id']; ?>.png">
                            <h3><?php echo getUserName($user["id"]); ?></h3>
                            <p><?php echo getUserGroup($user["id"], true); ?></p>
                            <p>Ieraksti: <?php echo $ierakstuSkaits; ?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="post-item2">
                    <div class="post-row" style="width: 100%;" id="<?php echo $row1["id"]; ?>">
                        <div class="item large">
                            <p><?php echo laikuAtpakal($row1["posted_at"]); ?></p>
                            <p class="margins">

                                <?php
                                //ierakstu kontroles
                                if ($row1["main"] == 1) {
                                    if ($author == $_SESSION["data"]["id"]) {
                                        ?>
                                        <a href="<?php echo iegutUrl() ?>/edit_topic.php?topic=<?php echo $_GET["id"]; ?>">Labot rakstu</a>
                                        <?php
                                    }

                                    if ($isAdminData["group"] == 1 || $isAdminData["group"] == 2 || $isAdminData["group"] == 3) {
                                        ?>
                                        <a href="<?php echo iegutUrl() ?>/delete_topic.php?id=<?php echo $_GET["id"]; ?>">Dzēst rakstu</a>
                                        <?php
                                        if ($locked == 0) {
                                            ?>
                                            <a href="<?php echo iegutUrl() ?>/lock_topic.php?id=<?php echo $_GET["id"]; ?>">Aizslēgt rakstu</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php echo iegutUrl() ?>/unlock_topic.php?id=<?php echo $_GET["id"]; ?>">Atslēgt rakstu</a>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                    }
                                } else {
                                    if ($row1["author"] == $_SESSION["data"]["id"]) { ?>
                                        <a href="<?php echo iegutUrl() ?>/edit_post.php?post=<?php echo $row1["id"]; ?>&topic=<?php echo $_GET["id"]; ?>">Labot ierakstu</a>
                                        <a href="<?php echo iegutUrl() ?>/delete_post.php?id=<?php echo $row1["id"]; ?>&topic=<?php echo $_GET["id"]; ?>">Dzēst ierakstu</a>
                                        <?php
                                    }

                                    if ($isAdminData["group"] == 1 || $isAdminData["group"] == 2 || $isAdminData["group"] == 3) { ?>
                                        <a href="<?php echo iegutUrl() ?>/delete_post.php?id=<?php echo $row1["id"]; ?>&topic=<?php echo $_GET["id"]; ?>">Dzēst ierakstu</a>
                                        <?php
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <?php
                        echo $row1["content"];
                        ?>
                    </div>
                </div>
                <?php
                if ($row1["main"] == 1) {
                    ?>
                    <div class="item large">
                        <hr>
                        <h4 class="button-text">Komentāri</h4>
                    </div>
                    <?php
                }
            }
        }
        ?>

        <div class="item large">
            <?php
            //pārbauda vai drīkst atstāt komentāru rakstā
            $query1 = "SELECT * FROM `cms_topics` WHERE `id`=" . $_GET["id"];
            $result1 = $conn->query($query1);
            $data1 = mysqli_fetch_array($result1);
            $locked = $data1["locked"];

            $show = false;

            if ($locked != 1 || ($isAdminData["group"] == 1 || $isAdminData["group"] == 2 || $isAdminData["group"] == 3)) $show = true;

            if ($show) {
                ?>
                <form method="post" class="form-reply" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <textarea name="editor1"></textarea>

                    <input type="submit" name="submit" value="Pievienot ierakstu">

                    <input type="hidden" name="id" value="<?php echo $_SESSION["data"]["id"]; ?>" />
                    <input type="hidden" name="topic" value="<?php echo $_GET["id"]; ?>" />

                    <script>
                        CKEDITOR.replace('editor1', {
                            height: 150,
                            contentsCss: [
                                'http://cdn.ckeditor.com/4.20.1/full-all/contents.css',
                                'https://ckeditor.com/docs/ckeditor4/4.20.1/examples/assets/css/classic.css'
                            ],
                            removeButtons: 'PasteFromWord'
                        });
                    </script>
                </form>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>
</body>

</html>
