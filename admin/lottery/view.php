<?php
session_start();
error_reporting(0);
require_once "../../public/scripts/functions.php";
require_once "../../public/scripts/vars.php";
require_once "../../public/required-php/required.php";
auth(admin: true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>
    
    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/table.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_table.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_lottery.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('admin');
    ?>

    <div id="page-wrapper">
        <div id="top-bar">
            <a href="/admin/admin">Home</a>
            <a href="">Lottery</a>
            <a href="/admin/bets/general">Bets</a>
            <a href="/admin/users/user">Users</a>
        </div>

        <div id="nav-bar">
            <a href="/admin/lottery/general">Create</a>
            <a href="">View</a>
        </div>

        <div id="page-content">
            <table id="lottery-table">
                <tr class="header-text">
                    <th>ID</th>
                    <th>Penge</th>
                    <th>Winchance</th>
                    <th>Max Tickets</th>
                    <th>Ticket Price</th>
                    <th>End Date</th>
                    <th>Winstate</th>
                    <th>Winner</th>
                </tr>
                <?php loadLotteries($db, $devbuild, state: 'view') ?>
            </table>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>