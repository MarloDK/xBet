function calcTime(unban) {
    var current = new Date();
    var diff = unban-current;
    var diffDays = Math.floor(diff/(1000*3600*24));
    var diffHours = Math.floor(diff/(1000*3600)) % 24;
    var diffMinutes = Math.floor(diff/(1000*60)) % 60;
    var diffSeconds = Math.floor(diff/1000) % 60;
    var diffTime = [diffDays, diffHours, diffMinutes, diffSeconds];
    for (let i = 1; i < diffTime.length; i++) {
        if (String(diffTime[i]).length < 2) {
            diffTime[i] = "0" + String(diffTime[i]);
        }
    }
    document.getElementById("time-left").innerHTML = diffTime[0] + "<span>d</span>:" + diffTime[1] + "<span>h</span>:" + diffTime[2] + "<span>m</span>:" + diffTime[3] + "<span>s</span>";

    if (diffTime[0] <= 0 && diffTime[1] <= 0 && diffTime[2] <= 0 && diffTime[3] <= 0) {
        location.href += '?logout=1';
    }
}

function timeTillUnban(unbanDate) {
    const unban = new Date(unbanDate);
    calcTime(unban);
    setInterval(calcTime, 500, unban);
}