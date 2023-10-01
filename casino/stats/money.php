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
    <link rel="stylesheet" href="/public/styling/money.css">
    <link rel="stylesheet" href="/public/styling/table.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('money');
    ?>
    <div id="page-wrapper">
        <table id="stat">
            <tr class="header-text">
                <th>Navn</th>
                <th>Penge</th>
                <th>Level</th>
            </tr>
            
            <?php pullLeaderboard($db, $devbuild, 'money') ?>
        </table>
    </div>
    <?php footer(); ?>
</body>
</html>