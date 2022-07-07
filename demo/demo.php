<?php
include('../sql/connect.php');
include('../sql/get.php');
include('../sql/engine.php');
session_start();
$_SESSION["user_id"] = 'ade';
$_SESSION["prenom_nom"] = 'Aubry Debord';
$_SESSION["mail"] = 'aubry.debord@millergraphics.com';
$_SESSION["pseudo"] = 'ade';
$_SESSION["language"] = 'fr';
$_SESSION['mode']= 'light';  
$_SESSION['homepage']= 'new_order.php';  
$title= 'demo';
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
        <title>Démo tableau de bord</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
<!--[if lt IE 9]><script type="text/javascript" src="js/excanvas.compiled.js"></script><![endif]-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php 
        include('../contents/header.php');        
?>
    <div class="content">



        <div class="intro-y box">
            <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                <h2 class="fw-medium fs-base me-auto">
                    Planning global rectif
                </h2>
            </div>
            <div id="progressbar-height" class="p-5">
                <div class="preview">
                    <!-- lien pour atteindre le planning de ce jour-->
                    <a href="">
                        <div class="mb-6">
                            2022-06-16 <span style="float:right;">(18 heures)</span>
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-4/5" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-17 <span style="float:right;">(18 heures)</span>
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-2/3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-18 <span style="float:right;">(0 heure)</span>
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-19 <span style="float:right;">(0 heure)</span> 
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-20 <span style="float:right;">(12 heures)</span> 
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-4/5" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-21 <span style="float:right;">(18 heures)</span> 
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-2/3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="mb-6">
                            2022-06-22 <span style="float:right;">(18 heures)</span> 
                            <div class="progress h-3 mt-3">
                                <div class="progress-bar w-1/2" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>



        <div class="grid columns-12 gap-6">
            <div class="g-col-12 g-col-xxl-9">
                <div class="grid columns-12 gap-6">

                    <!-- BEGIN: fiber charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                        <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge du jour<br>Fibre
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1" style="border-radius: 0 0 .375rem .375rem;">
                            <em><u>Temps disponible : 6h30</u></em>
                            <canvas class="mt-3"id="report-pie-chart" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">70%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">30%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: fiber charge -->

                    <!-- BEGIN: lining charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge du jour<br>Garnissage
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 10h</u></em>
                            <canvas class="mt-3"id="report-pie-chart2" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#008000 !important;"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">83%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">17%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: lining charge -->

                    <!-- BEGIN: ébauche charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge du jour<br>Ébauche
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 6h</u></em>
                            <canvas class="mt-3"id="report-pie-chart3" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FF0000 !important"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">44%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">56%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: ébauche charge -->

                    <!-- BEGIN: grinding charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge du jour<br>Rectification
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 13h</u></em>
                            <canvas class="mt-3"id="report-pie-chart4" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">58%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">42%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: grinding charge -->

                </div>
            </div>
        </div>

        <div class="row gap-y-6 mt-5">
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box mt-5">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            Informations du jour
                        </h2>
                    </div>
                    <div id="icon-alert" class="p-5">
                        <div class="preview">
                            <div class="alert alert-primary d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-triangle" class="w-6 h-6 me-2"></i>
                                • Thomas Faucon absent<br>
                                • Pinacho en maintenance
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box mt-5">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            Important
                        </h2>
                    </div>
                    <div id="icon-alert" class="p-5">
                        <div class="preview">
                            <div class="alert alert-danger d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-triangle" class="w-6 h-6 me-2"></i>
                                • Commande 00000-000-000-001 en retard<br>
                                • Stock résine bas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="grid columns-12 gap-6">
            <div class="g-col-12 g-col-xxl-9">
                <div class="grid columns-12 gap-6">

                    <!-- BEGIN: fiber charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                        <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge j+1<br>Fibre
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 5h30</u></em>
                            <canvas class="mt-3"id="report-pie-chart5" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">66%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">34%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: fiber charge -->

                    <!-- BEGIN: lining charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge j+1<br>Garnissage
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 8h</u></em>
                            <canvas class="mt-3"id="report-pie-chart6" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#008000 !important;"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">90%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">10%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: lining charge -->

                    <!-- BEGIN: ébauche charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge j+1<br>Ébauche
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 5h</u></em>
                            <canvas class="mt-3"id="report-pie-chart7" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FF0000 !important"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">62%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">38%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: ébauche charge -->

                    <!-- BEGIN: grinding charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                Charge j+1<br>Rectification
                            </h2>
                            <a href="" class="ms-auto text-theme-26 dark-text-theme-33 truncate">Voir planning</a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u>Temps disponible : 8h</u></em>
                            <canvas class="mt-3"id="report-pie-chart8" height="300"></canvas>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate">Charge</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">77%</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate">Disponible</span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto">23%</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: grinding charge -->

                </div>
            </div>
        </div>


        <div class="row gap-y-6 mt-5">
            <div class="intro-y box mt-5">
                <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                    <h2 class="fw-medium fs-base me-auto">
                        Informations jours à venir
                    </h2>
                </div>
                <div id="icon-alert" class="p-5">
                    <div class="preview">
                        <div class="alert alert-warning d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 me-2"></i>
                            • Thomas Faucon jusqu'au 12-06-2022<br>
                            • Absence Alexandre du 14-06-2022 au 16-06-2022
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
        
        include('../contents/footer.php'); ?>

</body>
</html>