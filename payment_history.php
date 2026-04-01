<?php
$pagecode = "PH-001";
include 'includes/check_session.php';
$pageno = 1;

// Supports both:
// - direct file access: payment_history.php?member_row_id=ID
// - routed access via index.php?{encoded} where decoded query contains member_row_id=ID
//   (index.php extracts decoded params into variables before including this file)
$member_row_id = 0;
if (isset($member_row_id) && is_numeric($member_row_id)) {
	$member_row_id = (int)$member_row_id;
} elseif (isset($_GET['member_row_id'])) {
	$member_row_id = (int)$_GET['member_row_id'];
}

$members_qry = $mysqli->executeQry("
	SELECT id, member_id, name
	FROM " . MEMBERS . "
	WHERE status='Active'
	ORDER BY id DESC
");

$member = null;
$membership_id = '';
$payments = [];
$membershipPaidSum = 0.0;
$ptPaidSum = 0.0;

if ($member_row_id > 0) {
	$member = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "' . $member_row_id . '"');
	if ($member && isset($member['member_id'])) {
		$membership_id = $member['member_id'];
		$paymentsRes = $mysqli->executeQry("
			SELECT received_on, payment_type, amount_received, payment_status, pending_amount
			FROM " . REVENUE . "
			WHERE member_id = '" . addslashes($membership_id) . "'
			AND payment_type IN ('MEMBERSHIP','PT')
			ORDER BY received_on DESC
		");

		if ($paymentsRes) {
			while ($row = $mysqli->fetch_assoc($paymentsRes)) {
				$payments[] = $row;
				$amt = isset($row['amount_received']) ? (float)$row['amount_received'] : 0.0;
				if (($row['payment_type'] ?? '') === 'PT') $ptPaidSum += $amt;
				else $membershipPaidSum += $amt;
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Payment History | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>
</head>

<body>
	<div class="content-body">
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="javascript:void(0)">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="javascript:void(0)">Payment History</a>
				</li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Payment History</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a href="javascript:void(0);" onclick="export_payment_history()" class="btn btn-success btn-sm text-white">
									<i class="fa fa-download"></i> Export
								</a>
							</li>
						</ul>
					</div>

					<div class="card-body">
						<div class="row g-3 align-items-end">
							<div class="col-xl-6 col-md-8">
								<label class="form-label">Search by member</label>
								<select id="member_row_id_filter" class="single-select form-control wide" style="width: 100%;" required>
									<option value="">Select Member</option>
									<?php
									while ($mrow = $mysqli->fetch_assoc($members_qry)) {
										$selected = ($mrow['id'] == $member_row_id) ? 'selected' : '';
									?>
										<option value="<?php echo (int)$mrow['id']; ?>" <?php echo $selected; ?>>
											<?php echo htmlspecialchars($mrow['member_id'] . ' - ' . $mrow['name']); ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xl-6 col-md-4">
								<button type="button" onclick="apply_member_filter()" class="btn btn-primary w-100">
									Search
								</button>
							</div>
						</div>

						<?php if ($member_row_id > 0 && $member): ?>
							<div class="mt-4 mb-2">
								<div class="d-flex justify-content-between">
									<span>Member</span>
									<b><?php echo htmlspecialchars($member['member_id'] . ' - ' . $member['name']); ?></b>
								</div>
								<div class="d-flex justify-content-between mt-1">
									<span>Membership Paid</span>
									<b><?php echo number_format($membershipPaidSum, 2); ?></b>
								</div>
								<div class="d-flex justify-content-between mt-1">
									<span>PT Paid</span>
									<b><?php echo number_format($ptPaidSum, 2); ?></b>
								</div>
							</div>
						<?php endif; ?>

						<div class="mt-3">
							<div class="table-responsive">
								<table class="table table-bordered table-sm align-middle">
									<thead class="table-light">
										<tr>
											<th>Date</th>
											<th>Type</th>
											<th>Paid</th>
											<th>Status</th>
											<th>Pending</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($member_row_id > 0 && !empty($payments)): ?>
											<?php foreach ($payments as $pmt): ?>
												<tr>
													<td><?php echo htmlspecialchars($pmt['received_on'] ?? ''); ?></td>
													<td><?php echo htmlspecialchars($pmt['payment_type'] ?? ''); ?></td>
													<td><?php echo htmlspecialchars($pmt['amount_received'] ?? '0'); ?></td>
													<td><?php echo htmlspecialchars($pmt['payment_status'] ?? ''); ?></td>
													<td><?php echo htmlspecialchars($pmt['pending_amount'] ?? '0'); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="5" class="text-center text-muted py-4">
													<?php echo ($member_row_id > 0) ? 'No payment history available.' : 'Select a member to view payment history.'; ?>
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function apply_member_filter() {
			var memberRowId = $('#member_row_id_filter').val();
			var baseUrl = "index.php?<?php echo $mysqli->encode('stat=payment_history'); ?>";
			if (!memberRowId) {
				window.location.href = baseUrl;
				return;
			}
			window.location.href = baseUrl + "&member_row_id=" + encodeURIComponent(memberRowId);
		}

		function export_payment_history() {
			var memberRowId = $('#member_row_id_filter').val();
			if (!memberRowId) {
				toastr.error('Please select a member first.');
				return;
			}
			$('#preloader').show();
			$.ajax({
				type: "POST",
				url: "index.php?<?php echo $mysqli->encode('stat=export_ajax'); ?>",
				dataType: "json",
				timeout: 0,
				data: {
					tab: 'export_payment_history',
					member_row_id: memberRowId
				},
				success: function(obj) {
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
				error: function() {
					$('#preloader').hide();
					toastr.error("Unable to export. Please try again.");
				}
			});
		}
	</script>

</body>

</html>

