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
    <?php include iegutUrl() . "/inc/header.php"; ?>

    <div class="item2 large" style="width: 100%;">
        <h3 class="button-text">STATISTIKA</h3>
    </div>

    <div class="item">
        <div class="cat-container">
            <?php
            $results_per_page = 10;

            //iegūst lietotāju skaitu
            $query = "select * from cms_accounts";
            $result = $conn->query($query);
            $number_of_result = mysqli_num_rows($result);

            ?>

            <h3>Mājaslapā reģistrētie lietotāji - <?php echo $number_of_result; ?></h3>

            <?php

            //noskaidro pieejamās lapas
            $number_of_page = ceil($number_of_result / $results_per_page);

            //noskaidro kurā lapā ir lietotajs
            if (!isset($_GET['ap'])) $page = 1;
            else $page = $_GET['ap'];

            //noskaidro sql query limitus datu iegūšanai
            $page_first_result = ($page - 1) * $results_per_page;

            //iegūst attiecīgās lapas lietotajus
            $query = "SELECT * FROM cms_accounts LIMIT $page_first_result,$results_per_page";
            $result = mysqli_query($conn, $query);

            //izvada attiecīgās lapas info
            if ($result->num_rows > 0) {
                while ($row1 = mysqli_fetch_array($result)) {
                    $query2 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`=" . $row1["id"];
                    $result2 = $conn->query($query2);
                    $user = mysqli_fetch_array($result2);

                    ?>
                    <div class="cat-item">
                        <div class="cat-row float-left">
                            <img width="52" height="52" src="lietotaju_bildes/<?php echo $row1["id"]; ?>.png" />
                        </div>
                        <div class="cat-row float-left">
                            <a href="./lietotajs.php?id=<?php echo $row1["id"]; ?>"><?php echo getUserName($row1["id"]); ?></a>
                            <p><?php echo getUserGroup($row1["id"], true); ?></p>
                            <p>Ieraksti: <?php echo ($user != null) ? $user[0] : 0; ?></p>
                        </div>
                        <div class="cat-row float-right">
                            Reģistrējās: <?php echo laikuAtpakal($row1["created_at"]); ?>
                        </div>
                    </div>
                    <?php
                }
            }

            ?>
            <div class="cat-item">
                <ul>
                    <?php
                    for($page = 1; $page<= $number_of_page; $page++) {
                        ?>
                        <li class="li-left"><a <?php echo isActive("statistika.php?ap=$page"); ?> href="<?php echo iegutUrl() ?>/statistika.php?ap=<?php echo $page; ?>"><?php echo $page; ?></a></li>

                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="item">
        <div class="cat-container">
            <h3>Administrācijas biedri</h3>

            <?php
            $posts = array();

            //iegūst administrācijas biedrus
            $query1 = "SELECT * FROM `cms_accounts` WHERE `group`='1' OR `group`='2' OR `group`='3';";
            $result1 = $conn->query($query1);

            if ($result1->num_rows >= 1) {
                while ($row1 = mysqli_fetch_array($result1)) {
                    $query2 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`='" . $row1["id"] . "' LIMIT 5;";
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

            //sakārto administrācijas biedrus pēc ierakstu skaita
            usort($posts, function($a, $b){
                return $b['ieraksti'] <=> $a['ieraksti'];
            });

            //izvada administrācijas biedrus
            for ($i = 0; $i < count($posts); $i++) {
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
                        Reģ. <?php echo laikuAtpakal($user["created_at"]); ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</body>

</html>
