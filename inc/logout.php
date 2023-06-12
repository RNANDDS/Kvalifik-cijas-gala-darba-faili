<?php
session_start();
//importē utils failu
include_once("utils.php");

//izdzēš sesdijas datus
unset($_SESSION["data"]);

//izveidz sesiju un nosūta uz sākumlapu
session_destroy();
header("Location: ../index.php");
?>