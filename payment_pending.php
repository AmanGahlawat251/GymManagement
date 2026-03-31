<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');

if(strtoupper(php_sapi_name()) == 'CLI')
{
	require_once('includes/constant.php');
	require_once('includes/autoload.php');
	$mysqli = new MySqliDriver();

	$sql = "SELECT * FROM " . MEMBERS . " WHERE status='Active' AND (payment_status IS NULL OR payment_status != 'Paid')";
	$result = $mysqli->executeQry($sql);

	$response = array();
	$response['processed'] = 0;

	while ($row = $mysqli->fetch_assoc($result)) {
		$response['processed']++;
		extract($row);

		$subject = "Payment Pending Reminder | Evosapiens Movement";
		$body = '<p>Hello ' . $name . ',</p><p>Your payment is pending. Please clear it at reception to continue your fitness journey.</p><p>Regards,<br/>Evosapiens Movement</p>';

		$emailOk = $mysqli->sendEmails($subject, $body, '', 'info@swimgymacademy.com', 'Evosapiens Movement', $email, $toName = '', $bcc = '');
		$emailStatus = ($emailOk > 0) ? 'sent' : 'failed';
		$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
			member_id = '" . (int)$id . "',
			type='email',
			message_type='payment_pending',
			status='".$emailStatus."',
			message='".addslashes(strip_tags($subject))."',
			provider_response='".$emailStatus."',
			created_at=NOW()");

		$waMessage = "Hello ".$name.", your gym payment is pending. Please clear it to continue your fitness journey at Evosapiens Movement.";
		$waOk = $mysqli->sendWhatsAppMessage($mobile, $waMessage);
		$waStatus = $waOk ? 'sent' : 'failed';
		$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
			member_id = '" . (int)$id . "',
			type='whatsapp',
			message_type='payment_pending',
			status='".$waStatus."',
			message='".addslashes($waMessage)."',
			provider_response='".($waOk ? 'ok' : 'not configured / failed')."',
			created_at=NOW()");
	}

	echo json_encode(array('msg_code'=>'00','msg'=>'Payment pending processed.', 'processed'=>$response['processed']));
}
else
{
	echo "You can not run this script through browser.";
}
?>

