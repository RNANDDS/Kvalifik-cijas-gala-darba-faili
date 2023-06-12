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
        <h3 class="button-text">PAR MUMS</h3>
    </div>

    <div class="item large">
        <div class="about-container">
            <div class="about-item about-left">
                <h3>Projekta Izstrade</h3>
                <p>
                    Pie vietnes tika strādāts kopš 2023.gada 16 janvāra, kā mācību vide programmēšanā, un mans skolas kvalifikācijas darbs! Mājaslapai ir vēl testa stadijā, kas nozīme
					, ka es pie tās vēl aktīvi strādāju, tapēc nebaidies ierakstīt kādu ieteikumu diskusijas sadaļā, ko varbūt tu, 
					lietotāj vēletos redzēt šajā tīmekļa vietnē!
                </p>
            </div>
            <div class="about-item about-right">
                <h3>Apspriedies ar citiem!</h3>
                <p>
                    Jūs varat veikt ierakstus un tēmas par jebko, noteikumu robežu ietvaros!
                    Ja neizdevās ieraskts, tu to droši vari labot vai izdzēst!
                </p>
            </div>
            <div class="about-item about-left">
                <h3>Labu vēli citiem, citi labu vēlēs tev!</h3>
                <p>
                    Atceries, kādi personiski uzbrukumi vai negācijas izpaušanas uz citiem lietotājiem, var
					rezultēties soda saņemšanā, tapēc lūdzu esam visi saprotoši un veidojam ierakstus un tēmas draudzīgā gaisotnē.
					Jauku sarakstīšanos jums novēl - Tiešsaistes Forums!
                </p>
            </div>
        </div>
    </div>

    <?php include iegutUrl() . "/inc/footer.php"; ?>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</body>

</html>
