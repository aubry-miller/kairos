<?php
include('sql/connect.php');
include('sql/get.php');
define('DAYSWEEK',array('1', '2', '3', '4', '5', '6', '7'));
session_start();

$title = 'Mandrel Planning';
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
        <title><?php echo $title;?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
    <?php include('contents/header.php');
    if(isset($_GET['id'])){
        $_SESSION['mandrel_id']=$_GET['id'];
    }

    $mandrel_id=$_SESSION['mandrel_id'];
  

    $list_fer=array(7);

    $list_spe=array('1986-10-31','2009-4-12','2009-9-23');
    $lien_redir="date_info.php";

    $clic=1;
    $month_fr = Array("",
        trad('january',$_SESSION["language"]), 
        trad('february',$_SESSION["language"]), 
        trad('march',$_SESSION["language"]), 
        trad('april',$_SESSION["language"]), 
        trad('may',$_SESSION["language"]), 
        trad('june',$_SESSION["language"]),
        trad('july',$_SESSION["language"]),
        trad('august',$_SESSION["language"]),
        trad('september',$_SESSION["language"]),
        trad('october',$_SESSION["language"]),
        trad('november',$_SESSION["language"]),#‰
        trad('december',$_SESSION["language"]));

    if(isset($_GET['month']) && isset($_GET['year']))
    {#
        $month=$_GET['month'];
        $year=$_GET['year'];
    }
    else
    {
        $month=date("n");
        $year=date("Y");
    }
    
    $l_day=date("t",mktime(0,0,0,$month,1,$year));
    $x=date("N", mktime(0, 0, 0, $month,1 , $year));
    $y=date("N", mktime(0, 0, 0, $month,$l_day , $year));
    $titre=$month_fr[$month]." : ".$year;
    //echo $l_day;
    ?>

    
    <?php
    if($month !='1'){
        $monthbefore=$month-1;
        $yearbefore=$year;
    } else {
        $monthbefore='12';
        $yearbefore=$year-1;
    }

    if($month !='12'){
        $monthafter=$month+1;
        $yearafter=$year;
    } else {
        $monthafter='1';
        $yearafter=$year+1;
    }
    ?>
    <div class="box mt-8 px-8 py-12">
        <center>
            <a href="?month=<?php echo $monthbefore;?>&year=<?php echo $yearbefore;?>" class="il-b_va"><i data-feather="skip-back" class="breadcrumb__icon"></i></a>
            <!-- <center> -->
            <div class="il-b_va" style="width:80%;">
                <form name="dt" method="get" action="">
                <select name="month" id="month" onChange="change()" class="liste">
                <?php
                    for($i=1;$i<13;$i++)
                    {
                        echo '<option value="'.$i.'"';
                        if($i==$month)
                            echo ' selected ';
                        echo '>'.$month_fr[$i].'</option>';
                    }
                ?>
                </select>
                <select name="year" id="year" onChange="change()" class="liste">
                <?php
                    for($i=2022;$i<2035;$i++)
                    {
                        echo '<option value="'.$i.'"';
                        if($i==$year)
                            echo ' selected ';
                        echo '>'.$i.'</option>';
                    }
                ?>
                </select>
                </form>
                <table class="table mt-40"><caption><?php echo $titre ;?></caption>
                    <thead class="table-light">
                        <tr style="text-align:center;">
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('monday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('tuesday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('wednesday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('thursday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('friday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('saturday',$_SESSION["language"]);?></th>
                            <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('sunday',$_SESSION["language"]);?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align:center;">
                        <?php
                        $case=0;
                        if($x>1)
                            for($i=1;$i<$x;$i++)
                            {
                                echo '<td class="desactive">&nbsp;</td>';
                                $case++;
                            }
                        for($i=1;$i<($l_day+1);$i++)
                        {
                            $f=$y=date("N", mktime(0, 0, 0, $month,$i , $year));
                            $da=$year."-".$month."-".$i;
                            $short_date=$month."-".$i;
                            $lien=$lien_redir;
                            $lien.="?id=1&dt=".$da;
                            $answer=get_mandrel_use_by_id_at_date($_SESSION['mandrel_id'],$da);
                            echo "<td";
                            if(!empty($answer)){
                                echo"  style='color:red; background-color: rgb(230,230,230)!important; font-weight: bold;'";
                            } else {
                                echo"  style='color:rgb(130,130,130);";
                                if(in_array($f, $list_fer) || $short_date=='1-1' || $short_date=='5-1' || $short_date=='5-8' || $short_date=='7-14' || $short_date=='8-15' || $short_date=='11-1' || $short_date=='11-11' || $short_date=='12-25'){
                                    echo " background-color: rgb(180,180,180); color:white;'";
                                }
                                else {
                                    echo "'";
                                }
                            }

                            
                            echo" >".$i."</td>";
                            $case++;
                            if($case%7==0){
                                echo "</tr><tr style='text-align:center;'>";
                            }

                            
                        }
                        if($y!=7)
                            for($i=$y;$i<7;$i++)
                            {
                                echo '<td class="desactive">&nbsp;</td>';
                            }
                        ?></tr>
                    </tbody>
                </table>
            </div>
            <!-- </center> -->
            <a href="?month=<?php echo $monthafter;?>&year=<?php echo $yearafter;?>" class="il-b_va"><i data-feather="skip-forward" class="breadcrumb__icon"></i></a>
        </center>
    </div>

    <?php

    include('contents/footer.php'); ?>

</body>
</html>

