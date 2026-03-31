<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--**********************************  Confihretion Start  ***********************************-->
    <?php
    include_once("includes/config.php");
    //echo $common_helper->encrypt("Pass@123"); 
    if (isset($_SESSION['hourglass_session_name']) && $_SESSION['hourglass_session_name'] != "") {
        $common_helper->redirect($_SESSION['dashboard']);
    }
    ?>
    <!--**********************************  Confihretion End  ***********************************-->
    <meta property="og:title" content="<?php echo APPLICATION_NAME; ?>">
    <meta property="og:description" content="Hourglass Research is a leading Intellectual Property (IP) and technology research services firm with offices in San Jose, California, and Bengaluru, India.
We provide Patent Drafting, Prosecution & Searching, and Technology Scouting & Analytics services for multinational companies and startups.">
    <meta property="og:image" content="<?php echo FAVICON_PATH; ?>">
    <title>Login | <?php echo APPLICATION_NAME; ?> </title>
    <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_PATH; ?>">
    <link href="<?php echo  APPLICATION_URL; ?>vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo  APPLICATION_URL; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo  APPLICATION_URL; ?>vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .btn-primary {
            border-color: #2b388f;
            background-color: #2b388f;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="vh-100">

    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="page-wraper">

        <!-- Content -->
        <div class="browse-job login-style3">
            <!-- Coming Soon -->
            <div class="bg-img-fix overflow-hidden" style="background:#fff url(<?php echo  APPLICATION_URL; ?>images/background/bg6.gif);background-repeat: no-repeat; background-size: 60%;  background-position-x: right;background-position-y: center;">
                <div class="row gx-0">
                    <div style="padding-top: 110px; box-shadow: 15px 5px 20px 0px #efeded;" class="col-xl-4 col-lg-5 col-md-6 col-sm-12 vh-100 bg-white ">

                        <a href="index.php" class="logo"><img style="margin-top: 75px;text-align: center;margin: auto;" src="<?php echo LOGO_PATH; ?>" alt="" class="width-230 light-logo"></a>

                        <div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" style="max-height: 653px;" tabindex="0">
                            <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
                                <div class="login-form style-2">


                                    <!--<div class="logo-header">
										<a href="index.html" class="logo"><img src="images/logo/logo-full.png" alt="" class="width-230 light-logo"></a>
										<a href="index.html" class="logo"><img src="images/logo/logofull-white.png" alt="" class="width-230 dark-logo"></a>
									</div>-->

                                    <?php 
                                    if(!isset($_GET['token'])){
                                        echo '<script>alert("Not a valid token!");location.href="index.php";</script>';
                                    }  
                                    ?>

                                    <div class="card-body">
                                        <nav>
                                            <div class="nav nav-tabs border-bottom-0" id="nav-tab" role="tablist">

                                                <div class="tab-content w-100" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                                                        <form action="" class=" dz-form pb-3" onsubmit="return false;" autocomplete="off" id="frm_reset_password" method="post">
                                                            <center>
                                                                <h2 class="form-title m-t0">Reset Password</h2>
                                                            </center></br>
                                                            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                                                            <label for="new_password">New Password:</label>
                                                            <div class="form-group mb-3">
                                                                <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter your password" required>
                                                            </div>
                                                            <label for="confirm_password">Confirm Password:</label>
                                                            <div class="form-group mb-3">
                                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter your password" required>
                                                            </div>
                                                            <input type="submit" class="btn btn-primary" value="Reset Password">
                                                            <input name="action" value="<?php echo $common_helper->encrypt('reset_password'); ?>" type="hidden">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class=" bottom-footer clearfix m-t10 m-b20 row text-center">
                                                    <div class="col-lg-12 text-center">
                                                        <span> © Copyright <?php echo date('Y') ?> <span class="heart"></span>
                                                            <a href="javascript:void(0);"><u><?php echo COMPANY_NAME; ?></u></a> All rights reserved.</span>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                                <div id="mCSB_1_scrollbar_vertical" class="mCSB_scrollTools mCSB_1_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: block;">
                                    <div class="mCSB_draggerContainer">
                                        <div id="mCSB_1_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 0px; display: block; height: 652px; max-height: 643px; top: 0px;">
                                            <div class="mCSB_dragger_bar" style="line-height: 0px;"></div>
                                            <div class="mCSB_draggerRail"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Full Blog Page Contant -->
            </div>
            <!-- Content END-->
        </div>

        <!--**********************************
	Scripts
***********************************-->
        <!-- Required vendors -->
        <script src="<?php echo  APPLICATION_URL; ?>vendor/global/global.min.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>js/deznav-init.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>js/custom.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>js/common.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>js/login.js"></script>
        <script src="<?php echo  APPLICATION_URL; ?>vendor/sweetalert2/dist/sweetalert2.min.js"></script>


</body>

</html>