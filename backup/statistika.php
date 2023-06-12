<?php
include("./inc/db.php");
include("./inc/utils.php");

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
            <div class="cat-container">
                <h3>Reģistrētie lietotāji</h3>

                <?php
                $query1 = "SELECT * FROM `cms_accounts`;";
                $result1 = $conn->query($query1);

                if ($result1->num_rows > 0) {
                    while ($row1 = mysqli_fetch_array($result1)) {
                        $query2 = "SELECT COUNT(*) FROM `cms_posts` WHERE `author`=" . $row1["id"];
                        $result2 = $conn->query($query2);
                        $user = mysqli_fetch_array($result2);

                ?>
                        <div class="cat-item">
                            <div class="cat-row">
                                <?php echo $row1["name"]; ?>
                                <p><?php echo getUserGroup($row1["id"], true); ?></p>
                                <p>Ieraksti: <?php echo ($user != null) ? $user[0] : 0; ?></p>
                            </div>
                            <div class="cat-row">
                                Reģistrējās: <?php echo laikuAtpakal($row1["created_at"]); ?>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="item">
        <div class="cat-container">
                <h3>Ziņojumu TOPS</h3>

                <?php
                $posts = array();

                $query1 = "SELECT * FROM `cms_accounts` LIMIT 5;";
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

                usort($posts, function($a, $b) {
                    return $b['ieraksti'] <=> $a['ieraksti'];
                });

                for ($i = 0; $i < count($posts); $i++) {
                    $lietotajaId = $posts[$i]["id"];
                    $ierakstuSkaits = $posts[$i]["ieraksti"];

                    $query1 = "SELECT * FROM `cms_accounts` WHERE `id`='$lietotajaId';";
                    $result = $conn->query($query1);
                    $user = mysqli_fetch_array($result);

                ?>
                    <div class="cat-item">
                        <div class="cat-row">
                            <?php echo $user["name"]; ?>
                            <p><?php echo getUserGroup($user["id"], true); ?></p>
                            <p>Ieraksti: <?php echo $ierakstuSkaits; ?></p>
                        </div>
                        <div class="cat-row">
                            Reģistrējās: <?php echo laikuAtpakal($user["created_at"]); ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="cat-container">
                <h3>Administrācijas biedri</h3>

                <?php
                $posts = array();

                $query1 = "SELECT * FROM `cms_accounts` WHERE `group`='1' LIMIT 5;";
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

                usort($posts, function($a, $b){
                    return $b['ieraksti'] <=> $a['ieraksti'];
                });

                for ($i = 0; $i < count($posts); $i++) {
                    $lietotajaId = $posts[$i]["id"];
                    $ierakstuSkaits = $posts[$i]["ieraksti"];

                    $query1 = "SELECT * FROM `cms_accounts` WHERE `id`='$lietotajaId';";
                    $result = $conn->query($query1);
                    $user = mysqli_fetch_array($result);

                ?>
                    <div class="cat-item">
                        <div class="cat-row">
                            <?php echo $user["name"]; ?>
                            <p><?php echo getUserGroup($user["id"], true); ?></p>
                            <p>Ieraksti: <?php echo $ierakstuSkaits; ?></p>
                        </div>
                        <div class="cat-row">
                            Reģistrējās: <?php echo laikuAtpakal($user["created_at"]); ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <?php include "./inc/footer.php"; ?>
    </div>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</body>

</html>