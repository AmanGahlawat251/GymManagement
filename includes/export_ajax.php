<?php 
//code for log start
if (!isset($tab)) {
  $tab = $_POST['tab'];
}
$log = array();
$record_id = '';
$logid = $mysqli->Resquest_Response_log("", strtoupper($tab), '', json_encode($_POST), '');
//take log fields name(Query) and record id in every function 

//code for log end
extract($_POST);
if (!isset($_SESSION)) {
  session_start();
}

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
  echo "<script language='javascript' type='text/javascript'>";
  echo "alert('Request not identified as ajax request');";
  echo "</script>";
  $URL = "index.php";
  echo "<script>location.href='$URL'</script>";
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  echo "<script language='javascript' type='text/javascript'>";
  echo "alert('Bad Request method');";
  echo "</script>";
  $URL = "index.php";
  echo "<script>location.href='$URL'</script>";
}


$response = array();

// Role-based protection for CSV exports.
$role = $_SESSION['user_type'] ?? '';
$role = ($role === 'ADMIN') ? 'SUPERADMIN' : $role;
if ($role === 'TRAINER') {
	// Trainers can export only their assigned PT sessions.
	if ($tab !== 'export_pt_members') {
		$response['msg_code'] = "05";
		$response['msg'] = "Access denied.";
		echo json_encode($response);
		exit;
	}
	$trainer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
} elseif ($role === 'RECEPTIONIST') {
	// Receptionists can export only members + attendance (basic reports).
	$allowedExports = ['export_member', 'export_attendance', 'export_payment_history'];
	if (!in_array($tab, $allowedExports, true)) {
		$response['msg_code'] = "05";
		$response['msg'] = "Access denied.";
		echo json_encode($response);
		exit;
	}
}

  
if ($tab == 'export_member') {
  $data = array();
  $con = '';
 if (!empty($membership_id) || $membership_id != "") {
		$con .= " and member_id = '" . trim($membership_id) . "'";
	}
	if (!empty($search_user_name) || $search_user_name != "") {
		$con .= " and name like '%" . trim($search_user_name) . "%'";
	}

	if (!empty($search_user_email) || $search_user_email != "") {
		$con .= " and email = '" . trim($search_user_email) . "'";
	}

	if (!empty($search_user_contact) || $search_user_contact != "") {
		$con .= " and mobile = '" . trim($search_user_contact) . "'";
	}
	if (!empty($search_status) || $search_status != "") {
		$con .= " and status = '" . trim($search_status) . "'";
	}
	if (!empty($plan_type) || $plan_type != "") {
		$con .= " and plan_id IN (" . $plan_type . ")";
	}
 $sql = "select * from ".MEMBERS." where 1 ".$con." order by id ASC";
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".MEMBERS." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];

  $header = 0;
  $i = 1;
  $files = glob(ABSOLUTE_ROOT_PATH . '/export/*'); // get all file names
  foreach ($files as $file) { // iterate files
    if (is_file($file)) {
      @unlink($file); // delete file
    }
  }
    $file_title = 'export/member_export_' . time() . '.csv';
    $file_path = ABSOLUTE_ROOT_PATH . "/" . $file_title;
   $fp = fopen($file_path, 'w+');
  while ($arr = $mysqli->fetch_array($result)) {
	extract($arr);
	$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plan_id.'"');
      $data_pass = array(
            'Id' => $id,
            'Member_id' => $member_id,
            'Name' => $name,
            'Plan' => $plan_details['title'],
            'Timing' => $timing,
            'Start_date' => $start_date,
            'End_date' => $end_date,
            'Status' => $status,
        );

        if ($header == 0) {
            $head = array_keys((array) $data_pass);
            fputcsv($fp, $head);
            $header = 1;
        }

        $val123 = array_values((array) $data_pass);
        fputcsv($fp, $val123);
	$i++;
}
		  fclose($fp);
			// Set headers to download the file
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
			header('Pragma: no-cache');
			
		  $response['msg_code'] = '00';
		  $response['msg'] = 'members export successfull';
		  $response['redirect'] = 'export/' . basename($file_title);
		  echo json_encode($response);
		  exit;
}
if ($tab == 'export_attendance') {
  $data = array();
  $con = '';
 if (!empty($membership_id) || $membership_id != "") {
		$con .= " and member_id = '" . trim($membership_id) . "'";
	}
	if (!empty($att_date) || $att_date != "") {
		$con .= " and attendance_date = '" . trim($att_date) . "'";
	}else{
		$att_date = date('Y-m-d');
	$con .="and attendance_date = '".$att_date."'";
	}
  $sql = "select * from ".ATTENDANCE." where 1 ".$con."  ORDER BY attendance_date DESC, sign_in DESC";
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".ATTENDANCE." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];

  $header = 0;
  $i = 1;
  $files = glob(ABSOLUTE_ROOT_PATH . '/export/*'); // get all file names
  foreach ($files as $file) { // iterate files
    if (is_file($file)) {
      @unlink($file); // delete file
    }
  }
    $file_title = 'export/attendance_export' . time() . '.csv';
    $file_path = ABSOLUTE_ROOT_PATH . "/" . $file_title;
   $fp = fopen($file_path, 'w+');
  while ($arr = $mysqli->fetch_array($result)) {
	extract($arr);
	$member_details = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "'.$user_id.'" and member_id = "'.$member_id.'"');
	if($sign_out == '00:00:00'){
						$sign_out = 'Not Punched Out';
					}else{
						$sign_out = $mysqli->formatdate($sign_out,"h:i:s A");
					}
      $data_pass = array(
            'Member_id' => $member_details['member_id'],
            'Name' => $member_details['name'],
            'Attendance_Date' => $attendance_date,
            'Sign_In' => $mysqli->formatdate($sign_in,"h:i:s A"),
            'Sign_Out' => $sign_out,
        );

        if ($header == 0) {
            $head = array_keys((array) $data_pass);
            fputcsv($fp, $head);
            $header = 1;
        }

        $val123 = array_values((array) $data_pass);
        fputcsv($fp, $val123);
	$i++;
}
		  fclose($fp);
			// Set headers to download the file
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
			header('Pragma: no-cache');
			
		  $response['msg_code'] = '00';
		  $response['msg'] = 'attendance export successfull';
		  $response['redirect'] = 'export/' . basename($file_title);
		  echo json_encode($response);
		  exit;
}

