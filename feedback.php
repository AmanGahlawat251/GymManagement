<?php 
$pagecode = "PO-009";
include 'includes/check_session.php';
$pageno = 1; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!--**********************************  Header End  ***********************************-->
	<!--**********************************  Header Start  ***********************************-->
	<?php require_once("includes/header.php"); ?>
	<!--**********************************  Header End  ***********************************-->
	<!--**********************************  Sidebar Start  ***********************************-->
	<title>Enquiries | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>
	<!--**********************************  Sidebar End  ***********************************-->
	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
	<style>
		.card-header .collapsed .fas {
			transform: rotate(180deg);
		}

		.select2-container {
			z-index: 100000;
		}
		
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #resetButton {
            
            display: none;
        } 
	.form-control:disabled, .form-control[readonly] {
    background: #efeaea !important;
    opacity: 1 !important;
}
	</style>

	<!--**********************************  Content body start  ***********************************-->
	<div class="content-body">
		<!-- row -->
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						Dashboard </a>
				</li>
				<li class="breadcrumb-item active"><a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
							<path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
						</svg>
						Enquiries</a></li>
			</ol>
			<!--<a class="text-primary fs-13" data-bs-toggle="offcanvas" href="#offcanvasExample1" role="button" aria-controls="offcanvasExample1">+ Add Task</a>-->
		</div>
		<div class="container-fluid">

			<div class="col-xl-12">
							<form onsubmit="return false;" id="frm_search" method="post">
								
								<input type='hidden' name='tab' value="<?php echo 'view_feedbacks'; ?>" />					
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>			  
					<input type="hidden" name="record_limit" id="record_limit" value="10"> 			
					<input type='hidden' name='download' id='download' value="" />		 
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">  
							</form>

			<div class="col-xl-12">
				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Feedbacks</h4>
						
						<div class="card-tools" style="display:none;">
					Reloading in (Seconds): <span style="color:#fff;font-weight:bold" id='timee'></span> &nbsp;&nbsp;
					<a href="javascript:void(0)">
						<i class="fa fa-pause icon-lg" aria-hidden="true" id="timercontroller" onclick="stoptimer()" title="Pause"></i>
					</a>                  
                  
                </div>
					</div>
					<div id="dynamic_div" class="table-responsive">
					<div class="card-body">
						
					</div>					
					
				</div>	
				</div>
			</div>


		</div>

	</div>
	<!--**********************************  Content body end  ***********************************-->

							
<?php 
include_once("includes/footer.php") ; 
include_once("includes/dynamic_table.php") ; 
?>
	<script src="js/croppie.min.js"></script>
	<script src="vendor/select2/js/select2.full.min.js"></script>
	<script src="js/cms.js"></script>
	<script src="js/plugins-init/select2-init.js"></script>

	</body>

</html>