function calcTime(date) {
    var current = new Date();
    var diff = date-current;
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
    if (diff > 0) {
        document.getElementById("time-left").innerHTML = diffTime[0] + "<span>d</span>:" + diffTime[1] + "<span>h</span>:" + diffTime[2] + "<span>m</span>:" + diffTime[3] + "<span>s</span>";
    } else {
        document.getElementById("time-left").innerHTML = "0" + "<span>d</span>:" + "0" + "<span>h</span>:" + "0" + "<span>m</span>:" + "0" + "<span>s</span>";
    }
}

function countdown(date) {
    const unban = new Date(date);
    calcTime(unban);
    setInterval(calcTime, 500, unban);
}