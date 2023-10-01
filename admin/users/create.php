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
    <script src="/public/scripts/login.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin.css">
    <link rel="stylesheet" href="/public/styling/admin_styling/admin_users.css">
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
            <a href="">Create</a>
            <a href="/admin/users/logs">Logs</a>
        </div>

        <div id="page-content">
            <form method="POST">
                <div class="setting-container">
                    <h3>Username</h3>
                    <input class="required" type="text" id="uname" placeholder="Username" name="uname" required>
                    <span class="asterisk_input"> </span>
                
                    <h3>Password</h3>
                    <input class="required" type="password" id="pass" placeholder="Password" name="pass" required>
                    <span class="asterisk_input"> </span>
                    <i id="showPassIcon" class="fa-regular fa-eye passicon" style="cursor: pointer;" onclick="showpass()"></i>
                </div>

                <input type="submit" value="Register" name="register">

            </form>
            <div id="failed-register">
                <?php
                    if (!$devbuild) {
                        if (isset($_POST['register']) && $_POST['pass'] != "" && $_POST['uname'] != "") {
                            $conn = connect($db);
                            $uname = mysqli_real_escape_string($conn, $_POST['uname']);
                            $pass = mysqli_real_escape_string($conn, $_POST['pass']);
                            disconnect($conn);
                            
                            $result = dbQuery($db, "select count(*) from users where binary Navn = '".$uname."';");
                            $row = $result->fetch_assoc();
                            if ($row['count(*)'] == 0) {
                                $secure_pass = password_hash($pass, PASSWORD_BCRYPT);
                                dbLog($db, 'Indsatte bruger: '.$uname);
                                dbQuery($db, "insert into users (Navn, Pass) values ('".$uname."', '".$secure_pass."');");
                                $id = checkLogin($db, $uname, $pass)[1];
                                dbQuery($db, "insert into stats(UserID) values (".$id.");");
                                echo("<p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:green;'>Account registreret</p>");
                            } else {
                                dbLog($db, 'Prøvede at indsætte bruger: '.$uname);
                                echo("<p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>Brugernavnet er allerede registreret i databasen</p>"); 
                            }
                        } 
                    } else {
                        echo("<p style='font-size: 1.2vw; font-family: TwCenMT, sans-serif; color:red;'>*DEVBUILD ENABLED*</p>");
                    }
                ?>
            </div>
        </div>
    </div>
    <?php footer(); ?>
</body>
</html>