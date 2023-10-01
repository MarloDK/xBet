<?php
/* dbQuery */
function dbQuery($db, $query) {
    $conn = connect($db);
    $result = $conn->query($query);
    disconnect($conn);
    return $result;
}

/* Console log */
function consoleLog($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

/* checklogin */
function checklogin($db, $uname, $pass) {
    $result = dbQuery($db, 'SELECT ID, Pass FROM users WHERE BINARY Navn = "'.$uname.'";');
           /* login  ID Banned */
    $login = [FALSE, 0, FALSE];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['Pass'])) {
                $banned = dbQuery($db, 'select count(*) from banned where UserID = '.$row['ID'].' and endTid > curtime();')->fetch_assoc();
                if ($banned['count(*)'] == 0) {
                    $login = [TRUE, $row['ID'], FALSE];
                } else {
                    $login = [TRUE, $row['ID'], TRUE];
                }
            }
        }
    }
    if (!$login[0]) {
        echo "<p style='color:red;'>*Username or password was incorrect</p>";
    }
    return $login;
}

/* getuserdata - login */
function getUserData($db, $id, $pull) {
    $result = dbQuery($db, 'select '.$pull.' from users where ID = '.$id.';');
    $row = $result->fetch_assoc();
    return $row[$pull];
}

/* pull */
function pull($db, $userid, $pull, $table) {
    $result = dbQuery($db, 'select '.$pull.' from '.$table.' where UserID = '.$userid.';');
    $row = $result->fetch_assoc();
    return $row[$pull];
}

/* Check profile picture */
function checkPfp($img) {
    if ($img == "") {
        return '<i class="fa-solid fa-circle-user"></i>';
    } else {
        return '<img class="pfp" src="'.$img.'">';
    }
}

/* Leaderboards */
function pullLeaderboard($db, $devbuild, $pull) {
    if (!$devbuild) {
        if ($pull == 'wins') {
            $result = dbQuery($db, 'select users.img, users.Navn, stats.betWins + stats.lotteryWins as wins, stats.level from stats join users on stats.UserID = users.ID order by wins desc;');
        } else if ($pull == 'money') {
            $result = dbQuery($db, 'select users.img, users.Navn, stats.penge, stats.level from stats join users on stats.UserID = users.ID order by penge desc;');
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                if ($pull == 'wins') {
                    echo "<td>".checkPfp($row['img'])."".$row['Navn']."</td><td>".$row['wins']."</td><td>".$row['level']."</td>";
                } else if ($pull == 'money') {
                    echo "<td>".checkPfp($row['img'])."".$row['Navn']."</td><td>".$row['penge']."</td><td>".$row['level']."</td>";
                }
                echo "</tr>";
            }
            return;
        }
    }
        echo "<tr><td>Ingen</td><td>Ingen</td><td>Ingen</td></tr>";
    }

/* Connection and authentication */
function logout($header) {
    session_destroy();
    header("Location: ".$header);
    exit();
}

function auth($header = '/login', $admin = false) {
    if ($_SESSION['userandpasscheck'][2] && $_SERVER['PHP_SELF'] != '/ban.php') {
        header("Location: /ban");
        exit();
    }
    if (!isset($_SESSION['userid']) or !isset($_SESSION['userandpasscheck'])) {
        header("Location: ".$header);
        exit();
    } 
    if ($admin) {
        if ($_SESSION['admin'] != 2) {
            logout($header);
            exit();
        }
    }
    if (isset($_GET['logout'])) {
        logout($header);
    }
}

function connect($db) {
    $conn = new mysqli($db[0], $db[1], $db[2], $db[3]);

    if ($conn->connect_errno) {
        /* echo "Connection failed" . $conn->connect_error; */
        echo "Connection to database failed";
        return;
    }
    return $conn;
}

function disconnect($connection) {
    $connection->close();
}
?>



<?php





/* HOME FUNKTIONER: */


