<?php
$pagecode = "EMP-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Employees | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>

	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">Employees</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_employees'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Employees</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#canvas_employee" role="button" aria-controls="canvas_employee">+ Add Employee</a>
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

	<!-- Offcanvas Add/Edit Employee -->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff" id="canvas_employee">
		<div class="offcanvas-header">
			<h5 class="modal-title" id="canvas_employee_title">Add Employee</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" onclick="window.location.reload();" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div class="container-fluid">
				<form onsubmit="return false;" id="frm_employee">
					<div class="row">
						<div class="col-xl-12 mb-3">
							<label class="form-label">Name<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowAlphaNumericSpace" maxlength="150" id="name" name="name" required />
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Phone<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="20" id="phone" name="phone" required />
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Designation<span class="text-danger">*</span></label>
							<select id="designation" name="designation" class="single-select form-control wide" required>
								<option value="trainer">trainer</option>
								<option value="receptionist">receptionist</option>
								<option value="cleaner">cleaner</option>
								<option value="manager">manager</option>
								<option value="other">other</option>
							</select>
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Joining Date<span class="text-danger">*</span></label>
							<input type="date" id="joining_date" name="joining_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>" />
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Status<span class="text-danger">*</span></label>
							<select id="status" name="status" class="single-select form-control wide" required>
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>

						<input type="hidden" name="tab" value="<?php echo 'add_employee'; ?>" />
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
		$(document).on('submit', '#frm_employee', function(e) {
			e.preventDefault();
			// Hide immediately for better UX; table reload happens after AJAX success.
			$('#canvas_employee').offcanvas('hide');
			send_ajax_request('frm_employee', '', 'NOP');
		});

		$(document).on('click', '.employee-edit-form', function() {
			$("#preloader").show();
			$("#canvas_employee_title").text("Edit Employee");
			var element = $(this).data();

			// Fill matching form ids (name, phone, designation, joining_date, status, id)
			$.each(element, function(index, data) {
				if ($('#' + index).length) {
					$('#' + index).val(data);
				}
			});

			$("#preloader").hide();
			$('#canvas_employee').offcanvas('show');
		});

		function delete_employee(id) {
			Swal.fire({
				text: "Are you sure you want to delete this employee?",
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
							tab: 'delete_employee'
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
							toastr.error("Unable to delete. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

