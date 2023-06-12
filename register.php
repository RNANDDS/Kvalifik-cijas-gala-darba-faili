<?php
//uzsāk sesiju
session_start();
//iegūst datubāzi un utils
include("./inc/utils.php");
include("./inc/db.php");

//izveido mainīgos
$lietotajvards = "";
$parole1 = "";
$parole2 = "";
$kludas = "";
$registrejas = 6;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["register"])) {
    //iegūst pieprasījuma datus
    $lietotajvards = test_input($_POST["lietotajvards"]);
    $epasts = test_input($_POST["epasts"]);
    $parole1 = test_input($_POST["parole1"]);
    $parole2 = test_input($_POST["parole2"]);
    $kludas = "";
    $registrejas = 0;

    //apstrādā kļūdas
    if (empty($lietotajvards) || empty($epasts) || empty($parole1) || empty($parole2)) {
        $registrejas = 4;
    }

    if ($conn->query("SELECT * FROM `cms_accounts` WHERE `name`='{$lietotajvards}';")->num_rows > 0) {
        $registrejas = 1;
    }

    if ($conn->query("SELECT * FROM `cms_accounts` WHERE `email`='{$epasts}';")->num_rows > 0) {
        $registrejas = 2;
    }

    if ($parole1 != $parole2) {
        $registrejas = 3;
    }

    if (strlen($parole1) < 8) {
        $registrejas = 5;
    }

    //ja nav kļūdu, izveido profilu
    if ($registrejas == 0) {
        $paroleHash = md5($parole1);

        $conn->query("INSERT INTO `cms_accounts`(`name`, `email`, `password`) VALUES ('$lietotajvards', '$epasts', '$paroleHash');");
        izveidotDefaultBildi($conn->insert_id, $lietotajvards);
    }
}

?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reģistrācija</title>

    <link href="./assets/style.css" rel="stylesheet" />
</head>

<body>
    <div class="form-base-container">
        <div class="item">
            <div class="form-container">
                <div class="info">
                    <h3>Reģistrēties</h3>
                </div>
                <hr/>

                <?php
                //izvada kļūdas
                if ($registrejas == 1 || $registrejas == 2) {
                    echo ("<h3 class='error'>Lietotājvārds un/vai E-Pasts ir jau aizņemts/(-a)!</h3>");
                } else if ($registrejas == 3) {
                    echo ("<h3 class='error'>Paroles nesakrīt!</h3>");
                } else if ($registrejas == 4) {
                    echo ("<h3 class='error'>Lūdzu pārliecinies vai visi lauki ir aizpildīti!</h3>");
                } else if ($registrejas == 5) {
                    echo ("<h3 class='error'>Lūdzu pārliecinies vai parole ir vismaz 8 burtu vai ciparu gara!</h3>");
                }
                ?>

                <form class="form-login" method="post" action="./register.php">
                    <?php
                    if ($registrejas == 0) {
                        //echo ("<h5 class='success'>Veiksmīgi reģistrējies. <a href='./login.php'><bold>Ielogoties!</bold></a></h5>");
                        header("Location: ./login.php?register=1");
                    }
                    ?>
                    <label for="lietotajvards" class="label">Lietotajvārds:</label>
                    <input type="text" name="lietotajvards">

                    <label for="epasts" class="label">E-Pasts:</label>
                    <input type="text" name="epasts">

                    <label for="parole" class="label">Parole:</label>
                    <input type="password" name="parole1">

                    <label for="parole" class="label">Parole atkārtoti:</label>
                    <input type="password" name="parole2">

                    <input type="submit" name="register" value="Reģistrēties">
                </form>

                <h5>Jau esi reģistrējies?
                    <a href='<?php echo iegutUrl() ?>/login.php'><bold>Ielogojies!</bold></a>
                </h5>
            </div>
        </div>
    </div>
</body>
</html>