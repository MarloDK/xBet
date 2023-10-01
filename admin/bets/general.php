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
            <a href="" style="font-weight: bold;">Create</a>
            <a href="/admin/bets/edit" style="font-weight: bold;">Edit</a>
        </div>

        <div id="page-content">
            <form class="setting-panel-large" method="POST">
                <div class="setting-container">
                    <h3>Title</h3>
                    <input class="required" type="text" name='title' required>
                    <span class="asterisk_input"> </span>

                    <h3>Entry Price</h3>
                    <input type="number" name='price'>

                    <h3>Hidden userID</h3>
                    <input class="required" type="number" name='userID' required>
                    <span class="asterisk_input"> </span>

                    <h3>End time</h3>
                    <input class="required" type="datetime-local" min=<?php echo(date("Y-m-d\TH:i")); ?> name='endDate' required>
                    <span class="asterisk_input"> </span>

                    <h3>Bet Options</h3>
                    <input class="bet-options" type="text" name='valg1'>
                    <input class="bet-options" type="text" name='valg2'>
                </div>
                <input type="submit" value="Upload" name='submit'>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    if (isset($_POST['title']) && isset($_POST['userID']) && isset($_POST['endDate'])) {
                        $conn = connect($db);
                        $title = mysqli_real_escape_string($conn, $_POST['title']);
                        $price = mysqli_real_escape_string($conn, $_POST['price']);
                        $userid = mysqli_real_escape_string($conn, $_POST['userID']);
                        $date = mysqli_real_escape_string($conn, $_POST['endDate']);
                        $valg1 = mysqli_real_escape_string($conn, $_POST['valg1']);
                        $valg2 = mysqli_real_escape_string($conn, $_POST['valg2']);
                        disconnect($conn);

                        if ($date > date('Y-m-d\TH:i')) {
                            echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:green;'>
                                ".addBet($db, $title, $userid, $date, $devbuild, (strval($price) != "" ?$price:100), ($valg1 != "" ?$valg1:"Ja"), ($valg2 != ""?$valg2:"Nej")).
                            "</p>";
                        } else {
                            echo "
                            <p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>
                                Du kan ikke tilføje et bet der er udløbet.
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