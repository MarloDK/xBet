<?php
session_start();
error_reporting(0);
require_once __DIR__."/public/scripts/functions.php";
require_once __DIR__."/public/scripts/vars.php";
require_once __DIR__."/public/required-php/required.php";
auth(admin: true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/home.css">
    <link rel="stylesheet" href="/public/styling/profile.css">
</head>
<body>
    <?php
    navBar($devbuild, $db, $page='profile');
    sideBar();
    ?>
    
    <div id="page-wrapper">
        <div class="container">
            <div id="img-container" onscroll="scrollCoords()">
                <img id="output" onload="checkSize(this)"/>
            </div>
            <input type="range"><br>
            <input type="file"  accept=".jpg,.png" name="image" id="file" onchange="loadFile(event)" style="display: none;"><label for="file" style="cursor: pointer;color:aliceblue;">Select File</label>
            <input type="button" id="upload" style="display:none;" onclick="uploadFile()"><label for="upload" style="cursor: pointer;float:right;color:aliceblue;">Submit Image</label>
        </div>
        <canvas id="canvas" height="128px" width="128px"></canvas>

        <form method="POST" id="pfp-form">
            <input type="hidden" id="upload-value" name="pfp" value="">
        </form>

        <?php
            if (isset($_POST['pfp'])) {
                $conn = connect($db);
                $pfp = mysqli_real_escape_string($conn, $_POST['pfp']);
                disconnect($conn);

                dbQuery($db, "update users set img = '".$pfp."' where ID = ".$_SESSION['userid']." limit 1;");
                $_SESSION['pfp'] = $pfp;
            }
        ?>
    </div>
    <?php footer(); ?>

</body>
<script src="/public/scripts/pfp.js"></script>
<script src="/public/scripts/home.js"></script>
</html>
