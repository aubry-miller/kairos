<script language="JavaScript">

var startTime = 0
var start = 0
var end = 0
var diff = 0
var timerID = 0
function chrono(){
	end = new Date()
	diff = end - start
	diff = new Date(diff)
	var msec = diff.getMilliseconds()
	var sec = diff.getSeconds()
	var min = diff.getMinutes()
	var hr = diff.getHours()-1
	if (min < 10){
		min = "0" + min
	}
	if (sec < 10){
		sec = "0" + sec
	}
	if(msec < 10){
		msec = "00" +msec
	}
	else if(msec < 100){
		msec = "0" +msec
	}
	document.getElementById("chronotime").innerHTML = hr + ":" + min + ":" + sec + ":" + msec
	timerID = setTimeout("chrono()", 10)
}
function chronoStart(){
	document.chronoForm.startstop.value = "Pause"
    document.chronoForm.stop.style.display = "inline-block"
	document.chronoForm.startstop.onclick = chronoStop
	start = new Date()
	chrono()
}
function chronoContinue(){
	document.chronoForm.startstop.value = "Pause"
	document.chronoForm.startstop.onclick = chronoStop
	start = new Date()-diff
	start = new Date(start)
	chrono()
}

function chronoStopReset(){
	document.getElementById("chronotime").innerHTML = "0:00:00:000"
	document.chronoForm.startstop.onclick = chronoStart
}
function chronoStop(){
	document.chronoForm.startstop.value = "Reprendre"
	document.chronoForm.startstop.onclick = chronoContinue
	clearTimeout(timerID)
}

function chronoFin(){
    document.chronoForm.startstop.style.display = "none"
    document.chronoForm.stop.style.display = "none"
	document.chronoForm.startstop.onclick = chronoContinue
	clearTimeout(timerID)
}

</script>
<span id="chronotime">0:00:00:00</span>
<form name="chronoForm">
    <input type="button" name="startstop" value="Start" onClick="chronoStart()" />
    <input type="button" name="stop" value="Stop" style="display:none" onClick="chronoFin()" />
</form>
