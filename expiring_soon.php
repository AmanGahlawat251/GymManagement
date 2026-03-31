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

	error_log("expiring report Started:".$start_time." ----- expiring report Ended:" .date('Y-m-d H:i:s')."" . PHP_EOL."\r\n", 3, 'expiringReport.txt');

	$logid = $mysqli->Resquest_Response_log("", strtoupper('expiry_mail'), '', 'mail to expiring memberships', ''); 
	$cur_date = date('Y-m-d');

	$target_date = date('Y-m-d', strtotime('+3 days', strtotime($cur_date)));
	 $sql = "SELECT * FROM " . MEMBERS . " WHERE end_date = '" . $target_date . "'";
	$result = $mysqli->executeQry($sql);
	while ($row = $mysqli->fetch_assoc($result)) {
		extract($row);
		if($row['membership_type'] == 'Single' || $row['membership_type'] == 'Family' && $row['family_head'] == '1'){
			$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plan_id.'"');
			$subject = 'Welcome to Evosapiens Movement! Membership Expiring soon | '.$member_id.'';
				 
						$body = '<p>Hello ' . $name . ',</p>';

						$body .= '<p>We hope you have enjoyed being a member of Evosapiens Movement. We would like to inform you that your membership will expire in 3 days, on ' . $end_date . '.</p>';

						$body .= '<p>To continue your fitness journey with us, please renew your membership. You can visit our reception desk, and our staff will be happy to assist you in selecting a new membership plan that suits your needs.</p>';

						$body .= '<p>Here are the details of your current membership plan:</p>';

						$body .= '<p><b>Membership ID: ' . $member_id . '</b></p>';

						$body .= '<p><b>Membership Plan: ' . $plan_details['title'] . '</b></p>';

						$body .= '<p>Thank you for being a valued member of Evosapiens Movement. We look forward to welcoming you back soon!</p>';

						$body .= '<p>Best Regards,<br/>Evosapiens Movement</p>';

						$fromEmail = 'info@swimgymacademy.com';
						$fromName = "Evosapiens Movement";
						 $toEmail = $email;
						
						 $attachmentPath = '';
						 $isMail = $mysqli->sendEmails($subject, $body, $attachmentPath, $fromEmail, $fromName, $toEmail,  $toName = '', $bcc = '');
						 $emailStatus = ($isMail > 0) ? 'sent' : 'failed';
						 // Log Email
						 $mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET member_id = '".$id."', type='email', message_type='expiry_3d', status='".$emailStatus."', message='".addslashes(strip_tags($subject))."', provider_response='".$emailStatus."', created_at=NOW()");

						 // WhatsApp (if configured)
						 $waMessage = "Hello ".$name.", your membership expires in 3 days (".$end_date."). Renew your fitness plan at Evosapiens Movement.";
						 $waOk = $mysqli->sendWhatsAppMessage($mobile, $waMessage);
						 $waStatus = $waOk ? 'sent' : 'failed';
						 $mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET member_id = '".$id."', type='whatsapp', message_type='expiry_3d', status='".$waStatus."', message='".addslashes($waMessage)."', provider_response='".($waOk ? 'ok' : 'not configured / failed')."', created_at=NOW()");

						 if($emailStatus == 'sent'){
							 $response['msg_code'] = "00";
							 $response['msg'] = "mail sent (and WhatsApp attempted if enabled).";
						 }else{
							 $response['msg_code'] = "01";
							 $response['msg'] = "mail not sent (and WhatsApp attempted if enabled).";
						 }
		}
		
	}
echo json_encode($response);
$logid = $mysqli->Resquest_Response_log($logid, '', $response, '',$record_id,$log);  
}
else
{
	echo "You can not run this script through browser.";
}
?>