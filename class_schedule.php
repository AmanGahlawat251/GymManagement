<?php
$pagecode = "CS-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Class Schedule | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>
	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">Class Schedule</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_class_schedule'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Class Schedule</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#canvas_schedule" role="button" aria-controls="canvas_schedule">+ Add Schedule</a>
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

	<!-- Offcanvas Add/Edit Schedule -->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff" id="canvas_schedule">
		<div class="offcanvas-header">
			<h5 class="modal-title" id="canvas_schedule_title">Add Schedule</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" onclick="window.location.reload();" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div class="container-fluid">
				<form onsubmit="return false;" id="frm_schedule">
					<div class="row">
						<div class="col-xl-12 mb-3">
							<label class="form-label">Class<span class="text-danger">*</span></label>
							<select id="class_id" name="class_id" class="single-select form-control wide" required>
								<?php
								$classes = $mysqli->executeQry("SELECT id, title FROM " . CLASSES . " WHERE status='Active' ORDER BY title ASC");
								while ($cl = $mysqli->fetch_assoc($classes)) { ?>
									<option value="<?php echo $cl['id']; ?>"><?php echo $cl['title']; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Schedule Date<span class="text-danger">*</span></label>
							<input type="date" id="schedule_date" name="schedule_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>" />
						</div>

						<div class="col-xl-6 mb-3">
							<label class="form-label">Start Time<span class="text-danger">*</span></label>
							<input type="time" id="start_time" name="start_time" class="form-control" required value="07:00" />
						</div>

						<div class="col-xl-6 mb-3">
							<label class="form-label">End Time<span class="text-danger">*</span></label>
							<input type="time" id="end_time" name="end_time" class="form-control" required value="08:00" />
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Status<span class="text-danger">*</span></label>
							<select id="status" name="status" class="single-select form-control wide" required>
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>

						<input type="hidden" name="tab" value="<?php echo 'add_class_schedule'; ?>" />
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
		$(document).on('submit', '#frm_schedule', function(e) {
			e.preventDefault();
			$('#canvas_schedule').offcanvas('hide');
			send_ajax_request('frm_schedule', '', 'NOP');
		});

		$(document).on('click', '.schedule-edit-form', function() {
			$("#preloader").show();
			$("#canvas_schedule_title").text("Edit Schedule");
			var element = $(this).data();
			$.each(element, function(index, data) {
				if ($('#' + index).length) {
					$('#' + index).val(data);
				}
			});
			$("#preloader").hide();
			$('#canvas_schedule').offcanvas('show');
		});

		function delete_schedule(id) {
			Swal.fire({
				text: "Are you sure you want to delete this schedule?",
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
							tab: 'delete_class_schedule'
						},
						dataType: "json",
						success: function(obj) {
							$('#preloader').hide();
							if (obj.msg_code == '00') toastr.success(obj.msg);
							else toastr.error(obj.msg);
							setTimeout(function() {
								window.location.reload();
							}, 1000);
						},
						error: function() {
							$('#preloader').hide();
							toastr.error("Unable to delete schedule. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

