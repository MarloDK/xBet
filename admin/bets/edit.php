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
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_bets.css">

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
            <a href="">Bets</a>
            <a href="/admin/users/user">Users</a>
        </div>
        
        <div id="nav-bar">
            <a href="/admin/bets/general" style="font-weight: bold;">Create</a>
            <a href="" style="font-weight: bold;">Edit</a>
        </div>

        <div id="page-content">
            <form class="setting-panel-large" method="POST">
                <div class="setting-container">
                    <h3>BetID</h3>
                    <select class="required" name='betID' required>
                        <?php echo getBetIDs($db, $devbuild); ?>
                    </select>
                    <span class="asterisk_input"> </span>

                    <h3>Winstate</h3>
                    <select name="winstate" required>
                        <option value="WAIT">WAIT</option>
                        <option value="valg1">valg1</option>
                        <option value="valg2">valg2</option>
                    </select>
                    <span class="asterisk_input"> </span>
                </div>
                <input type="submit" value="Edit" name='submit'>
            </form>
            <?php
                if (isset($_POST['submit']) && isset($_POST['betID'])) {
                    if ($_POST['betID'] != "Ingen Bets") {
                        $conn = connect($db);
                        
                        /* Senere feature */
                        /* $title = mysqli_real_escape_string($conn, $_POST['title']);
                        $price = mysqli_real_escape_string($conn, $_POST['price']);
                        $userid = mysqli_real_escape_string($conn, $_POST['userID']);
                        $date = mysqli_real_escape_string($conn, $_POST['endDate']);
                        $valg1 = mysqli_real_escape_string($conn, $_POST['valg1']);
                        $valg2 = mysqli_real_escape_string($conn, $_POST['valg2']); */

                        $betID = mysqli_real_escape_string($conn, $_POST['betID']);
                        $winstate = mysqli_real_escape_string($conn, $_POST['winstate']);
                        disconnect($conn);
                        if ($winstate == "valg1" || $winstate == "valg2") {
                            echo "
                                <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:green;'>
                                    ".editBet($db, $betID, $winstate).
                                "</p>";
                        } else {
                            echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>
                                Winstate fejl
                            </p>";
                        }
                    } else {
                        echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>
                                Ingen bets
                            </p>";
                    }
                }
            ?>
        </div>
    </div>
    <?php footer(); ?>
    </body>
</html>