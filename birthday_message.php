<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');
$start_time = date('Y-m-d H:i:s');

if(strtoupper(php_sapi_name()) == 'CLI')
{
	$root_path = '';
	if(!isset($_SERVER['DOCUMENT_ROOT']) || $_SERVER['DOCUMENT_ROOT'] == '')
	{
		$root_path = '/home/u583683241/public_html';
	}else
	{
		$root_path = $_SERVER['DOCUMENT_ROOT']."/";
		$ABSOLUTE_ROOT_PATH = $root_path;
	}
	$_SERVER['DOCUMENT_ROOT'] = $root_path;
	$_SERVER['HTTP_HOST'] = 'swimgymacademy.com';
	$_SERVER['REMOTE_ADDR'] = "CRON";

	$log = array();
	$response = array();
	$record_id = '';
	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();

	error_log("birthday report Started:".$start_time." ----- birthday report Ended:" .date('Y-m-d H:i:s')."" . PHP_EOL."\r\n", 3, 'birthdayReport.txt');

	$logid = $mysqli->Resquest_Response_log("", strtoupper('birthday_mail'), '', 'birthday messages', ''); 

	$sql = "SELECT * FROM ".MEMBERS." 
			WHERE dob IS NOT NULL
			AND DATE_FORMAT(dob, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')
			AND status = 'Active'
			AND (membership_type = 'Single' OR (membership_type = 'Family' AND family_head = '1'))";
	$result = $mysqli->executeQry($sql);

	while ($row = $mysqli->fetch_assoc($result)) {
		extract($row);

		$subject = 'Happy Birthday! from Evosapiens Movement | '.$member_id;
		$body = '<p>Hello '.$name.',</p>';
		$body .= '<p>Warm wishes on your birthday!</p>';
		$body .= '<p>May this year bring you health, strength, and great fitness.</p>';
		$body .= '<p>Best Regards,<br/>Evosapiens Movement</p>';

		$fromEmail = 'info@swimgymacademy.com';
		$fromName = "Evosapiens Movement";
		$toEmail = $email;
		$attachmentPath = '';
		$isMail = $mysqli->sendEmails($subject, $body, $attachmentPath, $fromEmail, $fromName, $toEmail,  $toName = '', $bcc = '');
		$emailStatus = ($isMail > 0) ? 'sent' : 'failed';

		$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET member_id = '".$id."', type='email', message_type='birthday', status='".$emailStatus."', message='".addslashes(strip_tags($subject))."', provider_response='".$emailStatus."', created_at=NOW()");

		$waMessage = "Hello ".$name.", Happy Birthday from Evosapiens Movement! Stay fit and strong with us.";
		$waOk = $mysqli->sendWhatsAppMessage($mobile, $waMessage);
		$waStatus = $waOk ? 'sent' : 'failed';
		$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET member_id = '".$id."', type='whatsapp', message_type='birthday', status='".$waStatus."', message='".addslashes($waMessage)."', provider_response='".($waOk ? 'ok' : 'not configured / failed')."', created_at=NOW()");

		$response['msg_code'] = "00";
		$response['msg'] = "Birthday messages processed.";
	}

	echo json_encode($response);
	$logid = $mysqli->Resquest_Response_log($logid, '', $response, '',$record_id,$log);  
}
else
{
	echo "You can not run this script through browser.";
}
?>

