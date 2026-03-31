<?php
$pagecode = "CM-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Class Enrollments | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>
	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">Class Enrollments</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_class_members'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">

					<div class="row mt-2 g-2">
						<div class="col-xl-3 col-md-6">
							<label class="form-label">Trainer</label>
							<select id="class_trainer_filter" name="trainer_id" class="single-select form-control wide">
								<option value="">All</option>
								<?php
								$tr2 = $mysqli->executeQry("SELECT id, name FROM " . EMPLOYEES . " WHERE designation='trainer' AND status='Active' ORDER BY name ASC");
								while ($tr = $mysqli->fetch_assoc($tr2)) { ?>
									<option value="<?php echo $tr['id']; ?>"><?php echo $tr['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">Status</label>
							<select id="class_status_filter" name="class_status" class="form-control">
								<option value="">All</option>
								<option value="Enrolled">Enrolled</option>
							</select>
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">From Date</label>
							<input type="date" id="class_from_date_filter" name="from_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">To Date</label>
							<input type="date" id="class_to_date_filter" name="to_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-xl-12 d-flex gap-2 mt-1">
							<button type="button" id="search" class="btn btn-sm btn-primary">Search</button>
							<button type="button" class="btn btn-sm btn-danger" onclick="window.location.reload();">Reset</button>
							<a href="javascript:void(0);" onclick="export_class_members()" class="btn btn-sm btn-success text-white ms-auto">
								<i class="fa fa-download"></i> Export CSV
							</a>
						</div>
					</div>
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Class Enrollments</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#canvas_enroll" role="button" aria-controls="canvas_enroll">+ Enroll Member</a>
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

	<!-- Offcanvas Enroll -->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff" id="canvas_enroll">
		<div class="offcanvas-header">
			<h5 class="modal-title" id="canvas_enroll_title">Enroll Member</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" onclick="window.location.reload();" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div class="container-fluid">
				<form onsubmit="return false;" id="frm_enroll">
					<div class="row">
						<div class="col-xl-12 mb-3">
							<label class="form-label">Schedule<span class="text-danger">*</span></label>
							<select id="class_schedule_id" name="class_schedule_id" class="single-select form-control wide" required>
								<?php
								$sched_qry = $mysqli->executeQry("SELECT s.id, c.title, s.schedule_date, s.start_time, s.end_time
									FROM " . CLASS_SCHEDULE . " s
									JOIN " . CLASSES . " c ON c.id = s.class_id
									WHERE s.status='Active' ORDER BY s.schedule_date DESC");
								while ($sc = $mysqli->fetch_assoc($sched_qry)) { ?>
									<option value="<?php echo $sc['id']; ?>">
										<?php echo $sc['title']; ?> (<?php echo $sc['schedule_date']; ?> <?php echo $sc['start_time']; ?>-<?php echo $sc['end_time']; ?>)
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Member<span class="text-danger">*</span></label>
							<select id="member_id" name="member_id" class="single-select form-control wide" required>
								<?php
								$mem_qry = $mysqli->executeQry("SELECT id, member_id, name FROM " . MEMBERS . " WHERE status='Active' ORDER BY id DESC LIMIT 500");
								while ($m = $mysqli->fetch_assoc($mem_qry)) { ?>
									<option value="<?php echo $m['id']; ?>"><?php echo $m['member_id']; ?> - <?php echo $m['name']; ?></option>
								<?php } ?>
							</select>
						</div>

						<input type="hidden" name="tab" value="<?php echo 'enroll_class_member'; ?>" />
						<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>
					</div>

					<div id="final_btns" style="margin-top: 10px;">
						<button type="submit" class="btn btn-primary me-1">Enroll</button>
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
		$(document).on('submit', '#frm_enroll', function(e) {
			e.preventDefault();
			$('#canvas_enroll').offcanvas('hide');
			send_ajax_request('frm_enroll', '', 'NOP');
		});

		function export_class_members() {
			$('#preloader').show();
			var trainer_id = $('#class_trainer_filter').val();
			var class_status = $('#class_status_filter').val();
			var from_date = $('#class_from_date_filter').val();
			var to_date = $('#class_to_date_filter').val();

			$.ajax({
				type: "POST",
				url: "index.php?<?php echo $mysqli->encode('stat=export_ajax'); ?>",
				dataType: "json",
				timeout: 0,
				data: {
					tab: 'export_class_members',
					trainer_id: trainer_id,
					class_status: class_status,
					from_date: from_date,
					to_date: to_date
				},
				success: function (obj) {
					$('#preloader').hide();
					if (obj.msg_code != '00') {
						toastr.error(obj.msg || 'Unable to export.');
						return;
					}
					var link = document.createElement('a');
					link.href = obj.redirect;
					link.download = obj.redirect.split('/').pop();
					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);
				},
				error: function () {
					$('#preloader').hide();
					toastr.error("Unable to export. Please try again.");
				}
			});
		}

		function remove_enrollment(id) {
			Swal.fire({
				text: "Remove this enrollment?",
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
							tab: 'remove_class_member'
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
							toastr.error("Unable to remove enrollment. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

