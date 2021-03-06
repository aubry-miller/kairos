<?php

include("infos.php");
@$valider = $_POST["valider"];
$erreur = "";
if (isset($_POST["valider"])) {
    include('sql/connect.php');
    include('sql/get.php');
    $user = get_user_by_pseudo_and_password($pseudo, $pass_crypt);
if (count($user) > 0) {
    session_start();
    $_SESSION["user_id"] = $user[0]["us_id"];
    $_SESSION["prenom_nom"] = ucfirst(strtolower($user[0]["us_firstname"])) ." "  .  strtoupper($user[0]["us_name"]);
    $_SESSION["mail"] = strtolower($user[0]["us_firstname"]).'.'.strtolower($user[0]["us_name"]).'@millergraphics.com';
    $_SESSION["pseudo"] = $pseudo;
    $_SESSION["language"] = $user[0]["us_language"];
    $_SESSION['mode']=$user[0]['us_screen_mode'];  
    $_SESSION['homepage']=$user[0]['us_homepage'];  

    include("sql/set.php");

    header("location:".$_SESSION['homepage']);
} else
    $erreur = "Wrong login or password!";
}
?>
 
 <html lang="en" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <!--<link href="images/logo.svg" rel="shortcut icon">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Login</title>
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
    </head>
    <!-- END: Head -->
    <body class="login" style="background-color:rgb(240,240,240);">
        <div class="container px-sm-10">
            <div class="grid columns-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="g-col-2 g-col-xl-1 d-none d-xl-flex flex-column min-vh-screen">
                    <a href="" class="-intro-x d-flex align-items-center pt-5">
                        <img class="-intro-x w-3/4 mt-8" src="images/logo_Kairos.svg">
                        <!-- <span class="text-white fs-lg ms-3"> <span class="fw-medium">Kairos</span> </span> -->
                    </a>
                    <div class="my-auto">
                        <img class="-intro-x w-3/4" style="margin-top:15em;" src="images/your_tool.svg">
                        <!-- <div class="-intro-x text-white fw-medium fs-4xl lh-base mt-10">
                            A few more clicks to 
                            <br>
                            sign in to your account.
                        </div>
                        <div class="-intro-x mt-5 fs-lg text-white text-opacity-70 dark-text-gray-500">Manage all your e-commerce accounts in one place</div> -->
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- <div class="alert alert-danger d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-octagon" class="w-6 h-6 me-2"></i> <?php  echo  $erreur  ?> </div> -->
                <div class="connec_phone_box">
                    <img class="connec_phone" src="images/logo_Kairos.svg">
                </div>
                <div class="g-col-2 g-col-xl-1 h-screen h-xl-auto d-flex py-5 py-xl-0 my-10 my-xl-0">
                    <div class="my-auto mx-auto ms-xl-20 bg-white dark-bg-dark-1 bg-xl-transparent px-5 px-sm-8 py-8 p-xl-0 rounded-2 shadow-md shadow-xl-none w-full w-sm-3/4 w-lg-2/4 w-xl-auto">
                        
                        <h2 class="intro-x fw-bold fs-2xl fs-xl-3xl text-center text-xl-start">
                            Connexion
                        </h2>
                        <form  name="form" id="form_connexion" method="post"  action="">
                            <!-- <div class="intro-x mt-2 text-gray-500 d-xl-none text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div> -->
                                <div class="intro-x mt-8">
                                    <input  id="pseudo" type="text"  name="pseudo"  class="intro-x login__input form-control py-3 px-4 border-gray-300 d-block" style="background-color:white;" placeholder="Pseudo"/><br  />
                                    <input  id="password" type="password"  name="password" class="intro-x login__input form-control py-3 px-4 border-gray-300 d-block mt-4" style="background-color:white;"  placeholder="Mot de passe"  /><br  />
                        </div>
                        <div class="intro-x mt-5 mt-xl-8 text-center text-xl-start">
                            <input class="btn btn-primary py-3 px-4 w-full w-xl-32 me-xl-3 align-top" type="submit"  name="valider"  value="Me connecter"  />
                        </div>
                        </form>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        <!-- <div data-url="login-dark-login.html" class="dark-mode-switcher cursor-pointer shadow-md position-fixed bottom-0 end-0 box dark-bg-dark-2 border rounded-pill w-40 h-12 d-flex align-items-center justify-content-center z-50 mb-10 me-10">
            <div class="me-4 text-gray-700 dark-text-gray-300">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div> -->
        <!-- END: Dark Mode Switcher-->
        <!-- BEGIN: JS Assets-->
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>