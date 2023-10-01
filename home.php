<?php
session_start();
error_reporting(0);
require_once __DIR__."/public/scripts/functions.php";
require_once __DIR__."/public/scripts/vars.php";
require_once __DIR__."/public/required-php/required.php";
auth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/home.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('home');
    ?>
    
    <div id="page-wrapper">
        <div class="page" id="news">
            <p class="page-title">NYHEDER</p>
            
            <?php if (!$devbuild) {pullNews($db);} ?>

        </div>

        <div class="page" id="guide">
            <p class="page-title">VEJLEDNINGER</p>

            <?php if (!$devbuild) {pullGuides($db);} ?>

        </div>

        <div class="page" id="faq">
            <p class="page-title">FAQ</p>
            <div class="page-div">
                <h1 class="page-div-title">Ofte stillede spørgsmål:</h1>
                
                <?php if (!$devbuild) {pullFaq($db);} ?>

            </div>
        </div>
    </div>
    <?php footer(); ?>
    </body>
</html>