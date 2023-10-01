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
            <a href="">Create</a>
            <a href="/admin/lottery/view">View</a>
        </div>

        <div id="page-content">
            <form class="setting-panel-large" method="POST">
                <div class="setting-container">
                    <h3>Base Prize Pool</h3>
                    <input type="number" name="basePrizePool">

                    <h3>User Win Chance</h3>
                    <input type="number" step="0.01" name="userWinChance">

                    <h3>Ticket Price</h3>
                    <input type="number" name="ticketPrice">

                    <h3>Max Tickets</h3>
                    <input type="number" name="maxTickets">

                    <h3>End time</h3>
                    <input class="required" type="datetime-local" min=<?php echo(date("Y-m-d\TH:i")); ?> name='endDate' required>
                    <span class="asterisk_input"> </span>
                </div>
                
                <input type="submit" value="Upload" name='submit'>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    if (isset($_POST['endDate'])) {
                        $conn = connect($db);
                        $basePrizePool = mysqli_real_escape_string($conn, $_POST['basePrizePool']);
                        $userWinChance = mysqli_real_escape_string($conn, $_POST['userWinChance']);
                        $ticketPrice = mysqli_real_escape_string($conn, $_POST['ticketPrice']);
                        $maxTickets = mysqli_real_escape_string($conn, $_POST['maxTickets']);
                        $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
                        disconnect($conn);

                        if ($endDate > date('Y-m-d\TH:i')) {
                            echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:green;'>
                                ".addLottery($db, (strval($basePrizePool) != ""?$basePrizePool:10000), (strval($userWinChance) != ""?$userWinChance:0.25), (strval($maxTickets) != ""?$maxTickets:1), (strval($ticketPrice) != ""?$ticketPrice:1000), $endDate, $devbuild).
                            "</p>";
                        } else {
                            echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>
                                Du kan ikke tilføje lotteriet.
                            </p>";
                        }
                    }
                }
            ?>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>