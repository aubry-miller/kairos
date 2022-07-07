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
        <title>Démo charge atelier</title>
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
    <h4 class="fs-xl fw-medium lh-1 mt-3 mb-6">Atelier Garnissage</h4>
    <div class="content">
        <div class="row gap-y-6 mt-5">
            
            <!-- BEGIN: Boxed Accordion -->
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            Aujourd'hui
                        </h2>
                    </div>
                    <div id="boxed-accordion" class="p-5">
                        <div class="preview">
                            <div id="faq-accordion-2" class="accordion accordion-boxed">

                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview0" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                Hamelin - 00000000-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                80 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview0" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            Hamelin - 00000000-000-001-001
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>80 min</b><br>
                                        <br>
                                        Client : Hamelin<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->




                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview1" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                Lysipack - 11111111-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                56 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview1" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            Lysipack - 11111111-000-001-001
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>56 min</b><br>
                                        <br>
                                        Client : Lysipack<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->



                                


                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview2" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                Tilwel - 23232323-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                60 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview2" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            Tilwel - 23232323-000-001-001
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>60 min</b><br>
                                        <br>
                                        Client : Tilwel<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->



                                


                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview3" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                Tilwel - 12121212-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                30 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview3" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            Tilwel - 12121212-000-001-001
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>30 min</b><br>
                                        <br>
                                        Client : Tilwel<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->



                                


                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview4" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                               XXXXXXX - 12121212-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                30 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview4" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            XXXXXXX - 12121212-000-001-002
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>30 min</b><br>
                                        <br>
                                        Client : XXXXXXX<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->


                            </div>
                        </div>
                        <div class="source-code d-none">
                            <button data-target="#copy-boxed-accordion" class="copy-code btn py-1 px-2 btn-outline-secondary"> <i data-feather="file" class="w-4 h-4 me-2"></i> Copy example code </button>
                            <div class="overflow-y-auto mt-3 rounded-2">
                                <pre id="copy-boxed-accordion" class="source-preview"> <code class="fs-xs p-0 rounded-2 html ps-5 pt-8 pb-4 mb-n10 mt-n10"> HTMLOpenTagdiv id=&quot;faq-accordion-2&quot; class=&quot;accordion accordion-boxed&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-1&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-5&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;faq-accordion-collapse-5&quot;HTMLCloseTag OpenSSL Essentials: Working with SSL Certificates, Private Keys HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-5&quot; class=&quot;accordion-collapse collapse show&quot; aria-labelledby=&quot;faq-accordion-content-1&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-2&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-6&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-6&quot;HTMLCloseTag Understanding IP Addresses, Subnets, and CIDR Notation HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-6&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-2&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-3&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-7&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-7&quot;HTMLCloseTag How To Troubleshoot Common HTTP Error Codes HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-7&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-3&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-4&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-8&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-8&quot;HTMLCloseTag An Introduction to Securing your Linux VPS HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-8&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-4&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Boxed Accordion -->



            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box">
                    <div class="intro-y box p-5 mt-1">
                        <em><u>Temps disponible : 5h30</u></em>
                        <canvas class="mt-3"id="report-pie-chart9" height="300" style="max-width:200px; margin:auto;"></canvas>
                        <div class="mt-8">
                            <div class="d-flex align-items-center">
                                <div class="w-2 h-2 bg-theme-17 rounded-circle me-3"></div>
                                <span class="truncate">Charge</span> 
                                <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                <span class="fw-medium ms-xl-auto">96% (5h16)</span> 
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                <span class="truncate">Disponible</span> 
                                <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                <span class="fw-medium ms-xl-auto">04% (0h14)</span> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <hr>
        
        
        
        <div class="row gap-y-6 mt-5">
            
            <!-- BEGIN: Boxed Accordion -->
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            J + 1
                        </h2>
                    </div>
                    <div id="boxed-accordion" class="p-5">
                        <div class="preview">
                            <div id="faq-accordion-2" class="accordion accordion-boxed">

                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview5" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                XXXXXXX - 00000000-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                80 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview5" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            XXXXXXX - 00000000-000-001-002
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>80 min</b><br>
                                        <br>
                                        Client : XXXXXXX<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->




                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview6" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                XXXXXXX - 11111111-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                56 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview6" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            XXXXXXX - 11111111-000-001-002
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>56 min</b><br>
                                        <br>
                                        Client : XXXXXXX<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->




                                <!-- BEGIN: Slide Over Toggle -->
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview7" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                            <div style="display:inline-block; width:70%;">
                                                XXXXXXX - 33333333-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                48 minutes
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END: Slide Over Toggle -->
                                <!-- BEGIN: Slide Over Content -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview7" aria-labelledby="basicSlideOver">
                                    <div class="offcanvas-header p-5">
                                        <h5 class="offcanvas-title fw-medium fs-base">
                                            XXXXXXX - 33333333-000-001-001
                                        </h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        Temps estimé : <b>48 min</b><br>
                                        <br>
                                        Client : XXXXXXX<br>
                                        CSR :<br>
                                        Gomme :<br>
                                        Laize :<br>
                                        Table :<br>
                                        Décalage table :Centrée<br>
                                        ø porteur :<br>
                                        ø rectif :<br>
                                        Encoche :<br>
                                        Position des encoches :<br>
                                        Fibre :<br>
                                        Épaisseur fibre :<br>
                                        Puce :<br>
                                        Épaulement :<br>
                                        Diamètre de l'épaulement :
                                    </div>
                                </div> 
                                <!-- END: Slide Over Content -->

                            </div>
                        </div>
                        <div class="source-code d-none">
                            <button data-target="#copy-boxed-accordion" class="copy-code btn py-1 px-2 btn-outline-secondary"> <i data-feather="file" class="w-4 h-4 me-2"></i> Copy example code </button>
                            <div class="overflow-y-auto mt-3 rounded-2">
                                <pre id="copy-boxed-accordion" class="source-preview"> <code class="fs-xs p-0 rounded-2 html ps-5 pt-8 pb-4 mb-n10 mt-n10"> HTMLOpenTagdiv id=&quot;faq-accordion-2&quot; class=&quot;accordion accordion-boxed&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-1&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-5&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;faq-accordion-collapse-5&quot;HTMLCloseTag OpenSSL Essentials: Working with SSL Certificates, Private Keys HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-5&quot; class=&quot;accordion-collapse collapse show&quot; aria-labelledby=&quot;faq-accordion-content-1&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-2&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-6&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-6&quot;HTMLCloseTag Understanding IP Addresses, Subnets, and CIDR Notation HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-6&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-2&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-3&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-7&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-7&quot;HTMLCloseTag How To Troubleshoot Common HTTP Error Codes HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-7&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-3&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-4&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-8&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-8&quot;HTMLCloseTag An Introduction to Securing your Linux VPS HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-8&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-4&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Boxed Accordion -->



            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box">
                    <div class="intro-y box p-5 mt-1">
                        <em><u>Temps disponible : 5h30</u></em>
                        <canvas class="mt-3"id="report-pie-chart10" height="300" style="max-width:200px; margin:auto;"></canvas>
                        <div class="mt-8">
                            <div class="d-flex align-items-center">
                                <div class="w-2 h-2 bg-theme-17 rounded-circle me-3"></div>
                                <span class="truncate">Charge</span> 
                                <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                <span class="fw-medium ms-xl-auto">57% (3h04)</span> 
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                <span class="truncate">Disponible</span> 
                                <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                <span class="fw-medium ms-xl-auto">43% (2h26)</span> 
                            </div>
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