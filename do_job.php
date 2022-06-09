<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();

$job_timer=select_planning_task_by_plan_id($_GET['plan_id']);

if(isset($job_timer["pt_real_time"]) && $job_timer["pt_real_time"] != '00:00:00' && $job_timer["pt_real_time"] != null){
    setcookie('timer_base', $job_timer["pt_real_time"]);
} else {
    setcookie('timer_base', '00:00:00');
}

setcookie('status', $job_timer["pt_status"]);



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
            function chronoContinue2(){
                document.chronoForm.startstop.value = "⏸"
                document.chronoForm.startstop.onclick = chronoStop
                old_timer = new Date('1970-01-01 '+getCookie('timer_base'))
                base_date = new Date('1970-01-01 00:00:00')
                
                diff=old_timer-base_date

                start = new Date()-diff
                start = new Date(start)
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
                document.fish_step_form.style.display = "inline-block"
                document.chronoForm.startstop.onclick = chronoContinue
                clearTimeout(timerID)
                setCookie('status', 'Finished')
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

           if(getCookie('status') != 'Finished'){
                window.addEventListener('beforeunload', function (e) {
                    document.chronoForm.startstop.onclick = chronoStop
                    e.preventDefault();
                    e.returnValue = '';
                });
            }
        </script>
    </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php'); 
        
        
        ?>
        <h4 class="fs-xl fw-medium lh-1 mt-3"><?php echo trad('order',$_SESSION["language"]).' '.$_GET['piece'];?></h4>
        <div class="row gap-y-6 mt-5">
            <?php $response=get_piece_informations_by_id($_GET['piece']);?>
            <div class="intro-y col-12 col-lg-6">
                <div class="box d-sm-flex mt-8 px-8 py-12">
                    <ul style="width:100%;">
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('customer',$_SESSION["language"]);?> :
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['od_customer_name'];?>
                            </div>
                        </li>

                        <li style="width:100%;">
                            <div class="list_marged_label">
                            <?php echo trad('csr',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['od_csr_name'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('rubber',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['rb_label'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('engraving_sleeve_length',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_sleeve_length'];?> mm
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('engraving_length',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_table_length'];?> mm
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('engraving_sleeve_offset',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php 
                                if($response['pc_sleeve_offset'] == 0 || $response['pc_sleeve_offset'] == null){
                                    echo trad('centered',$_SESSION["language"]);
                                } else {
                                    echo trad('decentralized',$_SESSION["language"]);
                                }?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('mandrel_ø',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_mandrel_diameter'];?> mm
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('grinding_ø',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo round($response['pc_developement']/pi(),2);?> mm
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                             <?php echo trad('notch',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['nt_label'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('notch_position',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_notch_position'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('fiber',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['fb_label'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('fiber_thickness',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_fiber_thickness'];?> mm
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('chip',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_chip'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('cutback',$_SESSION["language"]);?> :  
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_cutback'];?>
                            </div>
                        </li>
                        
                        <li style="width:100%;">
                            <div class="list_marged_label">
                                <?php echo trad('cutback_diameter',$_SESSION["language"]);?> : 
                            </div>
                            <div class="list_marged_content">
                                <?php echo $response['pc_cutback_diameter'];?> mm
                            </div>
                        </li>
                        <?php if($response['nt_link'] != null && $response['nt_link'] != 0){?>
                            <li style="width:40%; float:right; margin-top:-180px;">
                                <img style="width: 100%; max-width:160px; float:right; "src="<?php echo $response['nt_link'];?>">
                        </li>
                    <?php } ?>
                        
                    </ul>
                    
                </div>
            </div>
            <div class="intro-y col-12 col-lg-6">
                <div class="box mt-8 px-8 py-12">
                    <?php if(isset($job_timer["pt_real_time"]) && $job_timer["pt_real_time"] != '00:00:00' && $job_timer["pt_real_time"] != null){?>
                        <span id="chronotime"><?php echo $job_timer["pt_real_time"].':000';?></span>
                    <?php } else {?>
                        <span id="chronotime">00:00:00:000</span>
                    <?php } ?>
                    
                    <form id="chronoForm" name="chronoForm">
                        <?php if(isset($job_timer["pt_real_time"]) && $job_timer["pt_real_time"] != '00:00:00' && $job_timer["pt_real_time"] != null){?>
                            <input type="button" name="startstop" value="▶️" onClick="chronoContinue2()" />
                            <input type="button" name="stop" value="⏹" onClick="chronoFin()" />
                        <?php } else {?>
                            <input type="button" name="startstop" value="▶️" onClick="chronoStart()" />
                            <input type="button" name="stop" value="⏹" style="display:none" onClick="chronoFin()" />
                        <?php } ?>
                        
                    </form>
                </div>


                <form id="fish_step_form" name="fish_step_form" style="display:none" action="back/add_material_consumption.php">
                    <input type="hidden" name="plan_id" value="<?php echo $_GET['plan_id'];?>">
                    <div  class="box d-sm-flex mt-8 px-8 py-12" >
                        <div class="form-inline mt-2">
                            <label for="horizontal-form-1" class="form-label w-sm-60">
                                Quantité de mantière (en kg):
                            </label>
                            <input type="number" class="form-control" name="quantity_material" step="0.01" min="0" placeholder="<?php echo $job_timer['pt_expected_material_consumption'];?>" value="<?php echo $job_timer['pt_expected_material_consumption'];?>">
                        </div>
                    </div>

                    <div class="form-inline mt-6">
                        <div class="form-label w-sm-40">
                            <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="edit_user" id="edit_user" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php include('contents/footer.php'); ?>

</body>
</html>