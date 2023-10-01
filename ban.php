<?php
session_start();
error_reporting(0);
require_once __DIR__."/public/scripts/functions.php";
require_once __DIR__."/public/scripts/vars.php";
require_once __DIR__."/public/required-php/required.php";
auth();
if ($_SESSION['userandpasscheck'][2] != TRUE) {
    header("Location: /home");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        top();
        
        $banned = dbQuery($db, 'select bannedGrund, endTid from banned where UserID = '.$_SESSION['userid'].' order by endTid desc limit 1;')->fetch_assoc();
    ?>
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/ban.css">
    <!-- <link rel="stylesheet" href="./public/styling/welcome_text_style.css"> -->
    <script src="/public/scripts/ban.js"></script>
</head>
<body onload="timeTillUnban('<?php echo $banned['endTid']; ?>')">
    <div id="dark-bg">
                <div>
                    <a><img src="/public/images/Logos/X-Bet - Cards white (transparent).png" alt="logo" style="height:5vw;"></a>
                    <p style="font-size:1.5vw;">You were banned because of:</p>
                    <p id="banned-grund"><?php echo $banned['bannedGrund']; ?></p>
                    <p id="time-left" style="font-size:5vw;"></p>
                    <!-- LOG OUT -->
                    <a href="?logout=1" id="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
                </div>
        </div>
</body>
</html>
    

