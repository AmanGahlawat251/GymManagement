<?php
$pagecode = "TR-001";
include 'includes/check_session.php';
$trainer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($trainer_id < 1) {
	echo "<script>location.href='index.php?".$mysqli->encode("stat=login")."'</script>";
	exit;
}
$today = date('Y-m-d');

// Session counters (trainer only)
$qTotal = $mysqli->executeQry("
	SELECT COUNT(ps.id) AS c
	FROM " . PT_SESSIONS . " ps
	JOIN " . PT_MEMBERS . " pm ON pm.id = ps.pt_member_id
	WHERE pm.trainer_id = '" . $trainer_id . "'
");
$totalSessions = $qTotal ? (int)($mysqli->fetch_array($qTotal)['c'] ?? 0) : 0;

$qCompleted = $mysqli->executeQry("
	SELECT COUNT(ps.id) AS c
	FROM " . PT_SESSIONS . " ps
	JOIN " . PT_MEMBERS . " pm ON pm.id = ps.pt_member_id
	WHERE pm.trainer_id = '" . $trainer_id . "' AND ps.status = 'Used'
");
$completedSessions = $qCompleted ? (int)($mysqli->fetch_array($qCompleted)['c'] ?? 0) : 0;

$qPending = $mysqli->executeQry("
	SELECT COUNT(ps.id) AS c
	FROM " . PT_SESSIONS . " ps
	JOIN " . PT_MEMBERS . " pm ON pm.id = ps.pt_member_id
	WHERE pm.trainer_id = '" . $trainer_id . "' AND ps.status = 'Pending'
");
$pendingSessions = $qPending ? (int)($mysqli->fetch_array($qPending)['c'] ?? 0) : 0;

// Today's assigned PT sessions list (active assignments where today falls in range)
$qToday = $mysqli->executeQry("
	SELECT
		pm.id AS pt_member_id,
		pm.member_id,
		m.name AS member_name,
		pm.start_date,
		pm.end_date,
		pm.total_sessions,
		pm.sessions_used,
		pm.status,
		p.title AS plan_title
	FROM " . PT_MEMBERS . " pm
	JOIN " . MEMBERS . " m ON m.member_id = pm.member_id
	JOIN " . PT_PLANS . " p ON p.id = pm.pt_plan_id
	WHERE pm.trainer_id = '" . $trainer_id . "'
		AND pm.status = 'Active'
		AND pm.start_date <= '" . $today . "'
		AND pm.end_date >= '" . $today . "'
	ORDER BY pm.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Trainer Dashboard | <?php echo APPLICATION_NAME; ?></title>
	<?php require_once("includes/sidebar.php"); ?>

	<style>
		.evos-kpi { border: 0; border-radius: 16px; color: #fff; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,.12); }
		.evos-kpi .card-body { padding: 18px 18px; }
		.evos-kpi .kpi-icon { width: 46px; height: 46px; display:flex; align-items:center; justify-content:center; border-radius: 14px; background: rgba(255,255,255,.18); }
		.evos-grad-1 { background: linear-gradient(135deg,#111827,#1d4ed8); }
		.evos-grad-2 { background: linear-gradient(135deg,#0f172a,#10b981); }
		.evos-grad-3 { background: linear-gradient(135deg,#111827,#f59e0b); }
		.evos-grad-4 { background: linear-gradient(135deg,#111827,#ef4444); }
		.evos-grad-5 { background: linear-gradient(135deg,#111827,#06b6d4); }
		.evos-grad-6 { background: linear-gradient(135deg,#111827,#a855f7); }
	</style>
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
			<li class="breadcrumb-item"><a href="javascript:void(0)">PT</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Trainer Dashboard</a></li>
		</ol>
	</div>

	<div class="container-fluid">
		<div class="row g-3">
			<div class="col-6 col-lg-4">
				<div class="card evos-kpi evos-grad-6">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<div class="fw-semibold">Total PT Sessions</div>
							<div class="fs-3 fw-bold"><?php echo $totalSessions; ?></div>
						</div>
						<div class="kpi-icon"><i class="fa fa-bolt"></i></div>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-4">
				<div class="card evos-kpi evos-grad-2">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<div class="fw-semibold">Completed</div>
							<div class="fs-3 fw-bold"><?php echo $completedSessions; ?></div>
						</div>
						<div class="kpi-icon"><i class="fa fa-check-circle"></i></div>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-4">
				<div class="card evos-kpi evos-grad-1">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<div class="fw-semibold">Pending</div>
							<div class="fs-3 fw-bold"><?php echo $pendingSessions; ?></div>
						</div>
						<div class="kpi-icon"><i class="fa fa-hourglass-half"></i></div>
					</div>
				</div>
			</div>
		</div>

		<div class="card dz-card mt-3">
			<div class="card-header flex-wrap">
				<h4 class="heading mb-0">Today's Assigned PT Sessions</h4>
				<div class="text-muted small">Mark next pending session as completed</div>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-responsive-sm">
						<thead>
							<tr>
								<th>#</th>
								<th>Action</th>
								<th>Member</th>
								<th>PT Plan</th>
								<th>Start-End</th>
								<th>Sessions</th>
								<th>Remaining</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							if ($qToday) {
								while ($row = $mysqli->fetch_assoc($qToday)) {
									$pt_member_id = (int)$row['pt_member_id'];
									$remaining = (int)$row['total_sessions'] - (int)$row['sessions_used'];
									$status = $row['status'];
									$action_btn = ($remaining > 0 && $status !== 'Completed')
										? "<span style='cursor:pointer;' class='btn btn-success shadow btn-xs sharp me-1' onclick='mark_pt_session(\"{$pt_member_id}\")' title='Mark next session done'><i class='fa fa-check'></i></span>"
										: "<span class='badge light badge-success badge-sm'>Completed</span>";

									$status_badge = ($status === 'Completed')
										? '<span class="badge light badge-success badge-sm">Completed</span>'
										: '<span class="badge light badge-warning badge-sm">Active</span>';

									echo "<tr>";
									echo "<td><nobr>" . $i . "</nobr></td>";
									echo "<td><nobr>" . $action_btn . "</nobr></td>";
									echo "<td><nobr>" . htmlspecialchars($row['member_id']) . " - " . htmlspecialchars($row['member_name']) . "</nobr></td>";
									echo "<td><nobr>" . htmlspecialchars($row['plan_title'] ?? '') . "</nobr></td>";
									echo "<td><nobr>" . htmlspecialchars($row['start_date']) . " - " . htmlspecialchars($row['end_date']) . "</nobr></td>";
									echo "<td><nobr>" . (int)$row['sessions_used'] . "/" . (int)$row['total_sessions'] . "</nobr></td>";
									echo "<td><nobr>" . $remaining . "</nobr></td>";
									echo "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
									echo "</tr>";
									$i++;
								}
							}
							if ($i === 1) {
								echo "<tr><td colspan='8' class='text-center text-muted'>No active PT assignments for today.</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once("includes/footer.php"); ?>

<script>
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

