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
    <link rel="stylesheet" href="/public/styling/admin_styling/admin.css">
</head>
<body>
    <?php
        navBar($devbuild, $db);
        sideBar('admin');
    ?>

    <div id="page-wrapper">
        <div id="top-bar">
            <a href="">Home</a>
            <a href="/admin/lottery/general">Lottery</a>
            <a href="/admin/bets/general">Bets</a>
            <a href="/admin/users/user">Users</a>
        </div>

        <div id="nav-bar">
            <a href="">Penge i omløb</a>
        </div>

        <div id="page-content">
            <div class="inflation">
                <p>Penge i omløb nu:</p>
                <p class="money" id="playersmoney"><?php echo(dbQuery($db, 'select SUM(penge) as penge from stats;')->fetch_assoc()['penge']); ?></p>
            </div>
            <div class="inflation">
                <p>Startpenge i omløb:</p>
                <p class="money" id="supposed"><?php echo(dbQuery($db, 'select COUNT(stats.penge) * 10000 as supposed from stats join users on stats.UserID = users.ID where users.admin = 1 and users.navn != "NONE";')->fetch_assoc()['supposed'] + dbQuery($db, 'select COUNT(stats.penge) * 12500 as supposed from stats join users on stats.UserID = users.ID where users.admin = 2;')->fetch_assoc()['supposed']); ?></p>
            </div>
            <div class="inflation">
                <p>Inflation:</p>
                <p class="money" id="percent"></p>
            </div>
            <script>
                var percent = document.getElementById('percent');
                var playersmoney = document.getElementById('playersmoney').textContent;
                var supposed = document.getElementById('supposed').textContent;

                if (playersmoney != 0 && supposed != 0) {
                    percent.textContent = Math.round((playersmoney / supposed - 1) * 100).toString() + "%";
                } else {
                    percent.textContent = '0%';
                }
            </script>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>