<?php

date_default_timezone_set('Asia/Kolkata');
$start_time = date('Y-m-d H:i:s');
//file_put_contents("input.txt", json_encode($_SERVER));exit;
 //if(1)
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
	//file_put_contents("input.txt", json_encode($_SERVER));exit;
	$mysqli = new MySqliDriver();

	error_log("update expired report Started:".$start_time." ----- update expired report Ended:" .date('Y-m-d H:i:s')."" . PHP_EOL."\r\n", 3, 'updateReport.txt');

	$logid = $mysqli->Resquest_Response_log("", strtoupper('update_expired'), '', 'update expired memberships', ''); 
	$cur_date = date('Y-m-d');
	 $sql = "SELECT * FROM ".MEMBERS." where end_date < '".$cur_date."' and status = 'Active'";
	$result = $mysqli->executeQry($sql);
	while ($row = $mysqli->fetch_assoc($result)) {
		extract($row);
		$sql_new = "UPDATE " . MEMBERS . " SET status = 'Expired', payment_status = 'Pending' WHERE  id = '" . $id."'";
		$res1 = $mysqli->executeQry($sql_new);
		if($res1 > 0){
			$apiKey = "11";
			$employeeCode = $id;
			$employeeName = $name;
			$isBlock = "true"; // or "false"
			$serialNumber = "CUB7235301317";
			$userName = "sgar";
			$userPassword = "Sgar@2024";
			$commandId = 0;
			$block = $mysqli->blockUnblockUser($apiKey, $employeeCode, $employeeName, $serialNumber, $isBlock, $userName, $userPassword, $commandId);  
			 $response['msg_code'] = "00";
			 $response['msg'] = "updated.";
		 }else{
			 $response['msg_code'] = "01";
			 $response['msg'] = "not updated.";
		 }
	}

$logid = $mysqli->Resquest_Response_log($logid, '', $response, '',$record_id,$log);
		
}
else
{
	echo "You can not run this script through browser.";
} 

		
?>