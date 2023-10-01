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
    <link rel="stylesheet" href="/public/styling/admin_styling/admin.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_users.css">
    <link rel="stylesheet" href="/public/styling/table.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_table.css">
</head>
<body>
    <?php
    navBar($devbuild, $db);
    sideBar('admin');
    ?>

    <div id="page-wrapper">
        <div id="top-bar">
            <a href="/admin/admin">Home</a>
            <a href="/admin/lottery/general">Lottery</a>
            <a href="/admin/bets/general">Bets</a>
            <a href="">Users</a>
        </div>

        <div id="nav-bar">
            <a href="/admin/users/user">View</a>
            <a href="/admin/users/create">Create</a>
            <a href="">Logs</a>
        </div>

        <div id="page-content">
            <table id="log-table">
                <tr class="header-text">
                    <th>UserID</th>
                    <th>Navn</th>
                    <th>Log</th>
                    <th>Date</th>
                </tr>
                <?php loadLogs($db, $devbuild) ?>
            </table>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>