function pullNews($db) {
    $result = dbQuery($db, 'select dato, titel, nyhed, forfatter from nyheder order by ID desc limit 5;');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="page-div">
                <h1 class="page-div-title">'.$row['titel'].'</h1>
                <p>'.str_replace('\n', '<br>', $row['nyhed']).'</p>
                <h1 class="page-div-author">- '.$row['forfatter'].'</h1>
                <h1 class="page-div-date">'.$row['dato'].'</h1>
                </div>';
        }
        return;
    }
}

function pullGuides($db) {
    $result = dbQuery($db, 'select titel, vejledning from vejledninger order by ID desc;');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="page-div">
                <h1 class="page-div-title">'.$row['titel'].'</h1>
                <p>'.str_replace('\n', '<br>', $row['vejledning']).'</p>
                </div>';
        }
        return;
    }
}

function pullFaq($db) {
    $result = dbQuery($db, 'select question, answer from faq order by ID desc;');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<p>Q: '.$row['question'].'</p>
            <p>A: '.$row['answer'].'</p>
            <br>';
        }
        return;
    }
}







/* ADMIN FUNKTIONER: */


function adminButton($admin = FALSE) {
    if ($_SESSION['admin'] == 2) {
        if ($admin) {
            return '<a href="/admin/admin" class="current-page"><i class="fa-solid fa-lock-open"></i>Admin</a>';
        } else {
            return '<a href="/admin/admin"><i class="fa-solid fa-lock"></i>Admin</a>';
        }
    }
}


/* Load */
function loadLotteries($db, $devbuild, $state = "load") {
    if (!$devbuild) {
        if ($state == "load") {
            $result = dbQuery($db, 'select * from lottery where slutTid > curtime() order by slutTid asc limit 1;');
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    return [
                        'ID' => $row['ID'],
                        'lotteryPenge' => $row['lotteryPenge'],
                        'userWinChance' => $row['userWinChance'],
                        'maxTickets' => $row['maxTickets'],
                        'ticketPrice' => $row['ticketPrice'],
                        'slutTid' => $row['slutTid']
                    ];
                }
                return;
            } else {
                return [
                    'ID' => 0,
                    'lotteryPenge' => 0,
                    'userWinChance' => 0,
                    'maxTickets' => 0,
                    'ticketPrice' => 0,
                    'slutTid' => 0
                ];
            };
        } else if ($state == 'view') {
            $result = dbQuery($db, 'select ID, lotteryPenge, userWinChance, maxTickets, ticketPrice, slutTid, winstate, winner from lottery;');
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {        
                    echo "<tr>";
                    echo "<td>".$row['ID']."</td><td>".$row['lotteryPenge']."</td><td>".$row['userWinChance']*(100)." %</td><td>".$row['maxTickets']."</td><td>".$row['ticketPrice']."</td><td>".$row['slutTid']."</td><td>".$row['winstate']."</td><td>".$row['winner']."</td>";
                    echo "</tr>";
                }
                return;
            }
            echo "<tr><td>Ingen</td><td>Ingen</td><td>Ingen</td><td>Ingen</td><td>Ingen</td><td>Ingen</td></tr>";
        }
    }
}

function getBetIDs($db, $devbuild) {
    if (!$devbuild) {
        $result = dbQuery($db, 'select ID, titel from bets where winstate = "WAIT" order by slutTid asc;');
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value='.$row['ID'].'>'.$row['titel'].'</option>';
            }
            return;
        }
    }
    echo "<option value='Ingen Bets'>Ingen Bets</option>";
}

/* Add, edit and join bets */
function addBet($db, $titel, $userID, $slutTid, $devbuild, $joinInPenge, $valg1, $valg2) {
    dbLog($db, 'Addede bet: '.$titel);
    dbQuery($db, 'insert into bets (titel, userID, slutTid, joinInPenge, valg1, valg2) values ("'.$titel.'",'.$userID.', "'.$slutTid.'", '.$joinInPenge.', "'.$valg1.'", "'.$valg2.'");');
    return 'Addede bet: '.htmlentities($titel, ENT_QUOTES, 'UTF-8');
}


