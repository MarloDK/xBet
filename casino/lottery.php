<?php
session_start();
error_reporting(0);
require_once "../public/scripts/functions.php";
require_once "../public/scripts/vars.php";
require_once "../public/required-php/required.php";
auth();
$lottery = loadLotteries($db, $devbuild, "load");
checkLotteryWin($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <!-- Styles -->
    <link rel="stylesheet" href="../public/styling/style.css">
    <link rel="stylesheet" href="../public/styling/lottery.css">
    <script src="../public/scripts/timer.js"></script>
</head>
<body onload="countdown('<?php echo $lottery['slutTid'];?>');">
    <?php
    navBar($devbuild, $db);
    sideBar('lottery');
    ?>

    <div id="page-wrapper">
        <div id="lottery-wrapper">
            <h4>Lottery</h4>
            <i class="fa-solid fa-ticket" id="logo"></i>
            <i class="fa-solid fa-ticket" id="logo2"></i>
            <p id="pulje"><?php echo $lottery['lotteryPenge'] ?><img id="coin" src="/public/images/X-Bet Coin.png"></p>
            <p id="time-left" style='margin-top: 1vh;'></p>
        </div>

        <div id="buy-panel">
            <div id="stats">
                <?php lotteryWinner($db, $lottery) ?>
            </div>
            <p>Tickets - <?php echo $lottery['ticketPrice'].'X$ pr. ticket';?></p>
            <form method=POST>
                <ul>
                    <li><input type="number" name="tickets" min="1" max="<?php echo $lottery['maxTickets'];?>" placeholder="max. <?php echo $lottery['maxTickets'];?> tickets" required></li>
                    <p id="error">
                    <?php
                        if (isset($_POST['joinLottery']) && $_POST['tickets'] <= $lottery['maxTickets'] && $_POST['tickets'] >= 0) {
                            $conn = connect($db);
                            $tickets = mysqli_real_escape_string($conn, $_POST['tickets']);
                            disconnect($conn);
                            if ($lottery['ID'] != 0) {
                                echo joinLottery($db, $_SESSION['userid'], $tickets, $lottery['ticketPrice'], $lottery['ID']);
                            } else {
                                echo 'Der kører ikke noget lotteri';
                            };
                        };
                    ?>
                    </p>
                    <li><input type="submit" value="JOIN LOTTERY" name="joinLottery" required></li>
                </ul>
            </form>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>