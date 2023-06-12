<?php
include("./inc/db.php");
include ("./inc/config.php");
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

        <div class="item">
            <script type="text/javascript">
                var timeout = setInterval(reload, 1000);

                function reload() {
                    $('#zinas').load("inc/chat.php");
                }
            </script>

            <div class="cat-container">
                <h3>Čats</h3>

                <div style="overflow: scroll; overflow-x: hidden; height: 300px;">
                    <div id="zinas"></div>
                </div>

                <?php
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
                                <input type="submit" name="chatSend">
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
            $query = "SELECT * FROM `cms_categories` WHERE `parent` IS NULL";
            $result = $conn->query($query);

            while ($row = mysqli_fetch_array($result)) {
            ?>
                <div class="cat-container">
                    <h3><?php echo $row["name"]; ?></h3>
                    <?php
                    $query1 = "SELECT * FROM `cms_categories` WHERE `parent`=" . $row["id"];
                    $result1 = $conn->query($query1);

                    while ($row1 = mysqli_fetch_array($result1)) {
                    ?>
                        <div class="cat-item">
                            <div class="cat-row">
                                <a href="<?php echo $lapas_saite . '/view_category.php?id=' . $row1["id"]; ?>"><?php echo $row1["name"]; ?></a>
                                <p><?php echo $row1["description"]; ?></p>
                            </div>
                            <div class="cat-row">
                                <?php
                                $query2 = "SELECT * FROM `cms_topics` WHERE `parent_cat`=" . $row1["id"] . " ORDER BY `posted_at` DESC LIMIT 1";
                                $result2 = $conn->query($query2);

                                if ($result2->num_rows >= 1) {
                                    while ($row2 = mysqli_fetch_array($result2)) {
                                        $query3 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row2["author"];
                                        $result3 = $conn->query($query3);
                                        $user = mysqli_fetch_array($result3);

                                ?>
                                        <div class="cat-row">
                                            <a href="<?php echo $lapas_saite . '/view_topic.php?id=' . $row2["id"]; ?>"><?php echo $row2["name"]; ?></a>
                                            <p>Autors: <?php echo $user["name"] ?> • <?php echo laikuAtpakal($row2["posted_at"]); ?></p>
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
                $query1 = "SELECT * FROM `cms_topics` ORDER BY `posted_at` DESC LIMIT 5;";
                $result1 = $conn->query($query1);

                if ($result1->num_rows >= 1) {
                    while ($row1 = mysqli_fetch_array($result1)) {
                        $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                        $result2 = $conn->query($query2);
                        $user = mysqli_fetch_array($result2);
                ?>
                        <div class="cat-item">
                            <div class="cat-row">
                                <a href="<?php echo $lapas_saite . '/view_topic.php?id=' . $row1["id"]; ?>"><?php echo $row1["name"]; ?></a>
                                <p>Autors: <?php echo $user["name"] ?> • <?php echo laikuAtpakal($row1["posted_at"]); ?></p>
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
        </div>

        <?php include "./inc/footer.php"; ?>
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