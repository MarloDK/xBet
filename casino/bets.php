<?php
session_start();
error_reporting(0);
require_once "../public/scripts/functions.php";
require_once "../public/scripts/vars.php";
require_once "../public/required-php/required.php";
require_once "../public/scripts/bets-management.php";
auth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/bets.css">
</head>
<body>
<?php
    navBar($devbuild, $db);
    sideBar('bets');
    ?>

    <div id="page-wrapper">
        <ul id="bets-container">
            <?php $bets = pullBets($db, $_SESSION['userid']); ?>
        </ul>

        <?php
            for ($i = 0; $i < count($bets); $i++) {
                if (isset($_POST[$bets[$i]->id]) && $_POST[$bets[$i]->id] == 'PLACE BET') {
                    
                    $conn = connect($db);
                    $valg = mysqli_real_escape_string($conn, $_POST['bet-valg']);
                    $penge = mysqli_real_escape_string($conn, $_POST['bet-amount']);
                    $betID = mysqli_real_escape_string($conn, $bets[$i]->id);
                    disconnect($conn);
                    
                    if ($_POST['bet-amount'] >= $bets[$i]->minimumBet && ($valg == 'valg1' || $valg == 'valg2')) {
                        joinBet($db, $_SESSION['userid'], $betID, $valg, $penge);
                    }
                }
            }
        ?>

    </div>
    <?php footer(); ?>
</body>
</html>