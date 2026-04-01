    <?php
	if (!defined('ABSOLUTE_ROOT_PATH')) {
		echo "<script>location.href='../error_403.php'</script>";
		exit;
	}
	if (!isset($_SESSION)) {
		session_start();
	}
	include 'includes/check_session.php';

	?>
    <!--**********************************Header start ***********************************-->
    <meta property="og:title" content="<?php echo APPLICATION_NAME; ?>">
    <meta property="og:description" content="Evosapiens Movement Dashboard">
    <meta property="og:image" content="<?php echo FAVICON_PATH; ?>">
    <meta name="format-detection" content="telephone=no">

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_PATH; ?>">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="./vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.css">

    <link href="./vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
	<!-- Toastr -->
    <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
    <link href="./vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
	<link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- tagify-css -->
    <link href="./vendor/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Style css -->
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/evos-responsive.css" rel="stylesheet">

    <style>
        .swal2-container.swal2-shown { z-index: 99999999999999 !important; }
		.nav-header {
    pointer-events: none;
}

.nav-header .brand-logo,
.nav-header .nav-control {
    pointer-events: auto;
}
    </style>

    </head>

    <body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">

    	<!--*******************
        Preloader start
    ********************-->
    	<div id="preloader">
  <div class="gym-loader-pro">
    
    <div class="gym-bars">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>

    <div class="gym-loader-text">
      Loading...
    </div>

  </div>
</div>
    	<!--*******************
        Preloader end
    ********************-->

    	<!--**********************************
        Main wrapper start
    ***********************************-->
    	<div id="main-wrapper">
    		<!--**********************************
            Nav header start
        ***********************************-->
    		<div class="nav-header" style="width: 300px;" >
    			<div class="brand-logo">
    				<a href="index.php" class="brand-link">
    					<img src="<?php echo LOGO_PATH; ?>" class="logo-abbr" width="100" height="50">
    				</a>
    				<span class="brand-title">Evosapiens Movement</span>
    			</div>
    			<div style="margin-left:60px;" class="nav-control">
    				<div class="hamburger">
    					<span class="line"></span>
    					<span class="line"></span>
    					<span class="line"></span>
    				</div>
    			</div>
    		</div>
    		<!--**********************************
            Nav header end
        ***********************************-->


    		<!--**********************************
            Header start
        ***********************************-->
    		<div class="header">
    			<div class="header-content">
    				<nav class="navbar navbar-expand">
    					<div class="collapse navbar-collapse justify-content-between">
    						<div class="header-left">
    							<!--<div class="input-group search-area">
								<span class="input-group-text"><a href="javascript:void(0)">
									<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="8.78605" cy="8.78605" r="8.23951" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M14.5168 14.9447L17.7471 18.1667" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>

								</a></span>
								<input type="text" class="form-control" placeholder="Search">
							</div> -->
    						</div>
    						<ul class="navbar-nav header-right">

    							<li class="nav-item ps-3">
    								<div class="dropdown header-profile2">
    									<button type="button" class="nav-link btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false">
    										<div class="header-info2 d-flex align-items-center">
    											<div class="header-info">
    												<!--<h3 style="color: #fff;">Welcome to Hourglass Projects!</h3>-->
    											</div>
    										</div>
    									</button>

    								</div>
    							</li>

    							<li class="nav-item ps-3">
    								<div class="dropdown header-profile2 mt-2">
    									<button type="button" class="nav-link btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false">
    										<div class="header-info2 d-flex align-items-center">
    											<div class="header-media">
    												<?php
													
														echo "<img src='" . DEFAULT_PROFILE_PICTURE . "' style='width:30px; height:30px;' class='avatar avatar-md'/>";
													
													?>

    											</div>
    											<div class="header-info profile-text mt-2">
    												<h6><?php echo ucwords($_SESSION['name']); ?></h6>
													
    											</div>

    										</div>
    									</button>
    									<div class="dropdown-menu dropdown-menu-end">
    										<div class="card border-0 mb-0">
    											<div class="card-header py-2">
    <div class="d-flex align-items-center gap-3">

        <!-- Profile Image -->
        <?php
            echo "<img src='" . DEFAULT_PROFILE_PICTURE . "' 
            class='rounded-circle' 
            style='width:45px; height:45px; object-fit:cover;' />";
        ?>

        <!-- User Info -->
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-semibold"><?php echo ucwords($_SESSION['name']); ?></h6>
            <small class="text-muted"><?php echo strtolower($_SESSION['login_id']); ?></small>
        </div>

    </div>
</div>
    											<!-- <div class="card-body px-0 py-2">
    												<a href="javascript:void(0)" class="dropdown-item ai-icon ">
    													<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    														<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    														<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94779 16.58 7.40969C16.58 4.8716 14.5229 2.81445 11.9848 2.81445C9.44667 2.81445 7.38857 4.8716 7.38857 7.40969C7.38 9.93922 9.42381 11.9973 11.9524 12.0059H11.9848Z" stroke="var(--primary)" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round" />
    													</svg>

    													<span class="ms-2">Profile </span>
    												</a>
    											</div> -->
    											<div class="card-footer px-0 py-2">
                                                    <?php $href = 'index.php?' . $mysqli->encode("stat=logout"); ?>
    												<a href="<?php echo $href; ?>" class="dropdown-item ai-icon">
    													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    														<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
    														<polyline points="16 17 21 12 16 7"></polyline>
    														<line x1="21" y1="12" x2="9" y2="12"></line>
    													</svg>
    													<span class="ms-2">Logout </span>
    												</a>
    											</div>
    										</div>

    									</div>
    								</div>
    							</li>
    						</ul>
    					</div>
    				</nav>
    			</div>
    		</div>
    		<!--**********************************
            Header end ti-comment-alt
        ***********************************-->