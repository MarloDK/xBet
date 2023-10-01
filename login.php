<?php
session_start();
error_reporting(0);
if (isset($_SESSION['userandpasscheck']) && isset($_SESSION['uname']) && isset($_SESSION['userid']) && isset($_SESSION['admin'])) {
    if ($_SESSION['userandpasscheck'][0]) {
        header("Location: /home");
        exit();
    }
}
require_once __DIR__."/public/scripts/functions.php";
require_once __DIR__."/public/scripts/vars.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="./public/images/Logos/BW Cards.ico"/>
    <title>X BET</title>

    <!-- Scripts -->
    <script src="/public/scripts/login.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="./public/styling/login_form_style.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Third-Party Assets -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <header>
        <nav>
            <a href="/"><img src="public/images/Logos/X-Bet - Cards white (transparent).png" alt="logo"></a>
        </nav>
    </header>

    <div id="login-form">
        <img src="public/images/cards.jpg">

        <div class="form-container">
            <h1>LOG<span style="color: #E8CF31;">IN</h1>

            <form method="POST">
                <input type="text" id="uname" placeholder="Username" name="uname">
                <i class="fa-regular fa-eye passicon" style="visibility: hidden;"></i>

                <input type="password" id="pass" placeholder="Password" name="pass">
                <i id="showPassIcon" class="fa-regular fa-eye passicon" onclick="showpass()"></i>
                
                <input type="submit" value="LOGIN" name="login">
            </form>
            <p class="incorrectpw">
                <?php
                    if (isset($_POST['login'])) {
                        if ($devbuild) {
                            $_SESSION['userandpasscheck'] = [TRUE, 0, FALSE];
                            $_SESSION['uname'] = 'DEV';
                            $_SESSION['userid'] = 0;
                            $_SESSION['admin'] = 2;
                            $_SESSION['pfp'] = NULL;
                            header("Location: /home");
                            exit();
                        }

                        $conn = connect($db);
                        $uname = mysqli_real_escape_string($conn, $_POST['uname']);
                        $pass = mysqli_real_escape_string($conn, $_POST['pass']);
                        disconnect($conn);

                        $_SESSION['userandpasscheck'] = checklogin($db, $uname, $pass);
                        if (isset($_SESSION['userandpasscheck'])) {
                            if ($_SESSION['userandpasscheck'][0]) {
                                $_SESSION['uname'] = $uname;
                                $_SESSION['userid'] = $_SESSION['userandpasscheck'][1];
                                $_SESSION['admin'] = getUserData($db, $_SESSION['userid'], 'admin');
                                $_SESSION['pfp'] = getUserData($db, $_SESSION['userid'], 'img');
                                header("Location: /home");
                                exit();
                            }
                        }
                    }
            ?>
            </p>
        </div>

        
    </div>

    <footer>

    </footer>
</body>
</html>