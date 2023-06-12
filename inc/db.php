<?php
//datu bāzes dati
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'ritvars';
//izveido savienojumu ar datubāzi
$conn = new mysqli($host, $user, $pass, $dbName);
//pārbauda vai savienojums izdevies
if($conn->connect_error){
    echo "Notikus kļūda savienojumā!";
}
?>