<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
$title='user_edit';
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
        <title>user_edit</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
<!--[if lt IE 9]><script type="text/javascript" src="js/excanvas.compiled.js"></script><![endif]-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php'); 

        $user=select_one_user_his_rights_and_his_function($_GET['user']);
        ?>
        <div class="box d-sm-flex mt-8 px-8 py-12">
            <form class="mt-8" name="edit_user" id="edit_user" method="get"  action="back/run_edit_user.php">
                <input type="hidden" name="user_id" value="<?php echo $user['us_id'];?>">
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Prénom:
                    </label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo $user['us_firstname'];?>">
                </div>
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Nom:
                    </label>
                    <input type="text" class="form-control" name="name" value="<?php echo $user['us_name'];?>">        
                </div>
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Login:
                    </label>
                    <input type="text" class="form-control" name="login" value="<?php echo $user['us_login'];?>">        
                </div>
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Select language:
                    </label>
                    <div class="form-inline mt-2">
                        <div class="form-check mt-2">
                            <input id="radio-switch" class="form-check-input vert-align_center" type="radio" name="language" value="fr" <?php if($user['us_language'] == 'fr'){ echo 'checked';}?>>
                            <label class="form-check-label vert-align_center" for="radio-switch">
                                <img src="images/flags/french_flag.png" class="flag inline vert-align_center"/>
                            </label>
                        </div>
                        <div class="form-check mt-2 ml-12 radio-phone">
                            <input id="radio-switch" class="form-check-input vert-align_center" type="radio" name="language" value="en" <?php if($user['us_language'] == 'en'){ echo 'checked';}?>>
                            <label class="form-check-label vert-align_center" for="radio-switch">
                                <img src="images/flags/english_flag.png" class="flag inline vert-align_center"/>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Fonction:
                    </label>

                    <select class="tom-select w-full" name="function" size='1'>
                        <option value="<?php echo $user['fc_id'];?>"><?php echo $user['fc_label'];?></option>
                        <?php $results=select_all_functions();
                        foreach($results as $result){
                            if($user['fc_label'] != $result['fc_label']){
                                ?>
                                <option value="<?php echo $result['fc_id'];?>"><?php echo $result['fc_label'];?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-inline mt-2">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Homepage:
                    </label>
                    <input type="text" class="form-control" name="homepage" value="<?php echo $user['us_homepage'];?>">        
                </div>
                <div class="form-inline mt-6">
                    <label for="horizontal-form-1" class="form-label w-sm-40">
                        Actif
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" 
                        <?php
                        if($user['us_status'] == 'enabled'){
                            echo 'checked';
                        }
                        ?>                        
                        >
                    </div>
                </div>

                <table class="table mt-40">
                    <thead class="table-light">
                        <tr>
                            <th class="border-bottom-0 text-gray-700 ">
                                Droits CSR
                            </th>
                            <th class="border-bottom-0 text-gray-700 ">
                                Droits vision planning taches
                            </th>
                            <th class="border-bottom-0 text-gray-700 ">
                                Droits vision planning global
                            </th>
                            <th class="border-bottom-0 text-gray-700 ">
                                Droits manager macanique
                            </th>
                            <th class="border-bottom-0 text-gray-700 ">
                                Droits admin site
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="csr_right" 
                                <?php
                                if($user['rg_csr_right'] == '1'){
                                    echo 'checked';
                                }
                                ?>                        
                                >
                            </td>
                            <td>
                                <input type="checkbox" name="task_plan_right" 
                                <?php
                                if($user['rg_task_plan_right'] == '1'){
                                    echo 'checked';
                                }
                                ?>                        
                                >
                            </td>
                            <td>
                                <input type="checkbox" name="global_plan_right" 
                                <?php
                                if($user['rg_global_plan_right'] == '1'){
                                    echo 'checked';
                                }
                                ?>                        
                                >
                            </td>
                            <td>
                                <input type="checkbox" name="mechanical_manager_right" 
                                <?php
                                if($user['rg_mechanical_manager_right'] == '1'){
                                    echo 'checked';
                                }
                                ?>                        
                                >
                            </td>
                            <td>
                                <input type="checkbox" name="site_management_right" 
                                <?php
                                if($user['rg_site_management_right'] == '1'){
                                    echo 'checked';
                                }
                                ?>                        
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>



                <div class="form-inline mt-6">
                    <div class="form-label w-sm-40">
                        <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="edit_user" id="edit_user" onclick="return confirm('Modifier cet utilisateur?')">
                    </div>
                </div>

            </form>
        </div>
        <?php


    include('contents/footer.php'); ?>

</body>
</html>