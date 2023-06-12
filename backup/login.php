<?php
session_start();
include("./inc/db.php");
include("./inc/utils.php");

$lietotajvards = "";
$parole = "";
$kludas = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["login"])) {
    $lietotajvards = test_input($_POST["lietotajvards"]);
    $parole = test_input($_POST["parole"]);
    $kludas = "";

    $irEpasts = filter_var($lietotajvards, FILTER_VALIDATE_EMAIL);
    $result = $conn->query("SELECT * FROM `cms_accounts` WHERE " . ($irEpasts ? "`email`='$lietotajvards'" : "`name`='$lietotajvards'") . " AND `password`='" . md5($parole) . "';");

    if ($result->num_rows <= 0) $kludas = $irEpasts ? 1 : 2;

    if (empty($lietotajvards) || empty($parole)) {
        $kludas = 3;
    }

    if (empty($kludas)) {
        $data = mysqli_fetch_array($result);

        $lietotajaInfo = [
            "id" => $data["id"],
            "name" => ($irEpasts) ? null : $lietotajvards,
            "email" => ($irEpasts) ? $lietotajvards : null
        ];

        $_SESSION["data"] = $lietotajaInfo;
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="./assets/style.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="item">
            <div class="form">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="lietotajvards" class="label">Lietotajvārds vai E-Pasts:</label>
                    <input type="text" class="input" name="lietotajvards">

                    <label for="parole" class="label">Parole:</label>
                    <input type="password" class="input" name="parole">

                    <input class="poga" type="submit" name="login" value="Ielogoties">
                </form>

                <h3>Neesi reģistrējies? <a href='./register.php'>
                        <bold>Reģistrējies!!!</bold>
                    </a></h3>

                <?php
                if (!empty($kludas)) {
                    switch ($kludas) {
                        case 1:
                            echo ("<h3 class='error'>E-Pasts un/vai parole ir nepareizs/(-a)!</h3>");
                            break;
                        case 2:
                            echo ("<h3 class='error'>Lietotājvārds un/vai parole ir nepareizs/(-a)!</h3>");
                            break;
                        case 3:
                            echo ("<h3 class='error'>Lūdzu pārliecinies vai visi lauki ir aizpildīti!</h3>");
                            break;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>