if ($tab == 'export_payment_history') {
	$member_row_id = isset($member_row_id) ? (int)$member_row_id : 0;

	if ($member_row_id < 1) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid member.";
		echo json_encode($response);
		exit;
	}

	$memberRow = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "' . $member_row_id . '"');
	if (!$memberRow || empty($memberRow['member_id'])) {
		$response['msg_code'] = "05";
		$response['msg'] = "Member not found.";
		echo json_encode($response);
		exit;
	}

	$membership_id = $memberRow['member_id'];

	// Build CSV
	$files = glob(ABSOLUTE_ROOT_PATH . '/export/*'); // get all file names
	foreach ($files as $file) {
		if (is_file($file)) @unlink($file); // delete file
	}

	$file_title = 'export/payment_history_export_' . time() . '.csv';
	$file_path = ABSOLUTE_ROOT_PATH . "/" . $file_title;
	$fp = fopen($file_path, 'w+');

	$head = ['Date', 'Payment_Type', 'Paid', 'Status', 'Pending'];
	fputcsv($fp, $head);

	$sql = "SELECT received_on, payment_type, amount_received, payment_status, pending_amount
			FROM " . REVENUE . "
			WHERE member_id = '" . addslashes($membership_id) . "'
			AND payment_type IN ('MEMBERSHIP','PT')
			ORDER BY received_on DESC";
	$result = $mysqli->executeQry($sql);

	while ($arr = $mysqli->fetch_assoc($result)) {
		fputcsv($fp, [
			$arr['received_on'],
			$arr['payment_type'],
			$arr['amount_received'],
			$arr['payment_status'],
			$arr['pending_amount']
		]);
	}

	fclose($fp);

	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="' . basename($file_title) . '"');
	header('Pragma: no-cache');

	$response['msg_code'] = '00';
	$response['msg'] = 'payment history export successful';
	$response['redirect'] = 'export/' . basename($file_title);
	echo json_encode($response);
	exit;
}

if ($tab == 'export_pt_members') {
  $con = '';
  if (!empty($trainer_id) || $trainer_id != "") {
    $con .= " AND pm.trainer_id = '" . trim($trainer_id) . "'";
  }
  if (!empty($pt_status) || $pt_status != "") {
    $con .= " AND pm.status = '" . trim($pt_status) . "'";
  }
  if (!empty($from_date) || $from_date != "") {
    $con .= " AND pm.start_date >= '" . trim($from_date) . "'";
  }
  if (!empty($to_date) || $to_date != "") {
    $con .= " AND pm.start_date <= '" . trim($to_date) . "'";
  }

  $sql = "SELECT pm.id, pm.member_id, m.name AS member_name, pm.trainer_id, e.name AS trainer_name,
			 pm.pt_plan_id, p.title AS plan_title, pm.start_date, pm.total_sessions, pm.sessions_used, pm.end_date, pm.status
			FROM " . PT_MEMBERS . " pm
			JOIN " . PT_PLANS . " p ON p.id = pm.pt_plan_id
			JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
			JOIN " . MEMBERS . " m ON m.member_id = pm.member_id
			WHERE 1 " . $con . "
			ORDER BY pm.id DESC";

  $result = $mysqli->executeQry($sql);

  $sql123 = "SELECT COUNT(pm.id) AS count_rows
			FROM " . PT_MEMBERS . " pm
			JOIN " . PT_PLANS . " p ON p.id = pm.pt_plan_id
			JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
			JOIN " . MEMBERS . " m ON m.member_id = pm.member_id
			WHERE 1 " . $con;

  $result123 = $mysqli->executeQry($sql123);
  $num_arr = $mysqli->fetch_array($result123);
  $num = (int)$num_arr['count_rows'];

  $header = 0;

  $files = glob(ABSOLUTE_ROOT_PATH . '/export/*');
  foreach ($files as $file) {
    if (is_file($file)) @unlink($file);
  }

  $file_title = 'export/pt_members_export_' . time() . '.csv';
  $file_path = ABSOLUTE_ROOT_PATH . "/" . $file_title;
  $fp = fopen($file_path, 'w+');

  while ($arr = $mysqli->fetch_array($result)) {
    $id = $arr['id'];
    $member_id = $arr['member_id'];
    $member_name = $arr['member_name'];
    $trainer_name = $arr['trainer_name'];
    $plan_title = $arr['plan_title'];
    $start_date = $arr['start_date'];
    $total_sessions = (int)$arr['total_sessions'];
    $sessions_used = (int)$arr['sessions_used'];
    $remaining = $total_sessions - $sessions_used;
    if ($remaining < 0) $remaining = 0;
    $status = $arr['status'];

    $data_pass = array(
      'Id' => $id,
      'Member_id' => $member_id,
      'Member' => $member_name,
      'Trainer' => $trainer_name,
      'PT_Plan' => $plan_title,
      'Start_Date' => $start_date,
      'Sessions_Used' => $sessions_used,
      'Remaining' => $remaining,
      'Status' => $status
    );

    if ($header == 0) {
      $head = array_keys((array)$data_pass);
      fputcsv($fp, $head);
      $header = 1;
    }

    $val123 = array_values((array)$data_pass);
    fputcsv($fp, $val123);
  }

  fclose($fp);

  header('Content-Type: application/csv');
  header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
  header('Pragma: no-cache');

  $response['msg_code'] = '00';
  $response['msg'] = 'PT sessions export successfull';
  $response['redirect'] = 'export/' . basename($file_title);
  echo json_encode($response);
  exit;
}

