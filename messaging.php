<?php
$pagecode = "MSG-001";
include 'includes/check_session.php';
$pageno = 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once("includes/header.php"); ?>
	<title>Messaging | <?php echo APPLICATION_NAME; ?> </title>
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
				<li class="breadcrumb-item active"><a href="javascript:void(0);">Messaging</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="col-xl-12">
				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">WhatsApp Bulk Send</h4>
					</div>

					<div class="card-body">
						<form onsubmit="return false;" id="frm_whatsapp_bulk" method="post">
							<div class="row">
								<div class="col-xl-4 mb-3">
									<label class="form-label">Message Type</label>
									<select id="whatsapp_message_type" name="message_type" class="single-select form-control wide" required>
										<option value="custom">custom</option>
										<option value="offer">offer</option>
										<option value="birthday">birthday</option>
										<option value="expiry">expiry</option>
									</select>
								</div>
								<div class="col-xl-12 mb-3">
									<label class="form-label">Message</label>
									<textarea class="form-control" name="message_text" id="whatsapp_body" placeholder="Write message..." required></textarea>
								</div>
							</div>

							<input type="hidden" name="tab" value="<?php echo 'send_whatsapp_bulk'; ?>" />
							<input type="hidden" name="url" id="bulk_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required />

							<button type="submit" class="btn btn-primary">Send WhatsApp</button>
						</form>
					</div>
				</div>
			</div>

			<div class="col-xl-12 mt-3">
				<form onsubmit="return false;" id="frm_search" method="post">
					<input type='hidden' name='tab' value="<?php echo 'view_message_logs'; ?>" />
					<input type="hidden" name="url" id="url" value="<?php echo "index.php?".$mysqli->encode("stat=table_response"); ?>" required>
					<input type="hidden" name="record_limit" id="record_limit" value="10">
					<input type='hidden' name='download' id='download' value="" />
					<input type="hidden" name="page" id="page" value="<?php echo $pageno; ?>">
				</form>

				<div class="card dz-card">
					<div class="card-header flex-wrap">
						<h4 class="heading mb-0">Message History</h4>
					</div>
					<div id="dynamic_div" class="table-responsive">
						<div class="card-body"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	include_once("includes/footer.php");
	include_once("includes/dynamic_table.php");
	?>

	<script>
		$(document).on('submit', '#frm_whatsapp_bulk', function(e) {
			e.preventDefault();
			send_ajax_request('frm_whatsapp_bulk', '', 'C');
		});
	</script>
</body>

</html>

