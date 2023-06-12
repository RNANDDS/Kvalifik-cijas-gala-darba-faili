<?php
include("./inc/utils.php");
include("./inc/db.php");

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
    <?php

    //ievieto header
    include iegutUrl() . "/inc/header.php"; ?>

    <div class="item">
        <script type="text/javascript">
            // atjauno čatu reizi sekundē
            var timeout = setInterval(reload, 1000);

            function reload() {
                $('#zinas').load("./inc/chat.php");
            }
        </script>

        <div class="cat-container">
            <h3>Čats</h3>

            <div style="overflow: scroll; overflow-x: hidden; height: 300px;">
                <div id="zinas"></div>
            </div>

            <?php
            //ja ir ielogojies, ievieto čata ievades lodziņu
            if (isset($_SESSION["data"])) {
                if (isset($_POST["chatText"]) || isset($_POST["chatSend"])) {
                    $chatQuery = "INSERT INTO `cms_chat` (`author`, `message`) VALUES ('" . $_SESSION["data"]["id"] . "', '" . $_POST["chatText"] . "');";
                    $conn->query($chatQuery);
                }
                ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="cat-item" style="transition: none; background-color: lightgray;">
                            <span style="display: inline-flex; width: 100%; height: auto;">
                                <input type="text" style="width: 100%;" name="chatText" placeholder="Jūsu ziņojums..." autocomplete="off">
                                <input type="submit" name="chatSend" value="Sūtīt">
                            </span>
                    </div>
                </form>

                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
                <?php
            }
            ?>
        </div>

        <?php
        //iegūst kategorijas
        $query = "SELECT * FROM `cms_categories` WHERE `parent` IS NULL";
        $result = $conn->query($query);

        //izvada visas kategorijas
        while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="cat-container">
                <h3><?php echo $row["name"]; ?></h3>
                <?php

                //iegūst kategorijas ar "parent" kategoriju
                $query1 = "SELECT * FROM `cms_categories` WHERE `parent`=" . $row["id"];
                $result1 = $conn->query($query1);

                //ivada kategorijas ar "parent" kategoriju
                while ($row1 = mysqli_fetch_array($result1)) {
                    ?>
                    <div class="cat-item">
                        <div class="cat-row float-left">
                            <a href="<?php echo iegutUrl() . "/view_category.php?id=" . $row1["id"]; ?>"><?php echo $row1["name"]; ?></a>
                            <p><?php echo $row1["description"]; ?></p>
                        </div>
                        <div class="cat-row float-right">
                            <?php
                            //iegūst kategorijas info
                            $query2 = "SELECT * FROM `cms_topics` WHERE `parent_cat`=" . $row1["id"] . " ORDER BY `posted_at` DESC LIMIT 1";
                            $result2 = $conn->query($query2);

                            if ($result2->num_rows >= 1) {
                                while ($row2 = mysqli_fetch_array($result2)) {
                                    $query3 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row2["author"];
                                    $result3 = $conn->query($query3);
                                    $user = mysqli_fetch_array($result3);

                                    ?>
                                    <div class="cat-row">
                                        <a href="<?php echo iegutUrl() . "/view_topic.php?id=" . $row2["id"]; ?>"><?php echo $row2["name"]; ?></a>
                                        <div class="limit">
                                            <p>Autors: <a href="./lietotajs.php?id=<?php echo $user["id"]; ?>"><?php echo getUserName($user["id"]); ?></a> • <?php echo laikuAtpakal($row2["posted_at"]); ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="cat-row">
                                    Nav jaunu rakstu
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="item">
        <div class="cat-container">
            <h3>Jaunākie ieraksti</h3>
            <?php
            //iegūst ierakstu datus
            $query1 = "SELECT * FROM `cms_topics` ORDER BY `posted_at` DESC LIMIT 5;";
            $result1 = $conn->query($query1);

            //ja ir ieraksti, izvada to datus
            if ($result1->num_rows >= 1) {
                while ($row1 = mysqli_fetch_array($result1)) {
                    $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                    $result2 = $conn->query($query2);
                    $user = mysqli_fetch_array($result2);
                    ?>
                    <div class="cat-item">

                        <div class="cat-row float-left">
                            <img width="52" height="52" src="lietotaju_bildes/<?php echo $user["id"]; ?>.png" />
                        </div>
                        <div class="cat-row">
                            <a href="<?php echo iegutUrl() . "/view_topic.php?id=" . $row1["id"]; ?>"><?php echo $row1["name"]; ?></a>
                            <div class="limit">
                                <p>Autors: <a href="./lietotajs.php?id=<?php echo $user["id"]; ?>"><?php echo getUserName($user["id"]); ?></a> • <?php echo laikuAtpakal($row1["posted_at"]); ?></p>
                            </div>
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

        <div class="cat-container">
            <h3>Ziņojumu TOPS</h3>

            <?php
            $posts = array();

            //iegūst lietotājus pēc lielākā ierakstu skaita
            $query1 = "SELECT * FROM `cms_accounts` a ORDER BY (SELECT COUNT(*) FROM `cms_posts` p WHERE p.author = a.id);";
            $result1 = $conn->query($query1);

            if ($result1->num_rows >= 1) {
                while ($row1 = mysqli_fetch_array($result1)) {
                    $query2 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $row1["id"] . "';";
                    $result2 = $conn->query($query2);
                    $user = mysqli_fetch_array($result2);

                    // $posts[n] = {lietotaja_id}:{ierakstu_skaits}
                    //$posts[] = "" . $row1["id"] . ":" . $user[0];
                    $posts[] = array(
                        "id" => $row1["id"],
                        "ieraksti" => $user[0]
                    );
                }
            }

            usort($posts, function($a, $b) {
                return $b['ieraksti'] <=> $a['ieraksti'];
            });

            //izvada top 5 lietotajus ar lielāko ierakstu skaitu
            for ($i = 0; $i < 5; $i++) {
                $lietotajaId = $posts[$i]["id"];
                $ierakstuSkaits = $posts[$i]["ieraksti"];

                $query1 = "SELECT * FROM `cms_accounts` WHERE `id`='$lietotajaId';";
                $result = $conn->query($query1);
                $user = mysqli_fetch_array($result);

                ?>
                <div class="cat-item">
                    <div class="cat-row float-left">
                        <img width="52" height="52" src="lietotaju_bildes/<?php echo $user["id"]; ?>.png" />
                    </div>
                    <div class="cat-row float-left">
                        <a href="./lietotajs.php?id=<?php echo $user["id"]; ?>"><?php echo getUserName($user["id"]); ?></a>
                        <p><?php echo getUserGroup($user["id"], true); ?></p>
                        <p>Ieraksti: <?php echo $ierakstuSkaits; ?></p>
                    </div>
                    <div class="cat-row float-right">
                        #<?php echo $i + 1; ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>

<script>
    function myFunction() {
        var popup = document.getElementById("userPopup");
        popup.classList.toggle("show");
    }
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</body>

</html>
