<?php
session_start();
error_reporting(0);
require_once "../public/scripts/functions.php";
require_once "../public/scripts/vars.php";
require_once "../public/required-php/required.php";
auth(admin: true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/shop.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('shop');
    ?>

    <div id="page-wrapper">     
        <div id="item-container">
            <div class="item">
                <p class="title">Premium</p>
                <p class="description">asidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodasasidkasidiasiodjasiojodass</p>
                <p class="price">X$600</p>
                <form method="post"><input type="submit" name="BUY" value="BUY"></form>
            </div>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>