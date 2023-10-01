<?php
session_start();
error_reporting(0);
require_once "../../public/scripts/functions.php";
require_once "../../public/scripts/vars.php";
require_once "../../public/required-php/required.php";
auth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>
    <script src="/public/scripts/leaderboards.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/wins.css">
    <link rel="stylesheet" href="/public/styling/table.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('wins');
    ?>
    <div id="page-wrapper">
        <table id="stat">
            <tr class="header-text">
                <th>Navn</th>
                <th>Wins</th>
                <th>Level</th>
            </tr>
            <?php pullLeaderboard($db, $devbuild, 'wins') ?>
        </table>
    </div>
    <?php footer(); ?>
</body>
</html>