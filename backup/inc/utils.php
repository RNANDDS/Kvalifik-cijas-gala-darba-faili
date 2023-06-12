<?php

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function isActive(string $scriptName)
{
    return ($_SERVER['SCRIPT_NAME'] == "/$scriptName") ? "class='active'" : "";
}

function laikuAtpakal($time_ago)
{
    $now = new DateTime();
    $input = new DateTime($time_ago);

    $difference = $now->diff($input);

    if ($difference->d == 0) {
        return "Šodien, " . $input->format("H:i");
    } elseif ($difference->d == 1) {
        return "Vakar, " . $input->format("H:i");
    }

    return $input->format("d/m/Y");
}

function getUserGroup(int $userId, bool $color = false)
{
    include("db.php");

    $query = "SELECT * FROM `cms_accounts` WHERE `id`='$userId';";
    $result = $conn->query($query);
    $fetch = mysqli_fetch_array($result);

    switch ($fetch["group"]) {
        case 0:
            return ($color) ? "<font color='black'><b>Lietotājs</b></font>" : "Lietotājs";
        case 1:
            return ($color) ? "<font color='red'><b>Administrators</b></font>" : "Administrators";
    }
}

function izveidotDefaultBildi(int $userId, string $lietotajvards)
{
    $im = imagecreatetruecolor(150, 150);
    $X = 47.5;
    $Y = 95;

    // Create some colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $grey = imagecolorallocate($im, 128, 128, 128);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledrectangle($im, 0, 0, 150, 150, $grey);

    // The text to draw
    $text = substr($lietotajvards, 0, 1);
    // Replace path by your own font path
    $font = 'arial.ttf';

    // Add the text
    imagettftext($im, 62, 0, $X, $Y, $black, $font, $text);

    // Using imagepng() results in clearer text compared with imagejpeg()
    imagepng($im, "lietotaju_bildes/$userId.png");
    imagedestroy($im);
}