if ($tab == 'export_class_members') {
  $con = '';
  if (!empty($trainer_id) || $trainer_id != "") {
    $con .= " AND e.id = '" . trim($trainer_id) . "'";
  }
  if (!empty($class_status) || $class_status != "") {
    $con .= " AND cm.status = '" . trim($class_status) . "'";
  }
  if (!empty($from_date) || $from_date != "") {
    $con .= " AND s.schedule_date >= '" . trim($from_date) . "'";
  }
  if (!empty($to_date) || $to_date != "") {
    $con .= " AND s.schedule_date <= '" . trim($to_date) . "'";
  }

  $sql = "SELECT cm.id, m.member_id, m.name AS member_name, e.name AS trainer_name,
				c.title AS class_title, s.schedule_date, s.start_time, s.end_time, cm.status
			FROM " . CLASS_MEMBERS . " cm
			JOIN " . CLASS_SCHEDULE . " s ON s.id = cm.class_schedule_id
			JOIN " . MEMBERS . " m ON m.id = cm.member_id
			JOIN " . CLASSES . " c ON c.id = s.class_id
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con . "
			ORDER BY cm.id DESC";

  $result = $mysqli->executeQry($sql);

  $sql123 = "SELECT COUNT(cm.id) AS count_rows
			FROM " . CLASS_MEMBERS . " cm
			JOIN " . CLASS_SCHEDULE . " s ON s.id = cm.class_schedule_id
			JOIN " . MEMBERS . " m ON m.id = cm.member_id
			JOIN " . CLASSES . " c ON c.id = s.class_id
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con;

  $result123 = $mysqli->executeQry($sql123);
  $num_arr = $mysqli->fetch_array($result123);
  $num = (int)$num_arr['count_rows'];

  $header = 0;

  $files = glob(ABSOLUTE_ROOT_PATH . '/export/*');
  foreach ($files as $file) {
    if (is_file($file)) @unlink($file);
  }

  $file_title = 'export/class_enrollments_export_' . time() . '.csv';
  $file_path = ABSOLUTE_ROOT_PATH . "/" . $file_title;
  $fp = fopen($file_path, 'w+');

  while ($arr = $mysqli->fetch_array($result)) {
    $id = $arr['id'];
    $member_id = $arr['member_id'];
    $member_name = $arr['member_name'];
    $trainer_name = $arr['trainer_name'];
    $class_title = $arr['class_title'];
    $schedule_date = $arr['schedule_date'];
    $start_time = $arr['start_time'];
    $end_time = $arr['end_time'];
    $status = $arr['status'];

    $data_pass = array(
      'Enrollment_Id' => $id,
      'Member_id' => $member_id,
      'Member' => $member_name,
      'Trainer' => $trainer_name,
      'Class' => $class_title,
      'Schedule_Date' => $schedule_date,
      'Start_Time' => $start_time,
      'End_Time' => $end_time,
      'Status' => $status
    );

    if ($header == 0) {
      $head = array_keys((array)$data_pass);
      fputcsv($fp, $head);
      $header = 1;
    }

    $val123 = array_values((array)$data_pass);
    fputcsv($fp, $val123);
  }

  fclose($fp);

  header('Content-Type: application/csv');
  header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
  header('Pragma: no-cache');

  $response['msg_code'] = '00';
  $response['msg'] = 'Class enrollments export successfull';
  $response['redirect'] = 'export/' . basename($file_title);
  echo json_encode($response);
  exit;
}

?>