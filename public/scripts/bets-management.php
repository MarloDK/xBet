<?php
require_once "functions.php";

$maxOptions = 2;
$currentBets = [];

class Bet {
    public String $title = '';
    public DateTime $endDate;
    public Int $minimumBet = 0;

    public Array $betOptions = [];
    public Array $users = [];

    function __construct($id, $title, $minimumBet, $betOptions = [], $users = [], $endDate) {
        $this->id = $id;
        $this->title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $this->minimumBet = $minimumBet;
        $this->endDate = $endDate;

        $this->betOptions = [htmlentities($betOptions[0], ENT_QUOTES, "UTF-8"), htmlentities($betOptions[1], ENT_QUOTES | ENT_IGNORE, "UTF-8")];
        $this->users = $users;
    }

    public function InsertBetOption($option) {
        if (count($this->betOptions) >= $maxOptions) {
            return "Couldn't insert bet option; Too many options inserted.";
        }

        array_push($this->betOptions, $option);
    }
}

// Laver et givent bet objekt om til HTML som bliver vist på siden.
function createBetHtmlObject($bet, $firstBet) {
    if ($firstBet) {
        $mainBet = '<li class="bet-display" id="main-bet">';
    } else {
        $mainBet = '<li class="bet-display">';
    }
    if (count($bet->users) > 0) {
        $topBets = '';
        foreach ($bet->users as $user) {
            $topBets .= '
            <li class="top-bets-user-container">
                <p class="username-display">'.$user[0].'</p><p class="bet-amount-display">X$'.htmlentities($user[1], ENT_QUOTES, 'UTF-8').'</p>
            </li>';
        };
    } else {
        $topBets = '
        <li class="top-bets-user-container--empty">
            <p class="username-display">Ingen bets...</p>
        </li>';
    }    

    echo $mainBet.'
                <h1>'.$bet->title.'</h1>

                <ul class="top-bets">
                    <h2>TOP BETS</h2>
                    '.$topBets.'
                </ul>
                <p class="end-date"><span class="small">Slutter den:</span> <br>'.$bet->endDate->format('d/m H:i').'</p>
                <form method="POST">
                    <ul>
                        <li>
                            <select name="bet-valg" required>
                            <option value=valg1>'.$bet->betOptions[0].'</option>
                            <option value=valg2>'.$bet->betOptions[1].'</option>
                            </select>
                        </li>
                        <li><input type="number" name="bet-amount" min="'.$bet->minimumBet.'" placeholder="min. X$'.$bet->minimumBet.'" required></li>
                    
                        <li><input type="submit" value="PLACE BET" name="'.$bet->id.'" required></li>
                    </ul>
                </form>
            </li>';
            
    return $bet;
};

function pullBets($db, $userID) {
    // Bets array
    $pulledBets = [];

    // Træk bet med keyword fra databasen
    $result = dbQuery($db, "select * from bets where slutTid > current_timestamp() and winstate = 'WAIT' and userID != ".$userID." order by slutTid asc limit 3;");
    $firstBet = TRUE;
    while($row = $result->fetch_assoc()) {
        $usersResult = dbQuery($db, "select joined_bets.betID, users.Navn, joined_bets.joinedPenge from joined_bets join users on joined_bets.UserID = users.ID where betID = ".$row['ID']." order by joinedPenge desc limit 3;");
        $allUsers = [];

        while ($usersRow = $usersResult->fetch_assoc()) {
            array_push($allUsers, [$usersRow['Navn'], $usersRow['joinedPenge']]);
        }

        array_push($pulledBets, createBetHtmlObject(new Bet($row['ID'], $row['titel'], $row['joinInPenge'], [$row['valg1'], $row['valg2']], $allUsers, new Datetime($row['slutTid'])), $firstBet));
        $firstBet = FALSE;
    };

    return $pulledBets;
};
?>
