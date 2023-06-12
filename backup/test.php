<?php

session_start();

// Create the image
$im = imagecreatetruecolor(150, 150);

$X = 47.5;
$Y = 95;

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 150, 150, $grey);

// The text to draw
$text = substr($_SESSION["data"]["name"], 0, 1);
// Replace path by your own font path
$font = 'arial.ttf';

// Add the text
imagettftext($im, 62, 0, $X, $Y, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im, "demo.png");
imagedestroy($im);
?>

<img src="demo.png">