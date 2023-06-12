<?php
include("./inc/db.php");
include_once("./inc/utils.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["editor1"])) {
    $lietotaja_id = $_POST["id"];
    $topic_id = $_POST["topic"];
    $editor_data = $_POST["editor1"];

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

    <link href="./assets/style.css" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
</head>

<body>
    <div class="post-container">
        <?php include "./inc/header.php"; ?>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["id"])) {
            $query = "SELECT * FROM `cms_topics` WHERE `id`=" . $_GET["id"];
            $result = $conn->query($query);

            $name = mysqli_fetch_array($result)["name"];
        ?>

            <div class="item large" style="width: 100%;">
                <h3 class="button-text"><?php echo $name; ?></h3>
            </div>
            <?php
            $query1 = "SELECT * FROM `cms_posts` WHERE `topic`=" . $_GET["id"] . " ORDER BY `posted_at` ASC";
            $result1 = $conn->query($query1);

            if ($result1->num_rows >= 1) {
                while ($row1 = mysqli_fetch_array($result1)) {
                    $query2 = "SELECT * FROM `cms_accounts` WHERE `id`=" . $row1["author"];
                    $result2 = $conn->query($query2);
                    $user = mysqli_fetch_array($result2);

            ?>
                    <div class="post-item2">
                        <?php
                        $posts = array();

                        $query3 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $row1["author"] . "'";
                        $result3 = $conn->query($query3);
                        $user1 = mysqli_fetch_array($result3);

                        // $posts[n] = {lietotaja_id}:{ierakstu_skaits}
                        //$posts[] = "" . $row1["id"] . ":" . $user[0];
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

                            $query1 = "SELECT * FROM `cms_accounts` WHERE `id`='$lietotajaId';";
                            $result = $conn->query($query1);
                            $user = mysqli_fetch_array($result);

                        ?>
                            <div class="post-row">
                                <img src="lietotaju_bildes/<?php echo $user['id']; ?>.png">
                                <h3><?php echo $user["name"]; ?></h3>
                                <p><?php echo getUserGroup($user["id"], true); ?></p>
                                <p>Ieraksti: <?php echo $ierakstuSkaits; ?></p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="post-item2">
                        <div class="post-row">
                            <p><?php echo laikuAtpakal($row1["posted_at"]); ?></p>
                            <?php echo $row1["content"]; ?>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

            <div class="item large">
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
            </div>
        <?php
        }
        ?>

        <?php include "./inc/footer.php"; ?>
    </div>
</body>

</html>