<?php
session_start();
include("./inc/db.php");
include("./inc/utils.php");

$lietotajvards = "";
$parole1 = "";
$parole2 = "";
$kludas = "";
$registrejas = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["register"])) {
    $lietotajvards = test_input($_POST["lietotajvards"]);
    $epasts = test_input($_POST["epasts"]);
    $parole1 = test_input($_POST["parole1"]);
    $parole2 = test_input($_POST["parole2"]);
    $kludas = "";
    $registrejas = 0;

    if (empty($lietotajvards) || empty($lietotajvards) || empty($lietotajvards)) {
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
    <div class="container">
        <div class="item">
            <div class="form">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php
                    if ($registrejas == 0) {
                        echo ("<h3 class='success'>Veiksmīgi reģistrējies. <a href='./login.php'><bold>Ielogoties!</bold></a></h3>");
                    }
                    ?>
                    <label for="lietotajvards" class="label">Lietotajvārds:</label>
                    <input type="text" class="input" name="lietotajvards">

                    <label for="epasts" class="label">E-Pasts:</label>
                    <input type="text" class="input" name="epasts">

                    <label for="parole" class="label">Parole:</label>
                    <input type="password" class="input" name="parole1">

                    <label for="parole" class="label">Parole atkārtoti:</label>
                    <input type="password" class="input" name="parole2">

                    <input class="poga" type="submit" name="register" value="Reģistrēties">
                </form>

                <h3>Jau esi reģistrējies? <a href='./login.php'><bold>Ielogojies!</bold></a></h3>

                <?php
                if ($registrejas == 1 || $registrejas == 2) {
                    echo ("<h3 class='error'>Lietotājvārds un/vai E-Pasts ir jau aizņemts/(-a)!</h3>");
                } else if ($registrejas == 3) {
                    echo ("<h3 class='error'>Paroles nesakrīt!</h3>");
                } else if ($registrejas == 4) {
                    echo ("<h3 class='error'>Lūdzu pārliecinies vai visi lauki ir aizpildīti!</h3>");
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>