function editBet($db, $betID, $winstate, $titel = "", $userID = 0, $slutTid = "") {
    /* dbQuery($db, 'update bets set titel = '.$titel.' userID = '.$userID.', slutTid = '.$slutTid.', joinInPenge = '.$joinInPenge.', winstate = '.$winstate.' where ID = '.$betID.' limit 1;'); */
    
    /* Giv vinderne penge: */
    $result = dbQuery($db, 'select * from joined_bets where betID = '.$betID.';');
    $losingBets = [];
    $winnerBets = [[], []];
    while ($rows = $result->fetch_assoc()){
        if ($rows['chosenValg'] == $winstate) {
            array_push($winnerBets[0], $rows['userID']);
            array_push($winnerBets[1], intval($rows['joinedPenge']));
        } else {
            array_push($losingBets, intval($rows['joinedPenge']));
        }
    }
    $totalMoney = array_sum($winnerBets[1]) + array_sum($losingBets);
    
    $xpPercentage = 0.02;
    $lotterySum = $totalMoney*0.1;
    
    $result = dbQuery($db, 'select winstate from bets where ID = '.$betID.';')->fetch_assoc();
    if ($result['winstate'] == 'WAIT') {
        dbLog($db, 'Rettede bet med ID: '.$betID.' til valg: '.$winstate);
        if (count($winnerBets[1]) == 0) {
            dbQuery($db, 'update bets set winstate = "'.$winstate.'" where ID = '.$betID.' limit 1');
            /* Add losingBets sum til lotteri hvis der ingen vindere er. */
            dbQuery($db, 'update lottery set lotterypenge = lotterypenge + "'.array_sum($losingBets).'" where winstate = "WAIT" limit 1');
            return 'Rettede bet. Ingen vindere.';
        } else if (count($losingBets) == 0) {
            dbQuery($db, 'update bets set winstate = "'.$winstate.'" where ID = '.$betID.' limit 1');
            dbQuery($db, 'update stats join joined_bets on stats.userID = joined_bets.userID set stats.penge = stats.penge+joined_bets.joinedPenge*1.05, stats.betWins = stats.betWins + 1, stats.xp = stats.xp + ('.$totalMoney.'*joined_bets.joinedPenge/'.array_sum($winnerBets[1]).')*'.$xpPercentage.' where joined_bets.chosenValg = "'.$winstate.'" and joined_bets.betID = '.$betID.';');
            return 'Rettede bet. Ingen tabere.';
        }
        
        
        dbQuery($db, 'update stats join joined_bets on stats.userID = joined_bets.userID set stats.penge = stats.penge+'.$totalMoney.'*joined_bets.joinedPenge/'.array_sum($winnerBets[1]).', stats.betWins = stats.betWins + 1, stats.xp = stats.xp + ('.$totalMoney.'*joined_bets.joinedPenge/'.array_sum($winnerBets[1]).')*'.$xpPercentage.' where joined_bets.chosenValg = "'.$winstate.'" and joined_bets.betID = '.$betID.';');
        checkForLevelUp($db);
    } else {
        return 'Bet allerede rettet';
    }
    dbQuery($db, 'update bets set winstate = "'.$winstate.'" where ID = '.$betID.' limit 1');

    return 'Rettede bet';
}

function joinBet($db, $userID, $betID, $valg, $penge) {
    $countrow = dbQuery($db, 'select COUNT(userID) as count from joined_bets where userID = '.$userID.' and betID = '.$betID.';')->fetch_assoc();
    $moneyrow = dbQuery($db, 'select penge from stats where userID = '.$userID.';')->fetch_assoc();
    if ($countrow['count'] == 0 && $moneyrow['penge'] >= $penge) {
        dbQuery($db, 'update stats set penge = penge-'.intval($penge).' where userID = '.$userID.';');
        dbQuery($db, 'insert into joined_bets (userID, betID, chosenValg, joinedPenge) values ('.$userID.', '.$betID.', "'.$valg.'", '.$penge.');');
        echo '<script>location.reload();</script>';
        return 'Joinede bet';
    } else {
        return 'Bruger har allerede bettet';
    }
}


