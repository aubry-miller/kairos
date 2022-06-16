<?php
include('../sql/connect.php');
include('../sql/get.php');
include('../sql/engine.php');
session_start();
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
        <title><?php echo trad('users',$_SESSION["language"]);?></title>
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
    <h4 class="fs-xl fw-medium lh-1 mt-3 mb-6">Atelier Fibres</h4>
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
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-9" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-9" aria-expanded="false" aria-controls="faq-accordion-collapse-9">
                                            <div style="display:inline-block; width:70%;">
                                                00000000-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                80 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-9" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-9" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 1<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-10" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-10" aria-expanded="false" aria-controls="faq-accordion-collapse-2">
                                            <div style="display:inline-block; width:70%;">
                                                11111111-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                56 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-10" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-10" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client :Exemple 2<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-11" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-11" aria-expanded="false" aria-controls="faq-accordion-collapse-11">
                                            <div style="display:inline-block; width:70%;">
                                                22222222-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                60 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-11" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 3<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-8" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-8" aria-expanded="false" aria-controls="faq-accordion-collapse-8">
                                            <div style="display:inline-block; width:70%;">
                                                23232323-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                60 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-8" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 5<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-7" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-7" aria-expanded="false" aria-controls="faq-accordion-collapse-7">
                                            <div style="display:inline-block; width:70%;">
                                                12121212-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                30 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-7" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 6<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-6" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-6" aria-expanded="false" aria-controls="faq-accordion-collapse-6">
                                            <div style="display:inline-block; width:70%;">
                                                12121212-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                30 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-6" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 7<br>
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
                                </div>
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
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-5" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-5" aria-expanded="false" aria-controls="faq-accordion-collapse-5">
                                            <div style="display:inline-block; width:70%;">
                                                00000000-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                80 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-5" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-6" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 8<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-4" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-4" aria-expanded="false" aria-controls="faq-accordion-collapse-4">
                                            <div style="display:inline-block; width:70%;">
                                                11111111-000-001-002
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                56 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-4" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-7" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client :Exemple 9<br>
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
                                </div>
                                <div class="accordion-item">
                                    <div id="faq-accordion-content-3" class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-3" aria-expanded="false" aria-controls="faq-accordion-collapse-3">
                                            <div style="display:inline-block; width:70%;">
                                                33333333-000-001-001
                                            </div>
                                            <div style="display:inline-block; text-align:right;width:29%;">
                                                48 minutes
                                            </div>
                                        </button>
                                    </div>
                                    <div id="faq-accordion-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-accordion-content-8" data-bs-parent="#faq-accordion-2">
                                        <div class="accordion-body lh-lg">
                                           Client : Exemple 10<br>
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
                                </div>
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