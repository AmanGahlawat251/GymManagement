<?php
$pagecode = "MT-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Membership Types | <?php echo APPLICATION_NAME; ?> </title>
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
				<li class="breadcrumb-item active"><a href="javascript:void(0);">Membership Types</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_membership_types'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Membership Types</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#canvas_membership_type" role="button" aria-controls="canvas_membership_type">+ Add Membership Type</a>
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

	<!-- Offcanvas Add/Edit Membership Type -->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff" id="canvas_membership_type">
		<div class="offcanvas-header">
			<h5 class="modal-title" id="canvas_membership_type_title">Add Membership Type</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" onclick="window.location.reload();" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div class="container-fluid">
				<form onsubmit="return false;" id="frm_membership_type">
					<div class="row">
						<div class="col-xl-12 mb-3">
							<label class="form-label">Name<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowAlphaNumericSpace" maxlength="100" id="name" name="name" required />
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Description</label>
							<textarea class="form-control" id="description" name="description" rows="3"></textarea>
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Status<span class="text-danger">*</span></label>
							<select id="status" name="status" class="single-select form-control wide" required>
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>

						<input type="hidden" name="tab" value="<?php echo 'add_membership_type'; ?>" />
						<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>
						<input type="hidden" name="edit_id" id="id" />
					</div>

					<div id="final_btns" style="margin-top: 10px;">
						<button type="submit" class="btn btn-primary me-1">Save</button>
						<button type="button" data-bs-dismiss="offcanvas" class="btn btn-danger light ms-1">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
	include_once("includes/footer.php");
	include_once("includes/dynamic_table.php");
	?>

	<script>
		$(document).on('submit', '#frm_membership_type', function(e) {
			e.preventDefault();
			$('#canvas_membership_type').offcanvas('hide');
			send_ajax_request('frm_membership_type', '', 'NOP');
		});

		$(document).on('click', '.membership-type-edit-form', function() {
			$("#preloader").show();
			$("#canvas_membership_type_title").text("Edit Membership Type");
			var element = $(this).data();
			$.each(element, function(index, data) {
				if ($('#' + index).length) {
					$('#' + index).val(data);
				}
			});
			$("#preloader").hide();
			$('#canvas_membership_type').offcanvas('show');
		});

		function toggle_membership_type(id, status) {
			$('#preloader').show();
			$.ajax({
				type: "POST",
				url: "index.php?<?php echo $mysqli->encode('stat=ajax'); ?>",
				data: {
					id: id,
					status: status,
					tab: 'toggle_membership_type'
				},
				dataType: "json",
				success: function(obj) {
					$('#preloader').hide();
					if (obj.msg_code == '00') toastr.success(obj.msg);
					else toastr.error(obj.msg);
					setTimeout(function() {
						window.location.reload();
					}, 800);
				},
				error: function() {
					$('#preloader').hide();
					toastr.error("Unable to update status. Please try again.");
				}
			});
		}

		function delete_membership_type(id) {
			Swal.fire({
				text: "Delete this membership type? (It cannot be deleted if already used by members)",
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
							tab: 'delete_membership_type'
						},
						dataType: "json",
						success: function(obj) {
							$('#preloader').hide();
							if (obj.msg_code == '00') toastr.success(obj.msg);
							else toastr.error(obj.msg);
							setTimeout(function() {
								window.location.reload();
							}, 800);
						},
						error: function() {
							$('#preloader').hide();
							toastr.error("Unable to delete. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