/* Add, edit and join lotteries */
function addLottery($db, $lotteryPenge, $userWinChance, $maxTickets, $ticketPrice, $endDate, $devbuild) {
    if (!$devbuild) {
        dbLog($db, 'Indsatte lottery');
        dbQuery($db, 'insert into lottery (lotteryPenge, userWinChance, maxTickets, ticketPrice, slutTid) values ('.$lotteryPenge.', '.$userWinChance.', '.$maxTickets.', '.$ticketPrice.', "'.$endDate.'");');
        $transferPenge = dbQuery($db, 'select lotteryPenge from lottery where winstate = "TRANSFER";')->fetch_assoc();
        dbQuery($db, 'update lottery set winstate = "DONE" where winstate = "TRANSFER";');
        dbQuery($db, 'update lottery set lotteryPenge = lotteryPenge + '.intval($transferPenge['lotteryPenge']).' where winstate = "WAIT";');
        return ('Addede lottery');
    }
}

function editLottery($db, $lotteryPenge, $userWinChance, $maxTickets, $ticketPrice, $ID) {
    dbLog($db, 'Rettede lottery med ID: '.$ID);
    dbQuery($db, 'update lottery set lotteryPenge = '.$lotteryPenge.', userWinChance = '.$userWinChance.', maxTickets = '.$maxTickets.', ticketPrice = '.$ticketPrice.' where ID = '.$ID.' limit 1;');
}

function joinLottery($db, $userID, $tickets, $ticketPrice, $lotteryID) {
    $countrow = dbQuery($db, 'select COUNT(userID) as count from joined_lotteries where userID = '.$userID.' and lotteryID = '.$lotteryID.';')->fetch_assoc();
    $moneyrow = dbQuery($db, 'select penge from stats where userID = '.$userID.';')->fetch_assoc();
    if ($countrow['count'] == 0 && $moneyrow['penge'] >= $tickets*$ticketPrice) {
        dbQuery($db, 'update stats set penge = penge-'.intval($tickets*$ticketPrice).' where userID = '.$userID.';');
        dbQuery($db, 'update lottery set lotteryPenge = lotteryPenge+'.intval($tickets*$ticketPrice).' where ID = '.$lotteryID.';');
        dbQuery($db, 'insert into joined_lotteries (userID, lotteryID, tickets) values ('.$userID.', '.$lotteryID.', '.$tickets.');');
        echo '<script>location.reload();</script>';
    };
}

function checkLotteryWin($db) {
    $lotteryCount = dbQuery($db, 'select COUNT(*) as count from lottery where winstate = "WAIT" and curtime() > slutTid;')->fetch_assoc();
    if ($lotteryCount['count'] > 0) {
        $lottery = dbQuery($db, 'select * from lottery where winstate = "WAIT" and curtime() > slutTid order by slutTid asc limit 1;')->fetch_assoc();
        $usersInLottery = dbQuery($db, 'select COUNT(*) as count from joined_lotteries where lotteryID = '.$lottery['ID'].';')->fetch_assoc();

        if ($lottery['userWinChance']*10 >= rand(0, 10) && $usersInLottery['count'] > 0) {
            $userTickets = dbQuery($db, 'select SUM(tickets) as sum from joined_lotteries where lotteryID = '.$lottery['ID'].';')->fetch_assoc();
            $winnerTicket = rand(0, $userTickets['sum']);
            
            /* Giv vinderbrugeren penge: */
            $result = dbQuery($db, 'select userID, tickets from joined_lotteries where lotteryID = '.$lottery['ID'].';');
            $possibleWinners = [[], []];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($possibleWinners[0], $row['userID']);
                    array_push($possibleWinners[1], $row['tickets']);
                };
            };
            for ($i=0; $i <= count($possibleWinners[0]); $i++) {
                $winnerTicket = $winnerTicket - $possibleWinners[1][$i];
                if ($winnerTicket <= 0) {
                    dbQuery($db, 'update stats set penge = penge + '.$lottery['lotteryPenge'].' where UserID = '.$possibleWinners[0][$i].';');
                    dbQuery($db, 'update lottery set winstate = "DONE", winner = '.$possibleWinners[0][$i].' where ID = '.$lottery['ID'].';');
                    return;
                }
            }
        } else {
            $lotteries = dbQuery($db, 'select COUNT(*) as count from lottery where winstate = "WAIT" and curtime() < slutTid;')->fetch_assoc()['count'];
            if ($lotteries > 0) {
                dbQuery($db, 'update lottery set winstate = "DONE" where ID = '.$lottery['ID'].';');
                $transferID = dbQuery($db, 'select ID from lottery where winstate = "WAIT" and curtime() < slutTid order by slutTid asc limit 1;')->fetch_assoc()['ID'];
                dbQuery($db, 'update lottery set lotteryPenge = lotteryPenge + '.intval($lottery['lotteryPenge']).' where ID = '.$transferID.';');
            } else {
                dbQuery($db, 'update lottery set winstate = "TRANSFER" where ID = '.$lottery['ID'].';');
            };
        };
    };
}

