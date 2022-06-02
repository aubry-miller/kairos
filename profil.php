<?php
include("sql/connect.php");
include("sql/get.php");
session_start();

if(isset($_POST["new_photo_profil"])) {
    $random=random_int(0, 99999);
    $target_dir = "images/profil/";
    $target_file = $target_dir . $random . "_" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $erreur = trad('file_not_image',$_SESSION["language"]);
        $uploadOk = 0;
    }


    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $erreur = trad('sorry_file_too_big',$_SESSION["language"]);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $erreur = trad('sorry_file_format',$_SESSION["language"]);
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $erreur = trad('sorry_file_not_uploaded',$_SESSION["language"]);
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

    include("sql/set.php");
    new_image_profil($random.'_'.$_FILES["fileToUpload"]["name"], $_SESSION['user_id']);



        header("location:profil.php");
    } else {
        $erreur = trad('sorry_file_not_uploaded',$_SESSION["language"]);
    }
    }
}



if(isset($_SESSION["prenom_nom"])  && $_SESSION["connecter"] = "yes"){
    $title=trad('profile',$_SESSION["language"]);?>
    <!doctype html>
    <html lang="fr" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <!--<link href="images/logo.svg" rel="shortcut icon">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <!-- BEGIN: CSS Assets-->
            <link rel="stylesheet" href="dist/css/app.css" />
            <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
        <title><?php echo $title;?></title>
    </head>
    <!-- END: Head -->
    <body class="main">
        <?php 
        include ("contents/header.php");?>
        <?php if(isset($erreur)){
            ?>
            <div  class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-octagon" class="w-6 h-6 me-2"></i> <?php  echo  $erreur  ?> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <i data-feather="x" class="w-4 h-4"></i> </button> </div>
            <?php
        }
        ?>
        <div class="intro-y d-flex flex-column flex-sm-row align-items-center mt-8">
            <h2 class="fs-lg fw-medium me-auto">
                <?php echo $title; ?>
            </h2>
        </div>
        <div class="row gap-y-6 mt-5">
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box" >
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <form name="image_profil" id="image_profil" method="get"  action="back/image_profil.php">
                            <h4 class="fs-xl fw-medium lh-1 mt-3"><?php echo trad('profile_picture_choice',$_SESSION["language"]);?></h4>
                            <div class="mt-6">
                                <?php
                                $results=select_image_profil_by_id_user($_SESSION['user_id']);
                                ?>
                                <div class="inline ml-12">
                                    <input class="inline vert-align_center" type="radio" name="img" value="defaut" required <?php if(empty($results)){ echo "checked";}?>>
                                    <div class="inline vert-align_center dropdown-toggle w-24 h-24 rounded-pill overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-bs-toggle="dropdown">
                                        <img alt="Icewall Bootstrap HTML Admin Template" src="images//profil/defaut.jpg">
                                    </div>
                                </div>
                                <?php
                                $recipes = select_all_photo_profil_by_id_user($_SESSION['user_id']);

                                foreach($recipes as $recipe){?>
                                    <div class="inline ml-12">
                                        <input class="inline vert-align_center" type="radio" name="img" value="<?php echo $recipe['imgp_id'];?>" required <?php if(!empty($results)){ if($results[0][0] == $recipe['imgp_adresse'] ){ echo "checked";} }?>>
                                        <div class="inline vert-align_center dropdown-toggle w-24 h-24 rounded-pill overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-bs-toggle="dropdown">
                                            <img alt="Icewall Bootstrap HTML Admin Template" src="images/profil/<?php echo $recipe['imgp_adresse'];?>">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="enregistrer_image" id="enregistrer_image" >
                        </form>
                    </div>
                </div>
            </div>
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box" >
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <div class="mt-2">
                            <h5 class="fs-lg fw-medium lh-1 mt-3"><?php echo trad('add_profile_picture',$_SESSION["language"]);?></h5>
                            <form action="" method="post" enctype="multipart/form-data" class="mt-40">
                                <div class="mt-2">
                                    <label class="form-label ">
                                        <?php echo trad('select_picture_for_profile',$_SESSION["language"]);?>
                                    </label>
                                    <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" >
                                </div>
                                <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="new_photo_profil">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gap-y-6 mt-5">
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box" >
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <div class="mt-2">
                            <h5 class="fs-lg fw-medium lh-1 mt-3"><?php echo trad('display_mode',$_SESSION["language"]);?></h5>
                            <form action="back/mode_affichage.php" method="get" class="mt-40">
                                <div class="mt-2">
                                    <label class="form-label">
                                        <?php echo trad('Select_display_mode_suits_you',$_SESSION["language"]);?>
                                    </label>
                                    <div class="form-inline mt-2">
                                        <div class="form-check mt-2">
                                            <input id="radio-switch" class="form-check-input" type="radio" name="choix_mode" value="light" <?php if($_SESSION['mode'] == 'light'){ echo 'checked';}?>>
                                            <label class="form-check-label" for="radio-switch">
                                                <?php echo trad('light_mode',$_SESSION["language"]);?>
                                            </label>
                                        </div>
                                        <div class="form-check mt-2 ml-28 radio-phone">
                                            <input id="radio-switch" class="form-check-input" type="radio" name="choix_mode" value="dark" <?php if($_SESSION['mode'] == 'dark'){ echo 'checked';}?>>
                                            <label class="form-check-label" for="radio-switch">
                                                <?php echo trad('dark_mode',$_SESSION["language"]);?>
                                            </label>
                                        </div>
                                    </div>
                                 </div>
                                <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="mode">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box" >
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <div class="mt-2">
                            <h5 class="fs-lg fw-medium lh-1 mt-3"><?php echo trad('language',$_SESSION["language"]);?></h5>
                            <form action="back/choix_langue.php" method="get" class="mt-40">
                                <div class="mt-2">
                                    <label class="form-label">
                                        <?php echo trad('Select_language_suits_you',$_SESSION["language"]);?>
                                    </label>
                                    <div class="form-inline mt-2">
                                        <div class="form-check mt-2">
                                            <input id="radio-switch" class="form-check-input vert-align_center" type="radio" name="language" value="fr" <?php if($_SESSION['language'] == 'fr'){ echo 'checked';}?>>
                                            <label class="form-check-label vert-align_center" for="radio-switch">
                                                <img src="images/flags/french_flag.png" class="flag inline vert-align_center"/>
                                            </label>
                                        </div>
                                        <div class="form-check mt-2 ml-28 radio-phone">
                                            <input id="radio-switch" class="form-check-input vert-align_center" type="radio" name="language" value="en" <?php if($_SESSION['language'] == 'en'){ echo 'checked';}?>>
                                            <label class="form-check-label vert-align_center" for="radio-switch">
                                                <img src="images/flags/english_flag.png" class="flag inline vert-align_center"/>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- <h6 class="fw-medium lh-1 mt-3">En effectuant cette opération vous serrez automatiquement déconnecté</h6> -->
                                </div>
                                <input class="btn btn-primary mt-5" type="submit" value="<?php echo trad('save',$_SESSION["language"]);?>" name="langue">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        



        <?php include ("contents/footer.php");?>
    </body>
    </html>
<?php
// if the person is not authenticated we redirect them to the login page
} else {
    header("location:index.php");
}
?>