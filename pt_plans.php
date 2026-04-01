<?php
$pagecode = "PTP-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>PT Plans | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>

	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="javascript:void(0);">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				</li>
				<li class="breadcrumb-item"><a href="javascript:void(0);">PT</a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0);">PT Plans</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_pt_plans'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">PT Plans</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="modal" href="#modal_add_pt_plan" role="button">+ New PT Plan</a>
							</li>
						</ul>
					</div>
					<div id="dynamic_div" class="table-responsive">
						<div class="card-body"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Add/Edit PT Plan -->
	<div class="modal fade" id="modal_add_pt_plan" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="pt_plan_head">Add PT Plan</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.reload();"></button>
				</div>
				<form onsubmit="return false;" id="frm_pt_plan">
					<div class="modal-body">
						<div class="row">
							<div class="col-xl-12 mb-3">
								<label class="form-label">Plan title<span class="text-danger">*</span></label>
								<input type="text" class="form-control" maxlength="150" id="title" name="title" placeholder="" required />
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">Total Sessions<span class="text-danger">*</span></label>
								<input type="number" class="form-control allowOnlyNumeric" maxlength="5" id="total_sessions" name="total_sessions" required />
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">Price<span class="text-danger">*</span></label>
								<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="price" name="price" required />
							</div>
						</div>
						<input type='hidden' name='tab' value="<?php echo 'add_pt_plan'; ?>" />
						<input type='hidden' name='edit_id' id='id' />
						<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
	include_once("includes/footer.php");
	include_once("includes/dynamic_table.php");
	?>

	<script src="vendor/select2/js/select2.full.min.js"></script>
	<script src="js/cms.js"></script>
	<script src="js/plugins-init/select2-init.js"></script>

	<script>
		$(document).on('submit', '#frm_pt_plan', function(e) {
			e.preventDefault();
			$('#modal_add_pt_plan').modal('hide');
			send_ajax_request('frm_pt_plan', '', 'NOP');
		});

		$(document).on('click', '.pt_plan-edit-form', function() {
			$("#preloader").show();
			$("#pt_plan_head").text("Edit PT Plan");
			var element = $(this).data();

			$.each(element, function(index, data) {
				if ($('#' + index).length) {
					$('#' + index).val(data);
				}
			});

			$("#preloader").hide();
			$('#modal_add_pt_plan').modal('show');
		});

		function delete_pt_plan(id) {
			Swal.fire({
				text: "Are you sure you want to delete this PT plan?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				buttonsStyling: false,
			}).then(function(result) {
				if (result.value) {
					$('#preloader').show();
					$.ajax({
						type: "POST",
						url: "index.php?<?php echo $mysqli->encode('stat=ajax'); ?>",
						data: {
							id: id,
							tab: 'delete_pt_plan'
						},
						dataType: "json",
						success: function(obj) {
							$('#preloader').hide();
							if (obj.msg_code == '00') {
								toastr.success(obj.msg);
							} else {
								toastr.error(obj.msg);
							}
							setTimeout(function() {
								window.location.reload();
							}, 1000);
						},
						error: function() {
							$('#preloader').hide();
							toastr.error("Unable to delete PT plan. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

