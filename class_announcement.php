<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');

if(strtoupper(php_sapi_name()) == 'CLI')
{
	$root_path = '';
	if(!isset($_SERVER['DOCUMENT_ROOT']) || $_SERVER['DOCUMENT_ROOT'] == '')
	{
		$root_path = '/home/u583683241/public_html';
	}else{
		$root_path = $_SERVER['DOCUMENT_ROOT']."/";
		$ABSOLUTE_ROOT_PATH = $root_path;
	}
	$_SERVER['DOCUMENT_ROOT'] = $root_path;
	$_SERVER['HTTP_HOST'] = 'swimgymacademy.com';
	$_SERVER['REMOTE_ADDR'] = "CRON";

	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();

	$cur_date = date('Y-m-d');
	$start_time_limit = date('H:i:s');

	$sql_s = "SELECT s.id AS schedule_id, s.schedule_date, s.start_time, s.end_time, c.title AS class_title
				FROM " . CLASS_SCHEDULE . " s
				JOIN " . CLASSES . " c ON c.id = s.class_id
				WHERE s.status='Active' AND s.schedule_date = '" . $cur_date . "'";
	$result_s = $mysqli->executeQry($sql_s);

	while ($srow = $mysqli->fetch_assoc($result_s)) {
		extract($srow);
		$waMessage = "Reminder: New class '$class_title' today at $start_time (Evosapiens Movement). See you there!";
		$subject = "New Class Reminder: $class_title | Evosapiens Movement";
		$emailBody = '<p>Hello,</p><p>Reminder: New class <b>' . $class_title . '</b> today at <b>' . $start_time . '</b>.</p><p>Regards,<br/>Evosapiens Movement</p>';

		$sql_m = "SELECT DISTINCT m.id, m.mobile, m.email, m.name
					FROM " . CLASS_MEMBERS . " cm
					JOIN " . MEMBERS . " m ON m.id = cm.member_id
					WHERE cm.class_schedule_id = '" . $schedule_id . "' AND cm.status='Enrolled' AND m.status='Active'";
		$res_m = $mysqli->executeQry($sql_m);
		while ($mrow = $mysqli->fetch_assoc($res_m)) {
			$emailOk = $mysqli->sendEmails($subject, $emailBody, '', 'info@swimgymacademy.com', 'Evosapiens Movement', $mrow['email'], $toName = '', $bcc = '');
			$emailStatus = ($emailOk > 0) ? 'sent' : 'failed';
			$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
				member_id = '" . (int)$mrow['id'] . "',
				type='email',
				message_type='class_announcement',
				status='".$emailStatus."',
				message='".addslashes($subject)."',
				provider_response='".$emailStatus."',
				created_at=NOW()");

			$waOk = $mysqli->sendWhatsAppMessage($mrow['mobile'], $waMessage);
			$waStatus = $waOk ? 'sent' : 'failed';
			$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
				member_id = '" . (int)$mrow['id'] . "',
				type='whatsapp',
				message_type='class_announcement',
				status='".$waStatus."',
				message='".addslashes($waMessage)."',
				provider_response='".($waOk ? 'ok' : 'not configured / failed')."',
				created_at=NOW()");
		}
	}

	echo json_encode(array('msg_code'=>'00','msg'=>'Class announcements processed.'));
}
else
{
	echo "You can not run this script through browser.";
}
?>

