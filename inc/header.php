<?php
session_start();
include_once("utils.php");

//vai lietotajs ir ielogojies
if (!isset($_SESSION["data"]["name"])) {
    header("Location: " . iegutUrl() . "/login.php");
    die();
}

?>

<div class="item large box">
    <ul>
        <li class="li-left"><a <?php echo isActive("index.php"); ?> href="<?php echo iegutUrl() ?>/index.php">Sakums</a></li>
        <li class="li-left"><a <?php echo isActive("statistika.php"); ?> href="<?php echo iegutUrl() ?>/statistika.php">Statistika</a></li>
        <li class="li-left"><a <?php echo isActive("view_topic.php?id=24"); ?> href="<?php echo iegutUrl() ?>/view_topic.php?id=24">Noteikumi</a></li>
        <li class="li-left"><a <?php echo isActive("par_mums.php"); ?> href="<?php echo iegutUrl() ?>/par_mums.php">Par Mums</a></li>
        <li class="li-left"><a <?php echo isActive("view_category.php?id=8"); ?> href="<?php echo iegutUrl() ?>/view_category.php?id=8">Palidziba</a></li>
    </ul>

    <ul>
        <li class="li-right">

            <?php
            //saturs atkarībā no tā vai lietotājs ir ielogojies
            if (isset($_SESSION["data"])) {
                ?>
                <div class="dropdown">
                    <a>
                        <img width="10" height="10" src="assets/user.png" />
                        <span><?php echo $_SESSION["data"]["name"]; ?></span>
                    </a>
                    <div class="dropdown-content">
                        <p><a href="./lietotajs.php?id=<?php echo $_SESSION["data"]["id"]; ?>">Profils</a></p>
                        <p><a href="./iestatijumi.php?id=<?php echo $_SESSION["data"]["id"]; ?>&err=0">Iestatījumi</a></p>
                        <p><a href="<?php echo iegutUrl() ?>/inc/logout.php">Izrakstities</a></p>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <a href="<?php echo iegutUrl() ?>/login.php">Ielogoties</a>
                <?php
            }
            ?>
        </li>
    </ul>
</div>

<script>
    function myFunction() {
        var popup = document.getElementById("userPopup");
        popup.classList.toggle("show");
    }
</script>
