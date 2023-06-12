<?php
include_once("./inc/utils.php");
include(iegutUrl() . "/inc/db.php");

//mainīgie
$lietotaja_id = "";
$nosaukums = "";
$cat_id = "";
$editor_data = "";
$post = "";
$kludas = isset($_GET["kludas"]) ? 1 : 0;
$topic = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //izveido mainīgos no pieprasījuma datiem
    $lietotaja_id = $_SESSION["data"]["id"];
    $editor_data = $_POST["editor1"];
    $post = $_POST["post"];
    $topic = $_POST["topic"];
    $kludas = 0;

    //vai saturs ir tukšs
    if (empty($editor_data)) {
        $kludas = 1;
    }

    //ja nav kļūdu, atjauno ierakstu
    if ($kludas == 0) {
        $conn->query("UPDATE `cms_posts` SET `content`='$editor_data' WHERE `id`='$post' AND `main`=0;");

        header("Location: ./view_topic.php?id=$topic");
    } else {
        header("Location: ./edit_topic.php?post=$post&kludas=$kludas");
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $post = $_GET["post"];
    $topic = $_GET["topic"];

    if ($post == 0 || $post == null) {
        header("Location: ./index.php");
        die();
    }
} else {
    header("Location: ./index.php");
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
    <?php
    include iegutUrl() . "/inc/header.php";
    $check_query = "SELECT * FROM `cms_posts` WHERE `id`=$post;";
    $result = $conn->query($check_query);
    $data = mysqli_fetch_array($result);

    if ($data["author"] != $_SESSION["data"]["id"]) {
        header("Location: ./index.php");
        die();
    }

    ?>

    <div class="item2 large">
        <h3 class="button-text">Ieraksta labošana</h3>
    </div>

    <div class="item box large">
        <?php
        if ($kludas == 1) {
            echo ("<h3 class='error'>Lūdzu pārliecinies vai visi lauki ir aizpildīti!</h3>");
        }
        ?>

        <form method="post" class="form-reply" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php
            $query = "SELECT * FROM `cms_posts` WHERE `id`='$post';";
            $post_data = mysqli_fetch_array($conn->query($query));

            ?>
            <textarea name="editor1"><?php echo $post_data["content"]; ?></textarea>
            <?php
            ?>

            <input type="hidden" name="post" value="<?php echo $post; ?>" />
            <input type="hidden" name="topic" value="<?php echo $topic; ?>" />
            <input type="submit" name="submit" value="Labot ierakstu">

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

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>
</body>

</html>