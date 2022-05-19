<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu d-md-none">
    <div class="mobile-menu-bar">
        <a href="accueil.php" class="d-flex me-auto">
            <img alt="Logo toolbox" class="w-6" src="images/logo_toolbox_blanc_h80px.png">
        </a>
        <a href="javascript:;" id="mobile-menu-toggler" class="mobile-menu-bar__toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white"></i> </a>
    </div>
    
</div>
<!-- END: Mobile Menu -->
<!-- BEGIN: Top Bar -->
<div class="top-bar-boxed border-bottom border-theme-2 dark-border-dark-3 mt-n7 mt-md-n5 mx-n3 mx-sm-n8 px-3 px-sm-8 mb-12">
    <div class="h-full d-flex align-items-center">
        <!-- BEGIN: Logo -->
        <a href="accueil.php" class="-intro-x d-none d-md-flex">
            <img alt="Logo toolbox" class="w-6" src="images/logo_toolbox_blanc_h80px.png">
            <span class="text-white fs-lg ms-3"> <span class="fw-medium">Kairos</span></span>
        </a>
        <!-- END: Logo -->
        <!-- BEGIN: Breadcrumb-->
        <div class="-intro-x breadcrumb me-auto"> <a href="homepage.php">Kairos</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href="" class="breadcrumb--active"> </a> <?php echo $title;?></div>
        <!-- END: Breadcrumb -->
        
        <!-- BEGIN: Notifications -->
        <div class="intro-x dropdown me-4 me-sm-6">
            <div class="dropdown-toggle notification cursor-pointer" role="button" aria-expanded="false" data-bs-toggle="dropdown"> <i data-feather="bell" class="notification__icon dark-text-gray-300"></i> </div>
            <div class="notification-content pt-2 dropdown-menu">
                <div class="notification-content__box dropdown-content">
                    <div class="notification-content__title dark-text-gray-300"><?php echo trad('notifications',$_SESSION["language"]);?></div>
                </div>
            </div>
        </div>
        <!-- END: Notifications -->
        <!-- BEGIN: Account Menu -->
        <div class="account-menu intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-pill overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-bs-toggle="dropdown">
                <?php
                    $recipes=select_image_profil_by_id_user($_SESSION['user_id']);
                    if(!empty($recipes)){?>
                        <img alt="Icewall Bootstrap HTML Admin Template" src="images/profil/<?php echo $recipes[0][0];?>">
                    <?php } else {?>
                        <img alt="Icewall Bootstrap HTML Admin Template" src="images/profil/defaut.jpg">
                    <?php }
                ?>
            </div>
            <div class="dropdown-menu w-56">
                <ul class="dropdown-content bg-theme-11 dark-bg-dark-6">
                    <li class="p-2">
                        <div class="fw-medium text-white"><?php echo $_SESSION["prenom_nom"]; ?></div>
                    </li>
                    <li>
                        <hr class="dropdown-divider border-theme-12 dark-border-dark-3">
                    </li>
                    <li>
                        <a href="profil.php" class="dropdown-item text-white bg-theme-1-hover dark-bg-dark-3-hover"> <i data-feather="user" class="w-4 h-4 me-2"></i> <?php echo trad('profile',$_SESSION["language"]);?> </a>
                    </li>
                    <!-- <li>
                        <a href="" class="dropdown-item text-white bg-theme-1-hover dark-bg-dark-3-hover"> <i data-feather="lock" class="w-4 h-4 me-2"></i> Reset Password </a>
                    </li> -->
                    <!-- <li>
                        <a href="" class="dropdown-item text-white bg-theme-1-hover dark-bg-dark-3-hover"> <i data-feather="help-circle" class="w-4 h-4 me-2"></i> Help </a>
                    </li> -->
                    <li>
                        <hr class="dropdown-divider border-theme-12 dark-border-dark-3">
                    </li>
                    <li>
                        <a href="deconnexion.php" class="dropdown-item text-white bg-theme-1-hover dark-bg-dark-3-hover"> <i data-feather="toggle-right" class="w-4 h-4 me-2"></i> <?php echo trad('logout',$_SESSION["language"]);?> </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->
<div class="wrapper">
    <div class="wrapper-box">
        <!-- BEGIN: Side Menu -->
        <nav class="side-nav">
            <ul>
                <li>
                    <a href="new_order.php" class="side-menu <?php if(
                                str_contains($_SERVER['REQUEST_URI'],'/new_order.php')
                                ){ echo 'side-menu--active';}?>">
                        <div class="side-menu__icon"> <i data-feather="watch"></i> </div>
                        <div class="side-menu__title"><?php echo trad('orders_awaiting_planning',$_SESSION["language"]);?></div>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="users.php" class="side-menu <?php if(
                                str_contains($_SERVER['REQUEST_URI'],'/users.php')
                                ){ echo 'side-menu--active';}?>">
                        <div class="side-menu__icon"> <i data-feather="user-plus"></i> </div>
                        <div class="side-menu__title"><?php echo trad('users_management',$_SESSION["language"]);?></div>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- END: Side Menu -->
        <!-- BEGIN: Content -->
        <div class="content">

