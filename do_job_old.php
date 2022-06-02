<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
$title='Homepage';
$_SESSION['plan_id']=$_GET['plan_id'];
?>

<!DOCTYPE html>
<html lang="fr" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <!--<link href="images/logo.svg" rel="shortcut icon">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Homepage</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
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
                var hr=0
                var min=0
                var sec=0
                var msec = diff.getMilliseconds()
                var tab = new Array()
                sec = diff.getSeconds()
                min = diff.getMinutes()
                hr = diff.getHours()-1
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
                var dtExpire = new Date();
                dtExpire.setTime(dtExpire.getTime() + 3600 * 1000);
                tab=hr+':'+min+':'+sec;
                setCookie('timer',tab,dtExpire, '/')
                
            }
            function chronoStart(){
                document.chronoForm.startstop.value = "⏸"
                document.chronoForm.stop.style.display = "inline-block"
                document.chronoForm.startstop.onclick = chronoStop
                start = new Date()
                go('00:00:00', 'Started')
                chrono()
            }
            function chronoContinue(){
                document.chronoForm.startstop.value = "⏸"
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
                document.chronoForm.startstop.value = "▶️"
                document.chronoForm.startstop.onclick = chronoContinue
                clearTimeout(timerID)
                tab=getCookie('timer')
                go(tab, 'In progress')
            }

            function chronoFin(){
                document.chronoForm.startstop.style.display = "none"
                document.chronoForm.stop.style.display = "none"
                document.chronoForm.startstop.onclick = chronoContinue
                clearTimeout(timerID)
                tab=getCookie('timer')
                go(tab, 'Finished')
            }

            function setCookie(nom, valeur, expire, chemin, domaine, securite){
                document.cookie = nom + ' = ' + escape(valeur) + '  ' +
                        ((expire == undefined) ? '' : ('; expires = ' + expire.toGMTString())) +
                        ((chemin == undefined) ? '' : ('; path = ' + chemin)) +
                        ((domaine == undefined) ? '' : ('; domain = ' + domaine)) +
                        ((securite == true) ? '; secure' : '');
            }

            function getCookie(name){
                if(document.cookie.length == 0)
                return null;

                var regSepCookie = new RegExp('(; )', 'g');
                var cookies = document.cookie.split(regSepCookie);

                for(var i = 0; i < cookies.length; i++){
                var regInfo = new RegExp('=', 'g');
                var infos = cookies[i].split(regInfo);
                if(infos[0] == name){
                    return unescape(infos[1]);
                }
                }
                return null;
            }

            function go(timer,status){

                popup = window.open("ajax.php?timer="+timer+'&status='+status, '', 'resizable=no, location=no, width=1, height=1, top=1000, scrollbars=no, directories=no, status=no, menubar=no');
                popup.blur();
                window.focus();
            }

            
        </script>
       <script type="text/javascript">
            window.addEventListener('beforeunload', function (e) {
                document.chronoForm.startstop.onclick = chronoStop
                e.preventDefault();
                e.returnValue = '';
            });
        </script>
    </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php'); ?>
        <h4 class="fs-xl fw-medium lh-1 mt-3">Commande <?php echo $_GET['piece'];?></h4>
        <div class="box mt-8 px-8 py-12">
            <span id="chronotime">0:00:00:00</span>
            
            <form id="chronoForm" name="chronoForm">
                <input type="button" name="startstop" value="▶️" onClick="chronoStart()" />
                <input type="button" name="stop" value="⏹" style="display:none" onClick="chronoFin()" />
            </form>
        </div>


<?php include('contents/footer.php'); ?>

</body>
</html>