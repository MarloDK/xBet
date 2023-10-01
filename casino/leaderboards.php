<?php
session_start();
error_reporting(0);
require_once "../public/scripts/functions.php";
require_once "../public/scripts/vars.php";
require_once "../public/required-php/required.php";
auth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php top(); ?>

    <script src="/public/scripts/leaderboards.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="/public/styling/style.css">
    <link rel="stylesheet" href="/public/styling/leaderboards.css">
</head>


<body>
    <?php
        navBar($devbuild, $db);
        sideBar('leaderboards');
    ?>
        
    <div id="page-wrapper">
    <div class="level big-box">
      <svg width="25vw" height="25vw" style="transform: rotate(90deg);">
        <circle cx="12.5vw" cy="12.5vw" r="10vw" fill="none" stroke="#111" stroke-width="10"/>
        <circle cx="12.5vw" cy="12.5vw" r="10vw" id="progress-bar" stroke="#111" fill="none" stroke-width="10"/>
      </svg>
      <div id="percent">0%</div>
      <div class="title">LEVEL <span id="level_display">1</span></div>
    </div>
    <div class="money small-box">
      <div id="money">0/0</div>
      <div class="title">XP</div>
    </div>
    <div class="wins small-box">
      <div id="wins"><?php if (!$devbuild) {echo pull($db, $_SESSION['userid'], 'betWins', 'stats') + pull($db, $_SESSION['userid'], 'lotteryWins', 'stats');} else {echo "1000";} ?></div>
      <div class="title">WINS</div>
    </div>
    </div>

    <?php footer(); ?>

<script>
    var xp = <?php if (!$devbuild) {echo pull($db, $_SESSION['userid'], 'xp', 'stats');} else {echo "1";} ?>;
    var level = <?php if (!$devbuild) {echo pull($db, $_SESSION['userid'], 'level', 'stats');} else {echo "1";} ?>;
    document.body.setAttribute("onload", "myLevel('" + xp + ", " + 100*Math.pow(1.25, level-1) + "')");
    document.getElementById("level_display").innerHTML = level;
    document.getElementById("money").innerHTML = xp + "/" + parseInt(100*Math.pow(1.25, level-1));
</script>
</body>
</html>