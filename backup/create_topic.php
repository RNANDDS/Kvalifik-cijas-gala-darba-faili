<?php
include("./inc/db.php");
include_once("./inc/utils.php");

$lietotaja_id = "";
$nosaukums = "";
$cat_id = "";
$editor_data = "";
$kludas = isset($_GET["kludas"]) ? 1 : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lietotaja_id = $_POST["id"];
    $nosaukums = $_POST["virsraksts"];
    $cat_id = $_POST["cat"];
    $editor_data = $_POST["editor1"];
    $kludas = 0;

    if (empty($nosaukums) || empty($editor_data)) {
        $kludas = 1;
    }

    if ($kludas == 0) {
        $conn->query("INSERT INTO `cms_topics`(`name`, `author`, `parent_cat`) VALUES ('$nosaukums', '$lietotaja_id', '$cat_id');");
        $topic_id = $conn->insert_id;
        $conn->query("INSERT INTO `cms_posts`(`content`, `author`, `topic`) VALUES ('$editor_data', '$lietotaja_id', '$topic_id');");

        header("Location: view_topic.php?id=$topic_id");
    } else {
        header("Location: create_topic.php?id=$cat_id&kludas=$kludas");
    }
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

        <div class="item box large">
            <?php
            if ($kludas == 1) {
                echo ("<h3 class='error'>Lūdzu pārliecinies vai visi lauki ir aizpildīti!</h3>");
            }
            ?>

            <form method="post" class="form-reply" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input class="new-post-input" type="text" name="virsraksts" placeholder="Ievadi virsrakstu">

                <textarea name="editor1"></textarea>

                <input type="submit" name="submit" value="Pievienot ierakstu">

                <input type="hidden" name="id" value="<?php echo $_SESSION["data"]["id"]; ?>" />
                <input type="hidden" name="cat" value="<?php echo $_GET["id"]; ?>" />

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

        <?php include "./inc/footer.php"; ?>
    </div>
</body>

</html>