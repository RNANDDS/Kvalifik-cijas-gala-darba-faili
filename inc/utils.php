<?php
//iegūst esošā faila vietu
function iegutUrl(): string
{
    return ".";
}

//pārbauda ievadi pret sql injection
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

//vai header poga ir aktīva
function isActive(string $scriptName)
{
    return (str_contains(basename($_SERVER['REQUEST_URI']), $scriptName)) ? "class='active'" : "";
}

//laika perioda aprēķins
function laikuAtpakal($time_ago)
{
    $input = new DateTime($time_ago);
	$today = new DateTime("today");

	$match_date = DateTime::createFromFormat( "Y-m-d H:i:s", $time_ago );
	$match_date->setTime( 0, 0, 0 );

	$diff = $today->diff( $match_date );
	$diffDays = (integer)$diff->format( "%R%a" );

    $kad = match ($diffDays) {
        0 => "Šodien, " . $input->format("H:i"),
        -1 => "Vakar, " . $input->format("H:i"),
        default => $match_date->format("d/m/Y"),
    };
	return $kad;
}

//iegūst lietotāja grupu
function getUserGroup(int $userId, bool $color = false)
{
    //importē datubāzi
    include("db.php");

    //iegūst lietotāja datus pēc norādīta id
    $query = "SELECT * FROM `cms_accounts` WHERE `id`='$userId';";
    $result = $conn->query($query);
    $fetch = mysqli_fetch_array($result);

    //izvada krupas krāsu
    switch ($fetch["group"]) {
        case 0:
            return ($color) ? "<font color='black'><b>Lietotājs</b></font>" : "Lietotājs";
        case 1:
            return ($color) ? "<font color='darkred'><b>Administrators</b></font>" : "Administrators";
        case 2:
            return ($color) ? "<font color='darkred'><b>Īpašnieks</b></font>" : "Īpašnieks";
        case 3:
            return ($color) ? "<font color='darkgreen'><b>Moderators</b></font>" : "Moderators";
    }
}

//izvada lietotāja lietotājvārdu ar krāsām
function getUserName(int $userId) {
    include("db.php");

    //iegūst lietotaja datus
    $query = "SELECT * FROM `cms_accounts` WHERE `id`='$userId';";
    $result = $conn->query($query);
    $fetch = mysqli_fetch_array($result);
    
    $group = $fetch["group"];

    //maina krāsu ja ir adminstrācijas biedrs
    if ($group == 1 || $group == 2 || $group == 3) {
        switch ($group) {
            case 1:
                return "<font color='red'><b>" . $fetch['name'] . "</b></font>";
            case 2:
                return "<font color='red'><b>" . $fetch['name'] . "</b></font>";
            case 3:
                return "<font color='green'><b>" . $fetch['name'] . "</b></font>";
        }
    }
    
    return "<font color='black'><b>" . $fetch['name'] . "</b></font>";
}

//izveido noklusējuma profila bildi
function izveidotDefaultBildi(int $userId, string $lietotajvards) {
    //iveido bildes dimensijas un burta atrašanās bietas koordinātas
    $im = imagecreatetruecolor(150, 150);
    $X = 47.5;
    $Y = 95;

    // izveido krāsas
    $grey = imagecolorallocate($im, 128, 128, 128);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledrectangle($im, 0, 0, 150, 150, $grey);

    $text = substr(strtoupper($lietotajvards), 0, 1);
    //fonta antrašanās vieta
    $font = 'inc/arial.ttf';

    //ievieto tekstu bildē
    imagettftext($im, 62, 0, $X, $Y, $black, $font, $text);

    // saglabā bildi
    imagepng($im, "lietotaju_bildes/$userId.png");
    imagedestroy($im);
}