function lotteryWinner($db, $lottery) {
    $winner = dbQuery($db, 'select winner, lotteryPenge from lottery where winstate = "DONE" or winstate = "TRANSFER" order by slutTid desc limit 1;')->fetch_assoc();
    if ($winner['winner'] != 'Ingen') {
        echo "<p>Tidligere vinder: ".dbQuery($db, 'select Navn from users where ID = '.$winner['winner'].';')->fetch_assoc()['Navn']."</p>";
        echo "<p>Tidligere gevinst: ".$winner['lotteryPenge']."</p>";
    } else {
        echo "<p>Tidligere vinder: Ingen</p>";
        echo "<p>Tidligere gevinst: 0</p>";
    }
    echo "<br>";
    echo "<p>Deltagere: ".dbQuery($db, 'select COUNT(*) as count from joined_lotteries where lotteryID = '.$lottery['ID'].';')->fetch_assoc()['count']."</p>";
}

/* Load users */
function loadUsers($db, $devbuild) {
    if (!$devbuild) {
        $result = dbQuery($db, 'select ID, Navn, admin from users order by admin desc;');
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['admin'] == 2) {
                    $admin = "<td style='color:green;'><i class='fa-solid fa-check'></i>";
                } else {
                    $admin = "<td style='color:red;'><i class='fa-solid fa-x'></i>";
                }

                echo "<tr>";
                echo "<td>".$row['ID']."</td><td>".$row['Navn']."</td>".$admin."</td>";
                echo "</tr>";
            }
            return;
        }
    }
    echo "<tr><td>Ingen</td><td>Ingen</td><td>Ingen</td></tr>";
}



/* Load Logs */
function loadLogs($db, $devbuild) {
    if (!$devbuild) {
        $result = dbQuery($db, 'select UserID, Navn, log, date from logs join users on logs.UserID = users.ID order by date desc;');
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['UserID']."</td><td>".$row['Navn']."</td><td>".$row['log']."</td><td>".$row['date']."</td>";
                echo "</tr>";
            }
            return;
        }
    }
    echo "<tr><td>Ingen</td><td>Ingen</td><td>Ingen</td><td>Ingen</td></tr>";
}

function checkForLevelUp($db) {
    for ($i = 0; $i < 3; $i++) {
        dbQuery($db, 'update stats set xp = xp-(100*power(1.25,level-1)), level = level + 1 where xp >= (100*power(1.25,level-1));');
    }
}

/* Logging */
function dbLog($db, $log) {
    dbQuery($db, 'insert into logs(UserID, log, date) values ('.$_SESSION['userid'].', "'.$log.'", now());');
    return "Loggede: ".$log;
}

?>