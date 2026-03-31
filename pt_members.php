<?php
$pagecode = "PTM-001";
include 'includes/check_session.php';
$pageno = 1;

// Dropdown data (keep simple; for large datasets you can move to Select2 AJAX later).
$trainers_qry = $mysqli->executeQry("SELECT id, name FROM " . EMPLOYEES . " WHERE designation = 'trainer' AND status = 'Active' ORDER BY name ASC");
$members_qry = $mysqli->executeQry("SELECT id, member_id, name FROM " . MEMBERS . " WHERE status = 'Active' ORDER BY id DESC LIMIT 200");
$pt_plans_qry = $mysqli->executeQry("SELECT id, title, total_sessions, price FROM " . PT_PLANS . " ORDER BY id DESC");

$default_plan = $mysqli->fetch_assoc($pt_plans_qry);
// Rewind by re-running the query so loops below still work.
$pt_plans_qry = $mysqli->executeQry("SELECT id, title, total_sessions, price FROM " . PT_PLANS . " ORDER BY id DESC");
$default_price = isset($default_plan['price']) ? $default_plan['price'] : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>PT Sessions | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>

	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">PT</a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0);">PT Sessions</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_pt_members'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">

					<div class="row mt-2 g-2">
						<div class="col-xl-3 col-md-6">
							<label class="form-label">Trainer</label>
							<select id="pt_trainer_filter" name="trainer_id" class="single-select form-control wide">
								<option value="">All</option>
								<?php
								$trainers_qry2 = $mysqli->executeQry("SELECT id, name FROM " . EMPLOYEES . " WHERE designation='trainer' AND status='Active' ORDER BY name ASC");
								while ($tr = $mysqli->fetch_assoc($trainers_qry2)) { ?>
									<option value="<?php echo $tr['id']; ?>"><?php echo $tr['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">Status</label>
							<select id="pt_status_filter" name="pt_status" class="form-control">
								<option value="">All</option>
								<option value="Active">Active</option>
								<option value="Completed">Completed</option>
							</select>
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">From Date</label>
							<input type="date" id="pt_from_date_filter" name="from_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label">To Date</label>
							<input type="date" id="pt_to_date_filter" name="to_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="col-xl-12 d-flex gap-2 mt-1">
							<button type="button" id="search" class="btn btn-sm btn-primary">Search</button>
							<button type="button" class="btn btn-sm btn-danger" onclick="window.location.reload();">Reset</button>
							<a href="javascript:void(0);" onclick="export_pt_members()" class="btn btn-sm btn-success text-white ms-auto">
								<i class="fa fa-download"></i> Export CSV
							</a>
						</div>
					</div>
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">PT Sessions</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="modal" href="#modal_add_pt_member" role="button">+ Assign PT</a>
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

	<!-- Add PT to Member -->
	<div class="modal fade" id="modal_add_pt_member" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Assign PT</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<form onsubmit="return false;" id="frm_pt_member">
					<div class="modal-body">
						<div class="row">
							<div class="col-xl-12 mb-3">
								<label class="form-label">Member<span class="text-danger">*</span></label>
								<select id="member_id" name="member_id" class="single-select form-control wide" required>
									<?php while ($mem = $mysqli->fetch_assoc($members_qry)) { ?>
										<option value="<?php echo $mem['member_id']; ?>">
											<?php echo $mem['member_id']; ?> - <?php echo $mem['name']; ?>
										</option>
									<?php } ?>
								</select>
							</div>

							<div class="col-xl-12 mb-3">
								<label class="form-label">PT Plan<span class="text-danger">*</span></label>
								<select id="pt_plan_id" name="pt_plan_id" class="single-select form-control wide" required>
									<?php while ($plan = $mysqli->fetch_assoc($pt_plans_qry)) { ?>
										<option value="<?php echo $plan['id']; ?>"><?php echo $plan['title']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="col-xl-12 mb-3">
								<label class="form-label">Trainer<span class="text-danger">*</span></label>
								<select id="trainer_id" name="trainer_id" class="single-select form-control wide" required>
									<?php while ($tr = $mysqli->fetch_assoc($trainers_qry)) { ?>
										<option value="<?php echo $tr['id']; ?>"><?php echo $tr['name']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="col-xl-12 mb-3">
								<label class="form-label">Start Date<span class="text-danger">*</span></label>
								<input type="date" id="start_date" name="start_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>" />
							</div>

						<div class="col-xl-6 mb-3">
							<label class="form-label">PT Plan Price (reference)</label>
							<input type="text" class="form-control allowOnlyNumeric" id="pt_plan_price_modal" readonly />
						</div>
						<div class="col-xl-6 mb-3">
							<label class="form-label">Final Amount<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pt_final_amount" name="final_amount" required value="0" />
							<small class="text-muted d-block mt-1">Enter final deal amount agreed with customer</small>
						</div>

							<div class="col-xl-6 mb-3">
								<label class="form-label">Payment Mode<span class="text-danger">*</span></label>
								<select id="mode" name="mode" class="single-select form-control wide" required>
									<option value="Cash">Cash</option>
									<option value="UPI">UPI</option>
								</select>
							</div>

							<div class="col-xl-6 mb-3">
							<label class="form-label">Paid Amount<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="paid" name="paid" required value="0" />
							</div>

						<div class="col-xl-12 mb-3">
							<label class="form-label">Pending Amount</label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pt_pending_amount_modal" readonly value="0" />
						</div>
						</div>

						<input type='hidden' name='tab' value="<?php echo 'add_pt_member'; ?>" />
						<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Assign</button>
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
		$(document).on('submit', '#frm_pt_member', function(e) {
			e.preventDefault();
			$('#modal_add_pt_member').modal('hide');
			send_ajax_request('frm_pt_member', '', 'NOP');
		});

		function recalcPtModalPending() {
			var finalAmount = parseFloat($('#pt_final_amount').val() || 0);
			var paidAmount = parseFloat($('#paid').val() || 0);
			if (finalAmount < 0) finalAmount = 0;
			if (paidAmount < 0) paidAmount = 0;
			// Validation: Paid Amount must not exceed Final Amount
			if (paidAmount > finalAmount) {
				paidAmount = finalAmount;
				$('#paid').val(paidAmount);
			}
			var pending = finalAmount - paidAmount;
			if (pending < 0) pending = 0;
			$('#pt_pending_amount_modal').val(pending.toFixed(2));
		}

		function loadPtPlanPrice() {
			var pid = $('#pt_plan_id').val();
			if (!pid) {
				$('#pt_plan_price_modal').val('');
				$('#pt_final_amount').val('0');
				$('#pt_pending_amount_modal').val('0');
				return;
			}
			$.ajax({
				type: "POST",
				url: window.AJAX_URL,
				data: { tab: 'get_pt_plan_price', pt_plan_id: pid },
				success: function(data) {
					var obj = $.parseJSON(data);
					var price = parseFloat(obj.price || 0);
					$('#pt_plan_price_modal').val(price.toFixed(2));
					// Default: Final Amount = PT plan price, Paid Amount = 0
					$('#pt_final_amount').val(price.toFixed(2));
					$('#paid').val('0');
					recalcPtModalPending();
				},
				error: function() {
					toastr.error("Unable to load PT plan price.");
				}
			});
		}

		$(document).ready(function() {
			loadPtPlanPrice();
		});

		$(document).on('change', '#pt_plan_id', function() {
			loadPtPlanPrice();
		});
		$(document).on('keyup change', '#pt_final_amount,#paid', function() {
			recalcPtModalPending();
		});

		function export_pt_members() {
			$('#preloader').show();
			var trainer_id = $('#pt_trainer_filter').val();
			var pt_status = $('#pt_status_filter').val();
			var from_date = $('#pt_from_date_filter').val();
			var to_date = $('#pt_to_date_filter').val();

			$.ajax({
				type: "POST",
				url: "index.php?<?php echo $mysqli->encode('stat=export_ajax'); ?>",
				dataType: "json",
				timeout: 0,
				data: {
					tab: 'export_pt_members',
					trainer_id: trainer_id,
					pt_status: pt_status,
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

		function mark_pt_session(pt_member_id) {
			Swal.fire({
				text: "Mark next PT session as completed?",
				icon: 'question',
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
							pt_member_id: pt_member_id,
							tab: 'mark_pt_session'
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
							}, 800);
						},
						error: function() {
							$('#preloader').hide();
							toastr.error("Unable to mark session. Please try again.");
						}
					});
				}
			});
		}
	</script>
</body>

</html>

