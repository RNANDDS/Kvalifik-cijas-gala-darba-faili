<?php
session_start();
include_once("utils.php");
include_once("config.php");

if (!isset($_SESSION["data"]["name"])) {
    header("Location: $lapas_saite/login.php");
    die();
}

?>

<div class="item large box">
    <ul>
        <li><a <?php echo isActive("index.php"); ?> href="<?php  echo $lapas_saite; ?>/index.php">Forums</a></li>
        <li><a <?php echo isActive("statistika.php"); ?> href="<?php echo $lapas_saite; ?>/statistika.php">Statistika</a></li>
    </ul>

    <p>
        <?php
        if (isset($_SESSION["data"])) {
        ?>
            <a class="popup" onclick="myFunction()"><?php echo $_SESSION["data"]["name"]; ?>
                <span class="popuptext" id="userPopup">
                    <?php echo $_SESSION["data"]["name"]; ?>
                    <?php echo $_SESSION["data"]["name"]; ?>
                    <?php echo $_SESSION["data"]["name"]; ?>
                    <?php echo $_SESSION["data"]["name"]; ?>
                    <?php echo $_SESSION["data"]["name"]; ?>
                    <?php echo $_SESSION["data"]["name"]; ?>
            </span>
            </a>

            <a href="./inc/logout.php">[IZRAKSTĪTIES]</a>
        <?php
        } else {
        ?>
            <a href="./login.php">[IZRAKSTĪTIES]</a>
        <?php
        }
        ?>
    </p>
</div>

<script>
    function myFunction() {
        var popup = document.getElementById("userPopup");
        popup.classList.toggle("show");
    }
</script>