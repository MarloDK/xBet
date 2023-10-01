function myLevel(msg) {
    var p=msg.split(",")[0]/msg.split(",")[1]*100;
    var animation_time=2500/p;
    var style_animation_time = animation_time/1000 + "s";
    document.documentElement.style.setProperty("--animation-time", style_animation_time);
    //calc vw
    var vectorTotalLength = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/10*2*Math.PI;
    document.documentElement.style.setProperty('--vector-length',vectorTotalLength);
    var vectorLength = (100 - p)/100*vectorTotalLength;
    document.documentElement.style.setProperty("--vector-shown-length",vectorLength); 
    var percent = 0;
    if (p==0) {
        document.getElementById("percent").innerHTML = '0%';
    } else {
        myInterval = setInterval(() => {
            if (percent==parseInt(p)) {
                clearInterval(myInterval);
            } else {
                percent+=1;
                document.getElementById("percent").innerHTML = percent + '%';
            }
        }, animation_time);
    }
}