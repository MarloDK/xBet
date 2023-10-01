<?php
function top($page = 'xBet'){
    if ($page == 'xBet') {
        echo '
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="shortcut icon" href="/public/images/Logos/BW Cards.ico"/>
        <title>X BET</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Third-Party Assets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Scripts -->
        <script src="/public/scripts/home.js"></script>';
    } elseif ($page == 'suggestions') {
        echo '
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="shortcut icon" href="http://www.x-bet.dk:8080/public/images/Logos/BW Cards.ico"/>
        <title>X BET Suggestions</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Third-Party Assets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
    }
};

     

function navBar($devbuild, $db, $page = 'xBet'){
    if ($page == 'xBet') {
        if (!$devbuild) {
            $balance = pull($db, $_SESSION['userid'], 'penge', 'stats');
        } else {
            $balance = "100.000.000";
        };

        echo '
        <header>
            <nav>
                <div id="balance-display">
                    <img src="/public/images/X-Bet Coin.png">

                    <!-- PENGE -->
                    <p id="balance">'.$balance.'</p>
                </div>

                <a onclick="showprofile()">'.checkPfp($_SESSION['pfp']).'</a>
                <!--<a href=""><i class="fa-solid fa-bell"></i></a>-->
            </nav>

            <div id="acc-drop-down" class="hidden">
                <ul>
                    './* <li><a href="/profile"><i class="fa-solid fa-user"></i> Profile</a></li> */'
                    <li><a class="disabled"><i class="fa-solid fa-user"></i> Profile</a></li>
                    <!-- <li><a href="/settings"><i class="fa-solid fa-gear"></i> Settings</a></li> -->
                    <li><a href="?logout=1"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></li>
                </ul>
            </div>
        </header>';
    } elseif ($page == 'suggestions') {
        echo '
        <header>
            <nav>
                <div id="balance-display">
                </div>
            </nav>
        </header>';
    } elseif ($page == 'profile') {
        echo '
        <header>
            <nav>
                <div id="balance-display">
                </div>
                <a onclick="showprofile()">
                    '.checkPfp($_SESSION['pfp']).'
                </a>
            </nav>
            <div id="acc-drop-down" class="hidden">
                <ul>
                    './* <li><a href="/profile"><i class="fa-solid fa-user"></i> Profile</a></li> */'
                    <li><a class="disabled"><i class="fa-solid fa-user"></i> Profile</a></li>
                    <!-- <li><a href="/settings"><i class="fa-solid fa-gear"></i> Settings</a></li> -->
                    <li><a href="?logout=1"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></li>
                </ul>
            </div>
        </header>';
    }
}

function sideBar($button = '', $page = 'xBet') {
    if ($page == 'xBet') {
        $home = '<a href="/home"><i class="fa-solid fa-house"></i>Home</a>';
        $leaderboards = '<a href="/casino/leaderboards"><i class="fa-solid fa-trophy"></i>Stats</a>';
        $bets = '<a href="/casino/bets"><i class="fa-solid fa-coins"></i>Bets</a>';
        $lottery = '<a href="/casino/lottery"><i class="fa-solid fa-ticket"></i>Lottery</a>';
        /* $lottery = '<a class="disabled"><i class="fa-solid fa-ticket"></i>Lottery</a>'; */
        /* $shop = '<a href="/casino/shop"><i class="fa-solid fa-cart-shopping"></i>Shop</a>'; */
        $shop = '<a class="disabled"><i class="fa-solid fa-cart-shopping"></i>Shop</a>';
        $admin = adminButton();

        if ($button == 'home') {
            $home = '<a href="" class="current-page"><i class="fa-solid fa-house"></i>Home</a>';
        } else if ($button == 'leaderboards' || $button == 'money' || $button == 'wins') {
            if ($button == 'money') {
                $leaderboards = '
                <a href="/casino/leaderboards"><i class="fa-solid fa-trophy"></i>Stats</a>
                    <a href="" class="stats-dropdown, current-page">Money</a>
                    <a href="/casino/stats/wins" class="stats-dropdown">Wins</a>
                ';
            } else if ($button == 'wins') {
                $leaderboards = '
                <a href="/casino/leaderboards"><i class="fa-solid fa-trophy"></i>Stats</a>
                    <a href="/casino/stats/money" class="stats-dropdown">Money</a>
                    <a href="" class="stats-dropdown, current-page">Wins</a>
                ';
            } else {
                $leaderboards = '
                <a href="" class="current-page"><i class="fa-solid fa-trophy"></i>Stats</a>
                    <a href="/casino/stats/money" class="stats-dropdown">Money</a>
                    <a href="/casino/stats/wins" class="stats-dropdown">Wins</a>
                ';
            }
        } else if ($button == 'bets') {
            $bets = '<a href="" class="current-page"><i class="fa-solid fa-coins"></i>Bets</a>';
        } else if ($button == 'lottery') {
            $lottery = '<a href="" class="current-page"><i class="fa-solid fa-ticket"></i>Lottery</a>';
        } else if ($button == 'shop') {
            $shop = '<a href="" class="current-page"><i class="fa-solid fa-cart-shopping"></i>Shop</a>';
        } else if ($button == 'admin') {
            $admin = adminButton(TRUE);
        }
        echo '
        <div id="side-bar">
            <a href="/home"><img src="/public/images/Logos/X-Bet - Cards white (transparent).png" alt="logo"></a>
            <nav>
                '.$home.'
                '.$leaderboards.'
                '.$bets.'
                '.$lottery.'
                '.$shop.'
                '.$admin.'
            </nav>
        </div>';
    } elseif ($page == 'suggestions') {
        $suggestions = '<a href="/suggestions"><i class="fa-solid fa-comments"></i>Suggestions</a>';
        /* $bugs = '<a href="/bug-reports" class="disabled"><i class="fa-solid fa-bug-slash"></i>Bug reports</a>'; */
        $bugs = '<a class="disabled"><i class="fa-solid fa-bug-slash"></i>Bug reports</a>';
        $logoutButton = '<a href="?logout=1"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>';

        if ($button == 'suggestions') {
            $suggestions = '<a href="" class="current-page"><i class="fa-solid fa-comments"></i>Suggestions</a>';
        } elseif ($button == 'bug-reports') {
            $bugs = '<a href="" class="current-page"><i class="fa-solid fa-bug-slash"></i>Bug reports</a>';
        }

        echo '
        <div id="side-bar">
            <a href="/suggestions"><img src="http://www.x-bet.dk:8080/public/images/Logos/X-Bet - Cards white (transparent).png" alt="logo"></a>
            <nav>
                '.$suggestions.'
                '.$bugs.'
                '.$logoutButton.'
            </nav>
        </div>';
    }
}

function footer(){
    echo '
        <footer>
            <p id="version">early-access v1.3.0</p>
        </footer>';
};

# Admin page




?>