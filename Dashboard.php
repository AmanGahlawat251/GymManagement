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
	<title>Dashboard | <?php echo APPLICATION_NAME; ?> </title>
	<!--**********************************  Sidebar Start  ***********************************-->
	<?php require_once("includes/sidebar.php"); ?>
	<!--**********************************  Sidebar End  ***********************************-->
	<!--**********************************  Content body start  ***********************************-->
	
	<div class="content-body">
		<!-- row -->
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
				<li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
			</ol>
		</div>


		<div class="container-fluid">
			<?php
				$today = date('Y-m-d');
				$monthStart = date('Y-m-01');
				$monthEnd = date('Y-m-t');

				// Widgets
				$r = $mysqli->executeQry("SELECT COUNT(id) AS c FROM ".MEMBERS);
				$totalMembers = (int)($r ? $mysqli->fetch_array($r)['c'] : 0);

				$r = $mysqli->executeQry("SELECT COUNT(id) AS c FROM ".MEMBERS." WHERE status='Active'");
				$activeMemberships = (int)($r ? $mysqli->fetch_array($r)['c'] : 0);

				$r = $mysqli->executeQry("SELECT COUNT(id) AS c FROM ".ATTENDANCE." WHERE attendance_date='".$today."'");
				$todayCheckins = (int)($r ? $mysqli->fetch_array($r)['c'] : 0);

				$r = $mysqli->executeQry("SELECT COALESCE(SUM(amount_received),0) AS s FROM ".REVENUE." WHERE payment_type IN ('MEMBERSHIP','PT') AND DATE(received_on) BETWEEN '".$monthStart."' AND '".$monthEnd."'");
				$monthlyRevenue = (float)($r ? $mysqli->fetch_array($r)['s'] : 0);

				$r = $mysqli->executeQry("SELECT COUNT(ps.id) AS c
					FROM ".PT_SESSIONS." ps
					WHERE DATE(ps.used_on) = '".$today."' AND ps.status='Used'");
				$ptSessionsToday = (int)($r ? $mysqli->fetch_array($r)['c'] : 0);

				$r = $mysqli->executeQry("SELECT COUNT(id) AS c FROM ".EMPLOYEES." WHERE designation='trainer' AND status='Active'");
				$activeTrainers = (int)($r ? $mysqli->fetch_array($r)['c'] : 0);

				// Charts: monthly revenue (last 12 months)
				$revLabels = array();
				$revData = array();
				for ($i = 11; $i >= 0; $i--) {
					$ym = date('Y-m', strtotime("-".$i." months"));
					$revLabels[] = date('M Y', strtotime($ym.'-01'));
					$revData[$ym] = 0;
				}
				$startYm = array_key_first($revData);
				$endYm = array_key_last($revData);
				$revQ = $mysqli->executeQry("SELECT DATE_FORMAT(received_on,'%Y-%m') AS ym, COALESCE(SUM(amount_received),0) AS s
					FROM ".REVENUE."
					WHERE payment_type IN ('MEMBERSHIP','PT') AND DATE_FORMAT(received_on,'%Y-%m') BETWEEN '".$startYm."' AND '".$endYm."'
					GROUP BY ym ORDER BY ym ASC");
				if ($revQ) {
					while ($row = $mysqli->fetch_assoc($revQ)) {
						$revData[$row['ym']] = (float)$row['s'];
					}
				}
				$revSeries = array_values($revData);

				// Charts: daily checkins (last 7 days)
				$chkLabels = array();
				$chkDataMap = array();
				for ($d = 6; $d >= 0; $d--) {
					$dt = date('Y-m-d', strtotime("-".$d." days"));
					$chkLabels[] = date('D', strtotime($dt));
					$chkDataMap[$dt] = 0;
				}
				$startD = array_key_first($chkDataMap);
				$endD = array_key_last($chkDataMap);
				$chkQ = $mysqli->executeQry("SELECT attendance_date, COUNT(id) AS c
					FROM ".ATTENDANCE."
					WHERE attendance_date BETWEEN '".$startD."' AND '".$endD."'
					GROUP BY attendance_date");
				if ($chkQ) {
					while ($row = $mysqli->fetch_assoc($chkQ)) {
						$chkDataMap[$row['attendance_date']] = (int)$row['c'];
					}
				}
				$chkSeries = array_values($chkDataMap);

				// Charts: membership type distribution (active members)
				$typeLabels = array();
				$typeSeries = array();
				$typeQ = $mysqli->executeQry("SELECT COALESCE(mt.name,'Unassigned') AS name, COUNT(m.id) AS c
					FROM ".MEMBERS." m
					LEFT JOIN ".MEMBERSHIP_TYPES." mt ON mt.id = m.membership_type_id
					WHERE m.status='Active'
					GROUP BY COALESCE(mt.name,'Unassigned')
					ORDER BY c DESC");
				if ($typeQ) {
					while ($row = $mysqli->fetch_assoc($typeQ)) {
						$typeLabels[] = $row['name'];
						$typeSeries[] = (int)$row['c'];
					}
				}
			?>

			<style>
				.evos-kpi {
					background: #fff;
					border: 1px solid rgba(15, 23, 42, .08);
					border-radius: 16px;
					color: #0f172a;
					overflow: hidden;
					box-shadow: 0 1px 2px rgba(0,0,0,.04);
				}
				.evos-kpi .card-body { padding: 18px 18px; }
				.evos-kpi .kpi-icon {
					width: 46px;
					height: 46px;
					display:flex;
					align-items:center;
					justify-content:center;
					border-radius: 14px;
					background: #f1f5f9;
				}
				/* Flat accents (no gradients) */
				.evos-grad-1 .kpi-icon { background: #e6f0ff; }
				.evos-grad-1 .kpi-icon i { color: #2563eb; }
				.evos-grad-2 .kpi-icon { background: #dcfce7; }
				.evos-grad-2 .kpi-icon i { color: #16a34a; }
				.evos-grad-3 .kpi-icon { background: #fff7ed; }
				.evos-grad-3 .kpi-icon i { color: #ea580c; }
				.evos-grad-4 .kpi-icon { background: #ffe4e6; }
				.evos-grad-4 .kpi-icon i { color: #e11d48; }
				.evos-grad-5 .kpi-icon { background: #e0f2fe; }
				.evos-grad-5 .kpi-icon i { color: #0284c7; }
				.evos-grad-6 .kpi-icon { background: #ede9fe; }
				.evos-grad-6 .kpi-icon i { color: #7c3aed; }
				.chart-card { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.08); }
			</style>

			<div class="row g-3">
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-1">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">Total Members</div>
								<div class="fs-3 fw-bold"><?php echo $totalMembers; ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-users"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-2">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">Active Memberships</div>
								<div class="fs-3 fw-bold"><?php echo $activeMemberships; ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-check-circle"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-5">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">Today Check-ins</div>
								<div class="fs-3 fw-bold"><?php echo $todayCheckins; ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-sign-in"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-4">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">Revenue (Monthly)</div>
								<div class="fs-3 fw-bold">₹ <?php echo number_format($monthlyRevenue, 0); ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-inr"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-6">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">PT Sessions Today</div>
								<div class="fs-3 fw-bold"><?php echo $ptSessionsToday; ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-bolt"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-lg-4 col-xl-2">
					<div class="card evos-kpi evos-grad-3">
						<div class="card-body d-flex align-items-center justify-content-between">
							<div>
								<div class="fw-semibold">Active Trainers</div>
								<div class="fs-3 fw-bold"><?php echo $activeTrainers; ?></div>
							</div>
							<div class="kpi-icon"><i class="fa fa-user"></i></div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2 g-3">
				<div class="col-12 col-xl-6">
					<div class="card chart-card">
						<div class="card-header"><h4 class="heading mb-0">Monthly Revenue (Last 12 Months)</h4></div>
						<div class="card-body">
							<canvas id="chartRevenue" height="120"></canvas>
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-6">
					<div class="card chart-card">
						<div class="card-header"><h4 class="heading mb-0">Daily Check-ins (Last 7 Days)</h4></div>
						<div class="card-body">
							<canvas id="chartCheckins" height="120"></canvas>
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-4">
					<div class="card chart-card">
						<div class="card-header"><h4 class="heading mb-0">Membership Types (Active)</h4></div>
						<div class="card-body">
							<canvas id="chartTypes" height="160"></canvas>
						</div>
					</div>
				</div>
			</div>
				<div class="col-xl-12">


				<div class="filter cm-content-box box-primary">
					<div class="content-title">
						<div class="cpa">
							<i class="fa-sharp fa-solid fa-filter me-2"></i>Filter
						</div>
						<div class="tools">
							<a href="javascript:void(0);" class="SlideToolHeader"><i class="fal fa-angle-down"></i></a>
						</div>
					</div>
					<div style="display:none;" class="cm-content-body form excerpt">
						<div class="card-body">
							<form onsubmit="return false;" id="frm_search" method="post">
								
								<div class="row">



									<div class="col-md-2">
										<label class="form-label">Search by membership ID</label>
										<input type="text" class="form-control  " maxlength="150" id="membership_id" name="membership_id" placeholder="" />
									</div>
									<div class="col-md-2">
										<label class="form-label">Search by date</label>
										<input type="date" name="att_date" id="att_date" class="form-control" required  value="<?php echo date('Y-m-d');?>" required />
									</div>

									<div class="col-md-2">
											<button style="margin-top: 30px;" id="search" type="submit" class="btn btn-sm btn-primary">Search</button>
											<button style="margin-top: 30px; margin-left:5px;" type="reset" onclick="window.location.reload();" class="btn btn-sm  btn-primary">Reset</button>
										
									</div>

								</div>
								<input type='hidden' name='tab' value="<?php echo 'view_recent_visitors'; ?>" />					
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>			  
					<input type="hidden" name="record_limit" id="record_limit" value="10"> 			
					<input type='hidden' name='download' id='download' value="" />		 
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">  
							</form>
						</div>
					</div>

				</div>
			</div>
				
				<div class="col-xl-12">
				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Recent Visitors</h4>
					<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a href="javascript:void(0);" onclick="export_attendance()" class="btn btn-success text-white btn-sm" style="margin-right:10px;"><i class="fa fa-download"></i></a>
							</li>
						</ul>
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

		<script>
			// Render charts once DOM is ready.
			// Keep this robust: if Chart.js doesn't load (or another script fails), show a clear message.
			document.addEventListener('DOMContentLoaded', function () {
				function replaceCanvasWithMessage(canvasId, msg) {
					var canvas = document.getElementById(canvasId);
					if (!canvas) return;
					var parent = canvas.parentNode;
					if (!parent) return;
					parent.innerHTML = '<div class="text-muted small">' + msg + '</div>';
				}

				if (typeof Chart === 'undefined') {
					replaceCanvasWithMessage('chartRevenue', 'Charts not available (Chart.js failed to load).');
					replaceCanvasWithMessage('chartCheckins', 'Charts not available (Chart.js failed to load).');
					replaceCanvasWithMessage('chartTypes', 'Charts not available (Chart.js failed to load).');
					return;
				}

				Chart.defaults.font.family = 'Poppins, system-ui, -apple-system, Segoe UI, Roboto, Arial';
				Chart.defaults.color = '#111827';

				// Monthly Revenue
				var revLabels = <?php echo json_encode($revLabels); ?>;
				var revData = <?php echo json_encode($revSeries); ?>;
				var hasRevData = Array.isArray(revData) && revData.some(function (v) { return Number(v) > 0; });
				if (!hasRevData) {
					replaceCanvasWithMessage('chartRevenue', 'No data available');
				} else {
					new Chart(document.getElementById('chartRevenue'), {
						type: 'line',
						data: {
							labels: revLabels,
							datasets: [{
								label: 'Revenue',
								data: revData,
								borderColor: '#0d99ff',
								backgroundColor: 'rgba(13,153,255,.15)',
								fill: true,
								tension: 0.35,
								pointRadius: 2
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: { legend: { display: false } },
							scales: { y: { beginAtZero: true } }
						}
					});
				}

				// Daily Check-ins
				var chkLabels = <?php echo json_encode($chkLabels); ?>;
				var chkData = <?php echo json_encode($chkSeries); ?>;
				var hasChkData = Array.isArray(chkData) && chkData.some(function (v) { return Number(v) > 0; });
				if (!hasChkData) {
					replaceCanvasWithMessage('chartCheckins', 'No data available');
				} else {
					new Chart(document.getElementById('chartCheckins'), {
						type: 'bar',
						data: {
							labels: chkLabels,
							datasets: [{
								label: 'Check-ins',
								data: chkData,
								backgroundColor: 'rgba(16,185,129,.85)',
								borderRadius: 10
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							plugins: { legend: { display: false } },
							scales: { y: { beginAtZero: true } }
						}
					});
				}

				// Membership Types
				var typeLabels = <?php echo json_encode($typeLabels); ?>;
				var typeData = <?php echo json_encode($typeSeries); ?>;
				var hasTypeData = Array.isArray(typeData) && typeData.some(function (v) { return Number(v) > 0; });
				if (!hasTypeData) {
					replaceCanvasWithMessage('chartTypes', 'No data available');
				} else {
					new Chart(document.getElementById('chartTypes'), {
						type: 'pie',
						data: {
							labels: typeLabels,
							datasets: [{
								data: typeData,
								backgroundColor: ['#0d99ff','#10b981','#f59e0b','#ef4444','#a855f7','#06b6d4','#111827']
							}]
						},
						options: { responsive: true, maintainAspectRatio: false }
					});
				}
			});
		</script>

	</div>
	<!--**********************************  Content body end  ***********************************-->

	<!--**********************************  Footer Start  ***********************************-->
	<?php include_once("includes/footer.php");
include_once("includes/dynamic_table.php") ;	?>
	<!--**********************************  Footer End  ***********************************-->
	 <script src="./js/cms.js"></script>
	<script>
	function export_attendance() {
        //console.log('hi');
        $('#preloader').show();
        var membership_id = $('#membership_id').val();
        var att_date = $('#att_date').val();
        var data = {};
        data.tab = 'export_attendance';
        data.membership_id = membership_id;
        data.att_date = att_date;
        $.ajax({
            type: "POST",
            url:"index.php?<?php echo $mysqli->encode('stat=export_ajax');?>",
            data: data,
            dataType: "json",
			timeout: 0,
            beforeSend: function () {
				$("#preloader").show();
			},
            success: function(data) {
				//console.log(data);return false;
				if (data.msg_code != '00') {
					toastr.error(data.msg);
				}else{
					 var link = document.createElement('a');
                link.href = data.redirect;
                link.download = data.redirect.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
				}
                $('#preloader').hide();
            },
			error: function ( jqXHR, exception ){
                $('#preloader').hide();
			} 
        });
		// }
    }
	</script>
	</body>

</html>