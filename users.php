<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
$title= trad('users',$_SESSION["language"]);
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
        include('contents/header.php');        
        $users=select_all_users_their_rights_and_their_function();
        ?>
        <h4 class="fs-xl fw-medium lh-1 mt-3 mb-6"><?php echo trad('users',$_SESSION["language"]);?></h4>
        <div class="box d-sm-flex mt-8 px-8 py-12">
                <div class="overflow-x-auto">
                        <table class="table mt-40">
                                <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('name',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('login_ident',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('function',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('homepage',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('csr_rights',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('view_planning_by_task',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('global_planning_vision',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('site_management',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('mechanical_manager',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('status',$_SESSION["language"]);?></th>
                                            <th class="border-bottom-0 text-gray-700 "><?php echo trad('edit',$_SESSION["language"]);?></th>
                                        <tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($users as $user){
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $user['us_firstname'].' '.$user['us_name'];?>
                                            </td>
                                            <td>
                                                <?php echo $user['us_login'];?>
                                            </td>
                                            <td>
                                                <?php echo $user['fc_label'];?>
                                            </td>
                                            <td>
                                                <?php echo $user['us_homepage'];?>
                                            </td>
                                            <td class="ta-center">
                                                <?php
                                                if($user['rg_csr_right']==1){
                                                    echo '&#x2713;';
                                                } else {
                                                    echo '&#x292B;';
                                                }
                                                ?>
                                            </td>
                                            <td class="ta-center">
                                                <?php
                                                if($user['rg_task_plan_right']==1){
                                                    echo '&#x2713;';
                                                } else {
                                                    echo '&#x292B;';
                                                }
                                                ?>
                                            </td>
                                            <td class="ta-center">
                                                <?php
                                                if($user['rg_global_plan_right']==1){
                                                    echo '&#x2713;';
                                                } else {
                                                    echo '&#x292B;';
                                                }
                                                ?>
                                            </td>
                                            <td class="ta-center">
                                                <?php
                                                if($user['rg_site_management_right']==1){
                                                    echo '&#x2713;';
                                                } else {
                                                    echo '&#x292B;';
                                                }
                                                ?>
                                            </td>
                                            <td class="ta-center">
                                                <?php
                                                if($user['rg_mechanical_manager_right']==1){
                                                    echo '&#x2713;';
                                                } else {
                                                    echo '&#x292B;';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if($user['us_status']=='enabled'){
                                                    echo '<i data-feather="user-check">';
                                                } else {
                                                    echo '<i data-feather="user-x">';
                                                }
                                                ?>
                                            </td>
                                            <td class="ta-center">
                                                <a href='user_edit.php?user=<?php echo $user['us_id'];?>'><i data-feather="edit"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                        </table>
                </div>
        </div>
        <?php
        
        include('contents/footer.php'); ?>

</body>
</html>