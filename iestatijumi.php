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
    include iegutUrl() . "/inc/header.php";

    //vai ir "get" pieprasījums
    if ($_SERVER["REQUEST_METHOD"] != "GET") {
        header("Location: " . iegutUrl() . "/index.php");
        die();
    }

    //vai ir norādīts lietotajs
    if (!isset($_GET["id"])) {
        header("Location: index.php");
        die();
    }
    $id = test_input($_GET["id"]);

    $err = 0;
    if (isset($_GET["err"]) && !preg_match("/\d+/m", $_GET["err"])) {
        header("Location: index.php");
        die();
    } else if (!isset($_GET["err"])) {
        $err = 0;
    } else {
        $err = test_input($_GET["err"]);
    }

    //vai eksistē lietotajs ar šādu id
    $query = "SELECT * FROM `cms_accounts` WHERE `id`=$id";
    $result = $conn->query($query);

    $user = mysqli_fetch_array($result);

    if ($user == null) {
        header("Location: " . iegutUrl() . "/index.php");
        die();
    }

    ?>

    <div class="item2 large">
        <h3 class="button-text">Profila iestatījumi</h3>
    </div>

    <div class="post-item2">
        <div class="post-row">
            <?php

            //iegūst lieottāja ierakstu skaitu
            $ierakstiSql = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $user["id"] . "'";
            $ierakstiQuery = $conn->query($ierakstiSql);
            $ierakstuSkaits = mysqli_fetch_array($ierakstiQuery);

            ?>

            <img src="<?php echo iegutUrl(); ?>/lietotaju_bildes/<?php echo $user['id']; ?>.png">
            <h3><?php echo getUserName($user["id"]); ?></h3>
            <p><?php echo getUserGroup($user["id"], true); ?></p>
            <p>Ieraksti: <?php echo $ierakstuSkaits[0]; ?></p>
            <p>Reģistrējās: <?php echo laikuAtpakal($user["created_at"]); ?></p>
        </div>
    </div>

    <div class="post-item2">
        <div class="profile-container">
            <div class="item bg-white large">
                <div class="settings-container">
                    <form class="settings" method="post" action="iestatijumi/atjaunot_bildi.php" enctype="multipart/form-data">
                        <div class="span">
                            <span>
                                <label for="bilde" class="file-label">
                                    Izvelies profila bildi
                                </label>
                                <input type="file" id="bilde" name="bilde" accept="image/png">

                                <input type="hidden" name="lietotaja_id" value="<?php echo $_SESSION["data"]["id"] ?>">

                                <input type="submit" id="submitBilde" value="Atjaunot">
                            </span>
                        </div>
                    </form>
                    <hr>

                    <?php
                    //iegūst lietotājvārda maiņas kļūdas
                    if (!empty($err)) {
                        switch ($err) {
                            case 21:
                                echo ("<h3 class='error'>Lietotājvārdam ir jābūt vismaz 3 līdz 16 rakstzīmju garam!</h3>");
                                break;
                            case 22:
                                echo ("<h3 class='error'>Lietotājvārds ir aizņemts</h3>");
                                break;
                        }
                    }
                    ?>
                    <form class="settings" method="post" action="./iestatijumi/atjaunot_lietotajvardu.php">
                        <div class="span">
                            <span>
                                <input type="text" name="lietotajvards" placeholder="Ievadi jauno lietotājvārdu">
                                <input type="hidden" name="lietotaja_id" value="<?php echo $_SESSION["data"]["id"] ?>">
                                <input type="submit" name="register" value="Atjaunot">
                            </span>
                        </div>
                    </form>
                    <hr>

                    <?php
                    //iegūst epasta maiņas kļūdas
                    if (!empty($err)) {
                        switch ($err) {
                            case 31:
                                echo ("<h3 class='error'>E-pasta adresei ir jāsatur @ simbols!</h3>");
                                break;
                            case 32:
                                echo ("<h3 class='error'>Epasts ir aizņemts</h3>");
                                break;
                        }
                    }
                    ?>


                    <form class="settings" method="post" action="./iestatijumi/atjaunot_epastu.php">
                        <div class="span">
                            <span>
                                <input type="text" name="epasts" placeholder="E-Pasta adrese" autocomplete="off">

                                <input type="hidden" name="lietotaja_id" value="<?php echo $_SESSION["data"]["id"] ?>">

                                <input type="submit" name="register" value="Atjaunot">
                            </span>
                        </div>
                    </form>
                    <hr>

                    <?php
                    //iegūst paroles maiņas kļūdas
                    if (!empty($err)) {
                        switch ($err) {
                            case 41:
                                echo ("<h3 class='error'>Ievadiet jauno paroli</h3>");
                                break;
                            case 42:
                                echo ("<h3 class='error'>Paroles nesakrīt</h3>");
                                break;
                            case 43:
                                echo ("<h3 class='error'>Parole ir jābūt vismaz 8 rakstzīmju garai</h3>");
                                break;
                        }
                    }
                    ?>
                    <form class="settings"  method="post" action="./iestatijumi/atjaunot_paroli.php">
                        <div class="span">
                            <span>
                                <input type="password" name="parole1" placeholder="Parole" autocomplete="off">
                            </span>
                        </div>
                        <div class="span">
                            <span>
                                <input type="password" name="parole2" placeholder="Parole atkārtoti" autocomplete="off">
                                <input type="submit" name="register" value="Atjaunot">
                                <input type="hidden" name="lietotaja_id" value="<?php echo $_SESSION["data"]["id"] ?>">
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="item large">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>
</body>

</html>
