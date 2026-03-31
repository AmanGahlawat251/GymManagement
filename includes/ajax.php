<?php
if (!isset($tab)) {
	$tab = $_POST['tab'];
}
$log = array();
//echo $tab;
//print_r($_POST); exit;
$record_id = '';
$logid = $mysqli->Resquest_Response_log("", strtoupper($tab), '', json_encode($_POST), '');
extract($_POST);
if (!isset($_SESSION)) {
	session_start();
}

$mysqli->autocommit(TRUE);

if ($tab != 'login' && $tab != 'sign_up' &&  $tab != 'forgot_password' &&  $tab != 'verify_payment' &&  $tab != 'verify_email' && $tab != 'view_access_ip' &&  $tab != 'verify_contact') {
	require_once('check_session.php');
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


// Basic role-based API protection for AJAX endpoints.
// - TRAINER: can only mark their PT sessions completed.
// - RECEPTIONIST: blocked from admin-only modules (employees/PT/class/class schedule/membership types).
$role = $_SESSION['user_type'] ?? '';
$role = ($role === 'ADMIN') ? 'SUPERADMIN' : $role;
if ($role === 'TRAINER') {
	if ($tab !== 'mark_pt_session') {
		$response['msg_code'] = "05";
		$response['msg'] = "Access denied.";
		echo json_encode($response);
		exit;
	}
} elseif ($role === 'RECEPTIONIST') {
	$blockedTabs = [
		// Employees
		'add_employee', 'delete_employee',
		// PT admin (plans + assignments)
		'add_pt_plan', 'delete_pt_plan', 'add_pt_member',
		// PT session marking (trainer-only)
		'mark_pt_session',
		// Classes admin
		'add_class', 'delete_class', 'add_class_schedule', 'delete_class_schedule',
		'enroll_class_member', 'remove_class_member',
		// Membership types admin
		'add_membership_type', 'toggle_membership_type', 'delete_membership_type'
	];
	if (in_array($tab, $blockedTabs, true)) {
		$response['msg_code'] = "05";
		$response['msg'] = "Access denied.";
		echo json_encode($response);
		exit;
	}
}

if ($tab == 'login') {
	$RecTimeStamp = $mysqli->RecTimeStamp("Y-m-d H:i:s");
	
		$obj_login = new login();
			$response = $obj_login->userLogin($user_email, $password, '');
	
		$record_id = $user_email;

}else if ($tab == 'new_family_id') {
			$RecTimeStamp = $mysqli->RecTimeStamp("Y-m-d H:i:s");
			$family_id = $mysqli->generate_family_id();
			$family_id = $familyTitle.'-'.$family_id;
			$sql = "INSERT INTO " . FAMILY_ID . " SET family_id = '" . $family_id . "', created_on = '" . $RecTimeStamp . "'";
			$log['sql'] = $sql;
			$res = $mysqli->executeQry($sql);
			$last_id = $mysqli->insert_id();
			if ($res > 0) {
				$response['msg_code'] = "00";
				$response['msg'] = "Family ID Generated.";
			
			} else {
				$response['msg'] = "05";
				$response['msg'] = "Unable to generate family id at this time.";
			}
}else if($tab == "get_base_price"){

	 $sql= "SELECT * from ".PLANS." WHERE id = '".$id."'";
	$result = $mysqli->executeQry($sql);
	$row = $mysqli->fetch_assoc($result);
	 extract($row); 

		$response['msg'] = "00";
		$response['price'] = $price;
		$response['duration'] = $duration;
	
}else if($tab == "get_pt_plan_price"){
	$pt_plan_id = isset($pt_plan_id) ? (int)$pt_plan_id : 0;
	$pd = $mysqli->singleRowAssoc_new('*', PT_PLANS, 'id = "'.$pt_plan_id.'"');
	if(!$pd){
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid PT plan";
	}else{
		$response['msg_code'] = "00";
		$response['msg'] = "ok";
		$response['price'] = $pd['price'];
	}

}else if($tab == "get_existing_family_details"){

	 $sql= "SELECT * from ".MEMBERS." WHERE member_id = '".$id."' and family_head = '1'";
	$result = $mysqli->executeQry($sql);
	$row = $mysqli->fetch_assoc($result);
	if($row){
	 extract($row); 
		$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plan_id.'"');
		$response['msg_code'] = "00";
		$response['price'] = $plan_details['price'];
		$response['plan_id'] = $plan_id;
		$response['gender'] = $gender;
		$response['timing'] = $timing;
		$response['payment_mode'] = $payment_mode;
		$response['discounted_price'] = $discounted_price;
		$response['email'] = $email;
		$response['mobile'] = $mobile;
		$response['address'] = $address;
		$response['joining_date'] = $joining_date;
		$response['head'] = $family_head;
	}else{
		$response['msg_code'] = "008";
	}
}else if ($tab == 'add_members') {
	//print_r($_POST);exit;
	$membership_type_id = isset($membership_type_id) ? (int)$membership_type_id : 0;
	$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plans.'"');
	$total_days = $plan_details['duration'] - 1;
	$plan_con = '+'.$total_days.' days';
	$end_date = date('Y-m-d', strtotime($user_doj . $plan_con));
	$dob_sql = (isset($dob) && trim($dob) != '') ? ("'" . trim($dob) . "'") : "NULL";
	$sportsperson = 0;
	$total_amount = isset($total_amount) && $total_amount !== '' ? (float)$total_amount : (float)$plan_details['price'];
	$paid = isset($paid) && $paid !== '' ? (float)$paid : 0;
	if ($paid < 0) $paid = 0;
	if ($total_amount < 0) $total_amount = 0;
	// Validation: Paid Amount must not exceed Final Amount
	if ($paid > $total_amount) $paid = $total_amount;
	$pending_amount = $total_amount - $paid;
	if ($pending_amount < 0) $pending_amount = 0;
	$payment_status = 'Paid';
	if ($paid <= 0 && $pending_amount > 0) $payment_status = 'Pending';
	else if ($pending_amount > 0) $payment_status = 'Partial';

	// Optional ID proof upload
	$id_proof_name = '';
	if (isset($_FILES['id_proof_file']) && !empty($_FILES['id_proof_file']['name'])) {
		$uploadsDir = "uploads/id_proofs/";
		if (!is_dir($uploadsDir)) {
			@mkdir($uploadsDir, 0777, true);
		}
		$allowed = array('jpg', 'jpeg', 'png', 'pdf', 'JPG', 'JPEG', 'PNG', 'PDF');
		$orig = $_FILES['id_proof_file']['name'];
		$tmp = $_FILES['id_proof_file']['tmp_name'];
		$ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
		if (in_array($ext, $allowed)) {
			$id_proof_name = time() . "_" . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $orig);
			@move_uploaded_file($tmp, $uploadsDir . $id_proof_name);
		}
	}
	if (isset($image) && $image !='') {
	$uploadsDir = "uploads/profile/";
	$image_parts = explode(";base64,", $image);
	$image_type_aux = explode("image/", $image_parts[0]);
	$image_type = $image_type_aux[1];

	$image_base64 = base64_decode($image_parts[1]);
	$fileName = uniqid() . '.png';
	$file = $uploadsDir . $fileName;
	file_put_contents($file, $image_base64);
	}else{
		$fileName = $picture;
	}
	
	if (isset($edit_id) && $edit_id !='') {
				if (true) {
					// Store the final deal amount (billing base) for invoice/history consistency.
					$paid_amount = ", discounted_price = '" . $total_amount . "'";
				}else{
					$paid_amount = "";
				}
			   $id_proof_sql = ($id_proof_name != '') ? (", id_proof = '" . addslashes($id_proof_name) . "'") : "";
			   $sql = "UPDATE " . MEMBERS . " SET membership_type_id = '" . $membership_type_id . "', membership_type = 'Single', plan_id = '" . $plans . "', name = '" . $user_name . "' , email = '" . $user_email . "', mobile= '" . $user_contact . "', gender= '" . $gender . "', age= '" . $age . "' , dob = ".$dob_sql.", address = '" . $user_address . "', joining_date = '" . $user_doj . "', start_date = '" . $user_doj . "', end_date = '" . $end_date . "', picture = '" . $fileName . "' ".$id_proof_sql.", payment_mode = '" . $mode . "', payment_status = '" . $payment_status . "', total_amount = '" . $total_amount . "', paid_amount = '" . $paid . "', pending_amount = '" . $pending_amount . "' ".$paid_amount.", sportsperson = '".$sportsperson."' WHERE id = " . $edit_id;
			$res = $mysqli->executeQry($sql);
			if ($res > 0) {
					if (true) {
					$sql_u = "UPDATE " . REVENUE . " SET  amount_received = '" . $paid . "', total_amount = '" . $total_amount . "', pending_amount = '" . $pending_amount . "', payment_status = '" . $payment_status . "', payment_type = 'MEMBERSHIP' WHERE start_date = '" . $user_doj . "'";
					$res = $mysqli->executeQry($sql_u);
				}

				// Family-group sync removed (membership types module)
			
				$response['msg_code'] = "00";
				$response['msg'] = "Successfully updated";    
				$response['redirect'] = "index.php?".$mysqli->encode("stat=users");
			} else {
				$response['msg_code'] = "05";
				$response['msg'] = "unable to update  at this time, contact to webmaster.";
			} 
		} else {
		    
			{
			$member_id = $mysqli->generate_membership_id();
			$family_head = '';
			$send_em = 1;
			}
			
			 $sql = "INSERT INTO " . MEMBERS . " SET membership_type_id = '" . $membership_type_id . "', membership_type = 'Single', plan_id = '" . $plans . "',  member_id = '" . $member_id . "', name = '" . $user_name . "' , email = '" . $user_email . "', mobile= '" . $user_contact . "', gender= '" . $gender . "', age= '" . $age . "' , dob = ".$dob_sql.", address = '" . $user_address . "', joining_date = '" . $user_doj . "', start_date = '" . $user_doj . "', end_date = '" . $end_date . "', picture = '" . $fileName . "', id_proof = '" . addslashes($id_proof_name) . "', payment_mode = '" . $mode . "', payment_status = '" . $payment_status . "', total_amount = '" . $total_amount . "', paid_amount = '" . $paid . "', pending_amount = '" . $pending_amount . "', discounted_price = '" . $total_amount . "', status = 'Active', sportsperson = '".$sportsperson."' ".$family_head.", created_on = '".date('Y-m-d H:i:s')."'";
			$log['sql'] = $sql;
			$res = $mysqli->executeQry($sql);
			$last_id = $mysqli->insert_id();
			if ($res > 0) {
				
				if($send_em == 1){
					
				
				$sql_u = "INSERT INTO " . REVENUE . " SET member_id = '" . $member_id . "', amount_received = '" . $paid . "' , total_amount = '" . $total_amount . "', pending_amount = '" . $pending_amount . "', payment_status = '" . $payment_status . "', start_date = '" . $user_doj . "' , end_date = '" . $end_date . "' , received_on = '".date('Y-m-d H:i:s')."', payment_type = 'MEMBERSHIP'";
				$res = $mysqli->executeQry($sql_u);
				
				$file = $mysqli->generateInvoice($last_id,'Single');
				if($file){
				$sql_new = "UPDATE " . MEMBERS . " SET invoice = '".$file."' WHERE id = '" . $last_id."'";
				$res1 = $mysqli->executeQry($sql_new);
				}
				 $plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plans.'"');
				 $subject = 'Welcome to Evosapiens Movement! Your Membership Details Inside | '.$member_id.'';
				 
						$body = '<p>Hello '.$user_name.',</p>';
						
						$body .= '<p>We are thrilled to welcome you to Evosapiens Movement! We are excited to have you as a member of our community and cant wait to support you on your fitness journey.</p>';
						
						$body .= '<p>Here are the details of your membership plan:</p>';
						
						$body .= '<p><b>Membership ID: '.$member_id.' </b></p>';
						
						$body .= '<p><b>Membership Plan: '.$plan_details['title'].' </b></p>';
						
						$body .= '<p><b>Start Date: '.$user_doj.'</b></p>';
						
						$body .= '<p><b>End Date: '.$end_date.'</b></p>';
						
						$body .= '<p>Thank you for choosing Evosapiens Movement! We look forward to helping you achieve your fitness goals.</p>';
						
						$body .= '<p style="color:red;"><b>Note:</b> Please note that the gym will be closed on Tuesdays.</p>';


						$body .= '<p>Best Regards,<br/>Evosapiens Movement</p>';
						
						$fromEmail = 'info@swimgymacademy.com';
						$fromName = "Evosapiens Movement";
						 $toEmail = $user_email;
						 $attachmentPath = ABSOLUTE_ROOT_INV.$file;
						
						 $isMail = $mysqli->sendEmails($subject, $body, $attachmentPath, $fromEmail, $fromName, $toEmail,  $toName = '', $bcc = '');

						 // Log Email (Welcome / Onboarding)
						 $emailStatus = ($isMail > 0) ? 'sent' : 'failed';
						 $mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
						 	member_id = '".$last_id."',
						 	type = 'email',
						 	message_type = 'welcome',
						 	status = '".$emailStatus."',
						 	message = '".addslashes(strip_tags($subject))."',
						 	provider_response = '".$emailStatus."',
						 	created_at = NOW()");

						 // Log WhatsApp attempt (Welcome)
						 $waMessage = "Hello ".$user_name.", welcome to Evosapiens Movement. Your membership is activated. Keep training!";
						 $waOk = $mysqli->sendWhatsAppMessage($user_contact, $waMessage);
						 $waStatus = $waOk ? 'sent' : 'failed';
						 $mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
						 	member_id = '".$last_id."',
						 	type = 'whatsapp',
						 	message_type = 'welcome',
						 	status = '".$waStatus."',
						 	message = '".addslashes($waMessage)."',
						 	provider_response = '".($waOk ? 'ok' : 'not configured / failed')."',
						 	created_at = NOW()");

						// Optional PT enrollment (creates PT assignment + PT revenue)
						$enroll_pt = isset($enroll_pt) && $enroll_pt == '1';
						$enroll_class = isset($enroll_class) && $enroll_class == '1';

						if ($enroll_pt) {
							$pt_plan_id = isset($pt_plan_id) ? (int)$pt_plan_id : 0;
							$pt_trainer_id = isset($pt_trainer_id) ? (int)$pt_trainer_id : 0;
							$pt_total_amount = isset($pt_total_amount) && $pt_total_amount !== '' ? (float)$pt_total_amount : 0;
							$pt_paid_amount = isset($pt_paid_amount) && $pt_paid_amount !== '' ? (float)$pt_paid_amount : 0;
							$pt_payment_mode = isset($pt_payment_mode) && $pt_payment_mode !== '' ? $pt_payment_mode : 'Cash';
							if ($pt_plan_id > 0 && $pt_trainer_id > 0) {
								$plan_details_pt = $mysqli->singleRowAssoc_new('*', PT_PLANS, 'id = "' . $pt_plan_id . '"');
								if ($plan_details_pt) {
									$total_sessions = (int)$plan_details_pt['total_sessions'];
									if ($total_sessions > 0) {
										$pt_start = $user_doj;
										$pt_end = date('Y-m-d', strtotime($pt_start . ' +' . ($total_sessions - 1) . ' days'));
										$sql_pt = "INSERT INTO " . PT_MEMBERS . " SET
											member_id = '" . $member_id . "',
											pt_plan_id = '" . $pt_plan_id . "',
											trainer_id = '" . $pt_trainer_id . "',
											start_date = '" . $pt_start . "',
											end_date = '" . $pt_end . "',
											total_sessions = '" . $total_sessions . "',
											sessions_used = '0',
											status = 'Active',
											created_on = '" . date('Y-m-d H:i:s') . "'";
										$mysqli->executeQry($sql_pt);
										$pt_member_row_id = $mysqli->insert_id();
										if ($pt_member_row_id > 0) {
											for ($i = 1; $i <= $total_sessions; $i++) {
												$mysqli->executeQry("INSERT INTO " . PT_SESSIONS . " SET pt_member_id='" . $pt_member_row_id . "', session_no='" . $i . "', status='Pending', created_on='" . date('Y-m-d H:i:s') . "'");
											}
											// PT payment (supports discount + partial)
											$pt_plan_price = (float)$plan_details_pt['price'];
											if ($pt_total_amount <= 0) $pt_total_amount = $pt_plan_price;
											if ($pt_paid_amount < 0) $pt_paid_amount = 0;
											if ($pt_paid_amount > $pt_total_amount) $pt_paid_amount = $pt_total_amount;
											$pt_pending_amount = $pt_total_amount - $pt_paid_amount;
											if ($pt_pending_amount < 0) $pt_pending_amount = 0;
											$pt_status = ($pt_pending_amount <= 0) ? 'Paid' : (($pt_paid_amount <= 0) ? 'Pending' : 'Partial');

											$mysqli->executeQry("INSERT INTO " . REVENUE . " SET
												member_id='" . $member_id . "',
												amount_received='" . $pt_paid_amount . "',
												total_amount='" . $pt_total_amount . "',
												pending_amount='" . $pt_pending_amount . "',
												payment_status='" . $pt_status . "',
												start_date='" . $pt_start . "',
												end_date='" . $pt_end . "',
												received_on='" . date('Y-m-d H:i:s') . "',
												payment_mode='" . addslashes($pt_payment_mode) . "',
												payment_type='PT'");
										}
									}
								}
							}
						}

						// Optional class enrollment (capacity + duplicate safe)
						if ($enroll_class) {
							$class_schedule_id = isset($class_schedule_id) ? (int)$class_schedule_id : 0;
							if ($class_schedule_id > 0) {
								$member_int_id = (int)$last_id;
								$dup = $mysqli->executeQry("SELECT id FROM " . CLASS_MEMBERS . " WHERE class_schedule_id = '" . $class_schedule_id . "' AND member_id = '" . $member_int_id . "' AND status='Enrolled' LIMIT 1");
								$dup_row = ($dup !== false) ? $mysqli->fetch_array($dup) : false;
								if (!$dup_row) {
									$capRes = $mysqli->executeQry("
										SELECT c.capacity AS capacity,
											(SELECT COUNT(cm2.id) FROM " . CLASS_MEMBERS . " cm2 WHERE cm2.class_schedule_id = s.id AND cm2.status='Enrolled') AS enrolled_count
										FROM " . CLASS_SCHEDULE . " s
										INNER JOIN " . CLASSES . " c ON c.id = s.class_id
										WHERE s.id = '" . $class_schedule_id . "'
										LIMIT 1
									");
									$capRow = ($capRes !== false) ? $mysqli->fetch_array($capRes) : false;
									if ($capRow && (int)$capRow['enrolled_count'] < (int)$capRow['capacity']) {
										$mysqli->executeQry("INSERT INTO " . CLASS_MEMBERS . " SET class_schedule_id='" . $class_schedule_id . "', member_id='" . $member_int_id . "', status='Enrolled', created_on='" . date('Y-m-d H:i:s') . "'");
									}
								}
							}
						}
						 
						 
				}
				$apiKey = "11";
				$employeeCode = $last_id;
				$employeeName = $user_name;
				$cardNumber = "Blank";
				$serialNumber = "CUB7235301317";
				$userName = "sgar";
				$userPassword = "Sgar@2024";
				$commandId = 0;
				$bio = $mysqli->addEmployee($apiKey, $employeeCode, $employeeName, $cardNumber, $serialNumber, $userName, $userPassword, $commandId); 
				
				$response['msg_code'] = "00";
				$response['msg'] = "Member successfully added.";
			
			} else {
				$response['msg'] = "05";
				$response['msg'] = "Unable to add at this time.";
			}
		}
}else if ($tab == 'renew_membership') {
			$members = $mysqli->selectQry(MEMBERS,"member_id = '".$member_id."'",'',''); 
			if($members->num_rows>0){
					while($member_details = $mysqli->fetch_assoc($members)){
						$sql_u = "INSERT INTO " . HISTORY . " SET member_id = '" . $member_details['id'] . "', membership_id = '" . $member_id . "', start_date = '" . $member_details['start_date'] . "' ,end_date = '" . $member_details['end_date'] . "' ,plan_id = '" . $member_details['plan_id'] . "' ,amount = '" . $member_details['discounted_price'] . "' ,payment_mode = '" . $member_details['payment_mode'] . "' ,timing = '' , renewd_on = '".date('Y-m-d H:i:s')."'";
						$res = $mysqli->executeQry($sql_u);	
						if ($res > 0) {
							
							$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$renew_plans.'"');
							$total_days = $plan_details['duration'] - 1;
							$plan_con = '+'.$total_days.' days';
							$end_date = date('Y-m-d', strtotime($user_doj . $plan_con));
							
							if($member_details['end_date'] > $user_doj){
								// Convert the date strings to DateTime objects
								$endDateObject = new DateTime($member_details['end_date']);
								$userDojObject = new DateTime($user_doj);

								// Calculate the difference between the two dates
								$dateDifference = $endDateObject->diff($userDojObject);

								// Get the number of days from the DateInterval object
								$daysDifference = $dateDifference->days;
								
								$plan_con2 = '+'.$daysDifference.' days';
								$new_expiry_date = date('Y-m-d', strtotime($end_date . $plan_con2));
								
							}else{
								$new_expiry_date = $end_date;
							}
						     $sql12 = "UPDATE " . MEMBERS . " SET  plan_id = '" . $renew_plans . "', joining_date = '" . $user_doj . "', start_date = '" . $user_doj . "', end_date = '" . $new_expiry_date . "', payment_status = 'Paid', discounted_price = '".$paid."', is_freezed = '0', status = 'Active', payment_status = 'Paid' WHERE id = " . $member_details['id'];
							$res12 = $mysqli->executeQry($sql12);	
							
							
							
							if ($member_details['membership_type'] == 'Single' || ($member_details['membership_type'] == 'Family' && $member_details['family_head'] == '1')) { 
							
							$sql_r = "INSERT INTO " . REVENUE . " SET member_id = '" . $member_id . "', amount_received = '" . $paid . "' , start_date = '" . $user_doj . "' , end_date = '" . $new_expiry_date . "' , received_on = '".date('Y-m-d H:i:s')."', payment_type = 'MEMBERSHIP'";
							$resr = $mysqli->executeQry($sql_r);
							
								$file = $mysqli->generateInvoice($member_details['id'],$member_details['membership_type']);
								if($file){
									$sql_new = "UPDATE " . MEMBERS . " SET invoice = '".$file."' WHERE id = '" . $member_details['id']."'";
									$res1 = $mysqli->executeQry($sql_new);
								}
								$subject = 'Welcome to Evosapiens Movement! Your Membership Details Inside | '.$member_id.'';
				 
								$body = '<p>Hello '.$user_name.',</p>';
								
								$body .= '<p>We are thrilled to welcome you to Evosapiens Movement! We are excited to have you as a member of our community and cant wait to support you on your fitness journey.</p>';
								
								$body .= '<p>Here are the details of your membership plan:</p>';
								
								$body .= '<p><b>Membership ID: '.$member_id.' </b></p>';
								
								$body .= '<p><b>Membership Plan: '.$plan_details['title'].' </b></p>';
								
								$body .= '<p><b>Start Date: '.$user_doj.'</b></p>';
								
								$body .= '<p><b>End Date: '.$new_expiry_date.'</b></p>';
								
								$body .= '<p>Thank you for choosing Evosapiens Movement! We look forward to helping you achieve your fitness goals.</p>';
								
								$body .= '<p style="color:red;"><b>Note:</b> Please note that the gym will be closed on Tuesdays.</p>';


								$body .= '<p>Best Regards,<br/>Evosapiens Movement</p>';
								
								$fromEmail = 'info@swimgymacademy.com';
								$fromName = "Evosapiens Movement";
								 $toEmail = $member_details['email'];
								 $attachmentPath = ABSOLUTE_ROOT_INV.$file;
								 $isMail = $mysqli->sendEmails($subject, $body, $attachmentPath, $fromEmail, $fromName, $toEmail,  $toName = '', $bcc = '');
							}
							 $apiKey = "11";
							$employeeCode = $member_details['id'];
							$employeeName = $user_name;
							$isBlock = "false"; // or "false"
							$serialNumber = "CUB7235301317";
							$userName = "sgar";
							$userPassword = "Sgar@2024";
							$commandId = 0;
							$block = $mysqli->blockUnblockUser($apiKey, $employeeCode, $employeeName, $serialNumber, $isBlock, $userName, $userPassword, $commandId); 
							$response['msg_code'] = "00";
							$response['msg'] = "Membership renewed.";
							$response['redirect'] = "index.php?".$mysqli->encode("stat=users");
						}else {
							$response['msg'] = "05";
							$response['msg'] = "Unable to renew at this time.";
						} 
					}
			}					
			
		
} // Set execution time to 5 minutes

else if ($tab == 'send_email') {
    
    if (!isset($email_body) || trim($email_body) == '') {
        $response['msg'] = "Email body can't be blank.";
    } elseif (strlen($email_body) <= 10) {
        $response['msg'] = "Email body must be more than 30 characters.";
    } else {
        $RecTimeStamp = $mysqli->RecTimeStamp("Y-m-d H:i:s");
        $subject = 'Stay Updated with Evosapiens Movement: Important News and Updates Inside';
        $sql_u = "INSERT INTO " . NOTIFICATIONS . " SET subject = '" . $subject . "', email = '" . $email_body . "', sent_on = '" . $RecTimeStamp . "'";
        $res = $mysqli->executeQry($sql_u);

        $sql = "SELECT * FROM " . MEMBERS . " WHERE (membership_type = 'single' OR (membership_type = 'family' AND family_head = 1))";
        $result = $mysqli->executeQry($sql);

        while ($member_details = $mysqli->fetch_assoc($result)) {
            $body = '<p>Hello ' . $member_details['name'] . ',</p>';
            $body .= $email_body;
            $body .= '<p>Best Regards,<br/>Evosapiens Movement</p>';

            $fromEmail = 'info@swimgymacademy.com';
            $fromName = "Evosapiens Movement";
            $toEmail = $member_details['email'];
            $attachmentPath = '';

            $isMail = $mysqli->sendEmails($subject, $body, $attachmentPath, $fromEmail, $fromName, $toEmail, $toName = '', $bcc = '');

			$emailStatus = ($isMail > 0) ? 'sent' : 'failed';
			$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
				member_id = '" . (int)$member_details['id'] . "',
				type = 'email',
				message_type = 'custom',
				status = '".$emailStatus."',
				message = '".addslashes($email_body)."',
				provider_response = '".$emailStatus."',
				created_at = NOW()");

        }

        $response['msg_code'] = "00";
        $response['msg'] = "Notification sent.";
        $response['redirect'] = "index.php?" . $mysqli->encode("stat=messages");
    }
}
else if ($tab == 'add_plans') {
	// Backward compatible duration handling:
	// - UI now sends duration in months: 1,3,6,12,24
	// - DB stores duration in days
	// - Older UI might still send days (30,90,180,...)
	$durationDays = (int)$duration;
	$monthToDays = [
		1 => 30,
		3 => 90,
		6 => 180,
		12 => 365,
		24 => 730
	];
	if (isset($monthToDays[$durationDays])) {
		$durationDays = (int)$monthToDays[$durationDays];
	}
		
	if (isset($edit_id) && $edit_id !='') {
			
			   $sql = "UPDATE " . PLANS . " SET SET title = '".$title."', price = '" . $price . "',  plan_type = '" . $plan_type . "', duration = '" . $durationDays . "' WHERE id = " . $edit_id;
			$res = $mysqli->executeQry($sql);
			if ($res > 0) {
				
				$response['msg_code'] = "00";
				$response['msg'] = "Plan successfully updated";    
				$response['redirect'] = "index.php?".$mysqli->encode("stat=plans");
			} else {
				$response['msg_code'] = "05";
				$response['msg'] = "unable to update  at this time, contact to webmaster.";
			} 
		} else {
			
			 $sql = "INSERT INTO " . PLANS . " SET title = '".$title."', price = '" . $price . "',  plan_type = '" . $plan_type . "', duration = '" . $durationDays . "' ";
			$log['sql'] = $sql;
			$res = $mysqli->executeQry($sql);
			$last_id = $mysqli->insert_id();
			if ($res > 0) {
				$response['msg_code'] = "00";
				$response['msg'] = "Plan successfully added.";
				$response['redirect'] = "index.php?".$mysqli->encode("stat=plans");
			
			} else {
				$response['msg'] = "05";
				$response['msg'] = "Unable to add at this time.";
			}
		}
}else if($tab == 'add_declaration'){
	// Declaration feature removed for gym version.
	$response['msg_code'] = "102";
	$response['msg'] = "Option not found";
}else if ($tab == 'freeze_membership') {
	if($freeze <= 15){
	$end_date = $mysqli->singleRowAssoc_new('end_date', MEMBERS, 'id = "'.$edit_id.'"');
	$freeze = $freeze - 1;
	$plan_con = '+'.$freeze.' days';
	$cur_date = date('Y-m-d');
	$new_date = date('Y-m-d', strtotime($end_date['end_date'] . $plan_con));
	$freezed_till = date('Y-m-d', strtotime($cur_date . $plan_con));
	
	 $sql = "UPDATE " . MEMBERS . " SET end_date = '".$new_date."', is_freezed = '1', membership_freezed_till = '".$freezed_till."', membership_freezed_on = '".$cur_date."', freezed_for_days = '".$freeze."' WHERE id = " . $edit_id;
	$res = $mysqli->executeQry($sql);
	if ($res > 0) {
		$response['msg_code'] = "00";
		$response['msg'] = "Membership successfully freezed.";
		$response['redirect'] = "index.php?".$mysqli->encode("stat=users");
	} else {
		$response['msg_code'] = "05";
		$response['msg'] = "unable to freeze at this time, contact to webmaster.";
	} 
	}else{
		$response['msg_code'] = "05";
		$response['msg'] = "Only 15 days are allowed to freeze membership.";
	}
}else if ($tab == 'delete_member') {
	$sql = "DELETE FROM " . MEMBERS . " WHERE id = " . $id . " LIMIT 1";
	$res = $mysqli->executeQry($sql);
	if ($res > 0) {
		$response['msg_code'] = "00";
		$response['msg'] = "Member successfully deleted.";
	} else {
		$response['msg_code'] = "05";
		$response['msg'] = "unable to remove at this time, contact to webmaster.";
	}

	$record_id = $id;
} else if ($tab == 'delete_plans') {
	$sql = "DELETE FROM " . PLANS . " WHERE id = " . $id . " LIMIT 1";
	$res = $mysqli->executeQry($sql);
	if ($res > 0) {
		$response['msg_code'] = "00";
		$response['msg'] = "Plan successfully deleted.";
	} else {
		$response['msg_code'] = "05";
		$response['msg'] = "unable to remove at this time, contact to webmaster.";
	}

	$record_id = $id;
} else if ($tab == 'add_employee') {
	if (isset($edit_id) && $edit_id != '') {
		$sql = "UPDATE " . EMPLOYEES . " SET 
			name = '" . $name . "',
			phone = '" . $phone . "',
			designation = '" . $designation . "',
			joining_date = '" . $joining_date . "',
			status = '" . $status . "'
		WHERE id = '" . $edit_id . "' LIMIT 1";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "Employee successfully updated.";
			// Create or update login credentials for trainers / receptionists.
			$record_id = $edit_id;
			if (in_array($designation, ['trainer', 'receptionist']) && $status === 'Active') {
				// Username: use phone as login (fallback to "emp{ID}" if phone missing).
				$username = !empty($phone) ? trim($phone) : ('emp'.$record_id);
				// Generate a simple random numeric password (kept plain only for sending).
				$plainPassword = (string)rand(100000, 999999);
				$encodedPassword = $mysqli->encode($plainPassword);

				$user_type = ($designation === 'trainer') ? 'TRAINER' : 'RECEPTIONIST';

				// Check if a user already exists for this username.
				$uRes = $mysqli->executeQry("SELECT user_id FROM ".USERS." WHERE email='" . $mysqli->escape($username) . "' LIMIT 1");
				if ($uRes && $mysqli->getTotalRow($uRes) > 0) {
					$uRow = $mysqli->fetch_array($uRes);
					$mysqli->executeQry("
						UPDATE ".USERS." SET
							user_name='" . $mysqli->escape($name) . "',
							password='" . $mysqli->escape($encodedPassword) . "',
							user_type='" . $mysqli->escape($user_type) . "',
							active='1'
						WHERE user_id='" . (int)$uRow['user_id'] . "'
						LIMIT 1
					");
				} else {
					$mysqli->executeQry("
						INSERT INTO ".USERS." SET
							user_name='" . $mysqli->escape($name) . "',
							email='" . $mysqli->escape($username) . "',
							password='" . $mysqli->escape($encodedPassword) . "',
							user_type='" . $mysqli->escape($user_type) . "',
							active='1',
							created_on=NOW()
					");
				}

				// Send credentials via WhatsApp if phone is present.
				if (!empty($phone)) {
					$loginUrl = APPLICATION_URL . "login.php";
					$waText = "Hello " . $name . ", your Evosapiens Movement staff login is ready.\n"
						. "Login URL: " . $loginUrl . "\n"
						. "Username: " . $username . "\n"
						. "Password: " . $plainPassword . "\n"
						. "Please change your password after first login.";
					$waOk = $mysqli->sendWhatsAppMessage($phone, $waText);
					$waStatus = $waOk ? 'sent' : 'failed';
					$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
						member_id = 0,
						type = 'whatsapp',
						message_type = 'employee_credentials',
						status = '" . $waStatus . "',
						message = '" . addslashes($waText) . "',
						provider_response = '" . ($waOk ? 'ok' : 'not configured / failed') . "',
						created_at = NOW()");
				}
			}
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to update employee.";
		}
		if (!isset($record_id)) {
			$record_id = $edit_id;
		}
	} else {
		$sql = "INSERT INTO " . EMPLOYEES . " SET 
			name = '" . $name . "',
			phone = '" . $phone . "',
			designation = '" . $designation . "',
			joining_date = '" . $joining_date . "',
			status = '" . $status . "'";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "Employee successfully added.";
			$record_id = $mysqli->insert_id();
			// Create login credentials for new trainers / receptionists.
			if (in_array($designation, ['trainer', 'receptionist']) && $status === 'Active') {
				$username = !empty($phone) ? trim($phone) : ('emp'.$record_id);
				$plainPassword = (string)rand(100000, 999999);
				$encodedPassword = $mysqli->encode($plainPassword);

				$user_type = ($designation === 'trainer') ? 'TRAINER' : 'RECEPTIONIST';

				$mysqli->executeQry("
					INSERT INTO ".USERS." SET
						user_name='" . $mysqli->escape($name) . "',
						email='" . $mysqli->escape($username) . "',
						password='" . $mysqli->escape($encodedPassword) . "',
						user_type='" . $mysqli->escape($user_type) . "',
						active='1',
						created_on=NOW()
				");

				if (!empty($phone)) {
					$loginUrl = APPLICATION_URL . "login.php";
					$waText = "Hello " . $name . ", your Evosapiens Movement staff login is ready.\n"
						. "Login URL: " . $loginUrl . "\n"
						. "Username: " . $username . "\n"
						. "Password: " . $plainPassword . "\n"
						. "Please change your password after first login.";
					$waOk = $mysqli->sendWhatsAppMessage($phone, $waText);
					$waStatus = $waOk ? 'sent' : 'failed';
					$mysqli->executeQry("INSERT INTO ".MESSAGE_LOGS." SET
						member_id = 0,
						type = 'whatsapp',
						message_type = 'employee_credentials',
						status = '" . $waStatus . "',
						message = '" . addslashes($waText) . "',
						provider_response = '" . ($waOk ? 'ok' : 'not configured / failed') . "',
						created_at = NOW()");
				}
			}
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to add employee.";
		}
		if (!isset($record_id)) {
			$record_id = $mysqli->insert_id();
		}
	}
} else if ($tab == 'delete_employee') {
	$sql = "DELETE FROM " . EMPLOYEES . " WHERE id = " . $id . " LIMIT 1";
	$res = $mysqli->executeQry($sql);
	if ($res > 0) {
		$response['msg_code'] = "00";
		$response['msg'] = "Employee successfully deleted.";
	} else {
		$response['msg_code'] = "05";
		$response['msg'] = "Unable to delete employee.";
	}
	$record_id = $id;
} else if ($tab == 'add_pt_plan') {
	if (isset($edit_id) && $edit_id != '') {
		$sql = "UPDATE " . PT_PLANS . " SET
			title = '" . $title . "',
			total_sessions = '" . $total_sessions . "',
			price = '" . $price . "'
		WHERE id = '" . $edit_id . "' LIMIT 1";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "PT plan successfully updated.";
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to update PT plan.";
		}
		$record_id = $edit_id;
	} else {
		$sql = "INSERT INTO " . PT_PLANS . " SET
			title = '" . $title . "',
			total_sessions = '" . $total_sessions . "',
			price = '" . $price . "'";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "PT plan successfully added.";
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to add PT plan.";
		}
		$record_id = $mysqli->insert_id();
	}
} else if ($tab == 'delete_pt_plan') {
	$sql = "DELETE FROM " . PT_PLANS . " WHERE id = " . $id . " LIMIT 1";
	$res = $mysqli->executeQry($sql);
	if ($res > 0) {
		$response['msg_code'] = "00";
		$response['msg'] = "PT plan successfully deleted.";
	} else {
		$response['msg_code'] = "05";
		$response['msg'] = "Unable to delete PT plan.";
	}
	$record_id = $id;
} else if ($tab == 'add_pt_member') {
	$pt_plan_id = isset($pt_plan_id) ? $pt_plan_id : '';
	$trainer_id = isset($trainer_id) ? $trainer_id : '';
	$member_id = isset($member_id) ? $member_id : '';
	$start_date = isset($start_date) ? $start_date : date('Y-m-d');

	$plan_details = $mysqli->singleRowAssoc_new('*', PT_PLANS, 'id = "' . $pt_plan_id . '"');
	if (!$plan_details) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid PT plan selected.";
	} else {
		$total_sessions = (int)$plan_details['total_sessions'];
		if ($total_sessions < 1) {
			$response['msg_code'] = "05";
			$response['msg'] = "PT plan has invalid total sessions.";
		} else {
			// Billing rule:
			// - final_amount is the actual deal value
			// - pending_amount = final_amount - paid_amount
			$final_amount = isset($final_amount) && $final_amount !== '' ? (float)$final_amount : (float)$plan_details['price'];
			$paid = isset($paid) && $paid !== '' ? (float)$paid : 0;
			$mode = isset($mode) && $mode !== '' ? $mode : 'Cash';
			if ($final_amount < 0) $final_amount = 0;
			if ($paid < 0) $paid = 0;
			if ($paid > $final_amount) $paid = $final_amount;
			$pending_amount = $final_amount - $paid;
			if ($pending_amount < 0) $pending_amount = 0;
			$pt_payment_status = ($pending_amount <= 0) ? 'Paid' : (($paid <= 0) ? 'Pending' : 'Partial');

			$end_date = date('Y-m-d', strtotime($start_date . ' +' . ($total_sessions - 1) . ' days'));

			$sql = "INSERT INTO " . PT_MEMBERS . " SET
				member_id = '" . $member_id . "',
				pt_plan_id = '" . $pt_plan_id . "',
				trainer_id = '" . $trainer_id . "',
				start_date = '" . $start_date . "',
				end_date = '" . $end_date . "',
				total_sessions = '" . $total_sessions . "',
				sessions_used = '0',
				status = 'Active',
				created_on = '" . date('Y-m-d H:i:s') . "'";

			$res = $mysqli->executeQry($sql);
			$pt_member_row_id = $mysqli->insert_id();

			if ($res > 0 && $pt_member_row_id > 0) {
				// Create PT sessions as Pending
				for ($i = 1; $i <= $total_sessions; $i++) {
					$sql_s = "INSERT INTO " . PT_SESSIONS . " SET
						pt_member_id = '" . $pt_member_row_id . "',
						session_no = '" . $i . "',
						status = 'Pending',
						created_on = '" . date('Y-m-d H:i:s') . "'";
					$mysqli->executeQry($sql_s);
				}

				// Payment entry (type=PT) in tbl_revenue
				$sql_r = "INSERT INTO " . REVENUE . " SET
					member_id = '" . $member_id . "',
					amount_received = '" . $paid . "',
					total_amount = '" . $final_amount . "',
					pending_amount = '" . $pending_amount . "',
					payment_status = '" . $pt_payment_status . "',
					start_date = '" . $start_date . "',
					end_date = '" . $end_date . "',
					payment_mode = '" . addslashes($mode) . "',
					received_on = '" . date('Y-m-d H:i:s') . "',
					payment_type = 'PT'";
				$mysqli->executeQry($sql_r);

				$response['msg_code'] = "00";
				$response['msg'] = "PT assigned successfully.";
				$record_id = $pt_member_row_id;
			} else {
				$response['msg_code'] = "05";
				$response['msg'] = "Unable to assign PT at this time.";
			}
		}
	}
} else if ($tab == 'mark_pt_session') {
	$pt_member_id = isset($pt_member_id) ? $pt_member_id : '';
	$pt = $mysqli->singleRowAssoc_new('*', PT_MEMBERS, 'id = "' . $pt_member_id . '"');
	if (!$pt) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid PT assignment.";
	} elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'TRAINER') {
		// Trainers must only mark sessions for their own PT assignments.
		$trainer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
		$pt_trainer_id = isset($pt['trainer_id']) ? (int)$pt['trainer_id'] : 0;
		if ($trainer_id < 1 || $pt_trainer_id !== $trainer_id) {
			$response['msg_code'] = "05";
			$response['msg'] = "Access denied for this PT assignment.";
		} elseif (isset($pt['status']) && $pt['status'] === 'Completed') {
			$response['msg_code'] = "05";
			$response['msg'] = "This PT assignment is already completed.";
		} else {
			$next = $mysqli->singleRowAssoc_new('*', PT_SESSIONS, 'pt_member_id = "' . $pt_member_id . '" AND status = "Pending" ORDER BY session_no ASC LIMIT 1');
			if (!$next || !isset($next['id'])) {
				$response['msg_code'] = "05";
				$response['msg'] = "No pending session found.";
			} else {
				$next_id = $next['id'];
				$mysqli->executeQry("UPDATE " . PT_SESSIONS . " SET status = 'Used', used_on = '" . date('Y-m-d H:i:s') . "' WHERE id = '" . $next_id . "' LIMIT 1");
				$mysqli->executeQry("UPDATE " . PT_MEMBERS . " SET sessions_used = sessions_used + 1 WHERE id = '" . $pt_member_id . "'");

				$pt2 = $mysqli->singleRowAssoc_new('*', PT_MEMBERS, 'id = "' . $pt_member_id . '"');
				$total_sessions = isset($pt2['total_sessions']) ? (int)$pt2['total_sessions'] : 0;
				$sessions_used = isset($pt2['sessions_used']) ? (int)$pt2['sessions_used'] : 0;

				if ($total_sessions > 0 && $sessions_used >= $total_sessions) {
					$mysqli->executeQry("UPDATE " . PT_MEMBERS . " SET status = 'Completed', end_date = CURDATE() WHERE id = '" . $pt_member_id . "' LIMIT 1");
				}

				$response['msg_code'] = "00";
				$response['msg'] = "PT session marked completed.";
				$record_id = $pt_member_id;
			}
		}
	} else if (isset($pt['status']) && $pt['status'] === 'Completed') {
		$response['msg_code'] = "05";
		$response['msg'] = "This PT assignment is already completed.";
	} else {
		$next = $mysqli->singleRowAssoc_new('*', PT_SESSIONS, 'pt_member_id = "' . $pt_member_id . '" AND status = "Pending" ORDER BY session_no ASC LIMIT 1');
		if (!$next || !isset($next['id'])) {
			$response['msg_code'] = "05";
			$response['msg'] = "No pending session found.";
		} else {
			$next_id = $next['id'];
			$mysqli->executeQry("UPDATE " . PT_SESSIONS . " SET status = 'Used', used_on = '" . date('Y-m-d H:i:s') . "' WHERE id = '" . $next_id . "' LIMIT 1");
			$mysqli->executeQry("UPDATE " . PT_MEMBERS . " SET sessions_used = sessions_used + 1 WHERE id = '" . $pt_member_id . "'");

			$pt2 = $mysqli->singleRowAssoc_new('*', PT_MEMBERS, 'id = "' . $pt_member_id . '"');
			$total_sessions = isset($pt2['total_sessions']) ? (int)$pt2['total_sessions'] : 0;
			$sessions_used = isset($pt2['sessions_used']) ? (int)$pt2['sessions_used'] : 0;

			if ($total_sessions > 0 && $sessions_used >= $total_sessions) {
				$mysqli->executeQry("UPDATE " . PT_MEMBERS . " SET status = 'Completed', end_date = CURDATE() WHERE id = '" . $pt_member_id . "' LIMIT 1");
			}

			$response['msg_code'] = "00";
			$response['msg'] = "PT session marked completed.";
			$record_id = $pt_member_id;
		}
	}
}
 else if ($tab == 'add_class') {
 	if (isset($edit_id) && $edit_id != '') {
 		$sql = "UPDATE " . CLASSES . " SET
 			title = '" . $title . "',
 			trainer_id = '" . $trainer_id . "',
 			capacity = '" . $capacity . "',
 			status = '" . $status . "'
 		WHERE id = '" . $edit_id . "' LIMIT 1";
 		$res = $mysqli->executeQry($sql);
 		if ($res > 0) {
 			$response['msg_code'] = "00";
 			$response['msg'] = "Class successfully updated.";
 		} else {
 			$response['msg_code'] = "05";
 			$response['msg'] = "Unable to update class.";
 		}
 		$record_id = $edit_id;
 	} else {
 		$sql = "INSERT INTO " . CLASSES . " SET
 			title = '" . $title . "',
 			trainer_id = '" . $trainer_id . "',
 			capacity = '" . $capacity . "',
 			status = '" . $status . "'";
 		$res = $mysqli->executeQry($sql);
 		if ($res > 0) {
 			$response['msg_code'] = "00";
 			$response['msg'] = "Class successfully added.";
 		} else {
 			$response['msg_code'] = "05";
 			$response['msg'] = "Unable to add class.";
 		}
 		$record_id = $mysqli->insert_id();
 	}
 } else if ($tab == 'delete_class') {
 	$sql = "DELETE FROM " . CLASSES . " WHERE id = " . $id . " LIMIT 1";
 	$res = $mysqli->executeQry($sql);
 	if ($res > 0) {
 		$response['msg_code'] = "00";
 		$response['msg'] = "Class successfully deleted.";
 	} else {
 		$response['msg_code'] = "05";
 		$response['msg'] = "Unable to delete class.";
 	}
 	$record_id = $id;
 } else if ($tab == 'add_class_schedule') {
 	if (isset($edit_id) && $edit_id != '') {
 		$sql = "UPDATE " . CLASS_SCHEDULE . " SET
 			class_id = '" . $class_id . "',
 			schedule_date = '" . $schedule_date . "',
 			start_time = '" . $start_time . "',
 			end_time = '" . $end_time . "',
 			status = '" . $status . "'
 		WHERE id = '" . $edit_id . "' LIMIT 1";
 		$res = $mysqli->executeQry($sql);
 		if ($res > 0) {
 			$response['msg_code'] = "00";
 			$response['msg'] = "Schedule successfully updated.";
 		} else {
 			$response['msg_code'] = "05";
 			$response['msg'] = "Unable to update schedule.";
 		}
 		$record_id = $edit_id;
 	} else {
 		$sql = "INSERT INTO " . CLASS_SCHEDULE . " SET
 			class_id = '" . $class_id . "',
 			schedule_date = '" . $schedule_date . "',
 			start_time = '" . $start_time . "',
 			end_time = '" . $end_time . "',
 			status = '" . $status . "'";
 		$res = $mysqli->executeQry($sql);
 		if ($res > 0) {
 			$response['msg_code'] = "00";
 			$response['msg'] = "Schedule successfully added.";
 		} else {
 			$response['msg_code'] = "05";
 			$response['msg'] = "Unable to add schedule.";
 		}
 		$record_id = $mysqli->insert_id();
 	}
 } else if ($tab == 'delete_class_schedule') {
 	$sql = "DELETE FROM " . CLASS_SCHEDULE . " WHERE id = " . $id . " LIMIT 1";
 	$res = $mysqli->executeQry($sql);
 	if ($res > 0) {
 		$response['msg_code'] = "00";
 		$response['msg'] = "Schedule successfully deleted.";
 	} else {
 		$response['msg_code'] = "05";
 		$response['msg'] = "Unable to delete schedule.";
 	}
 	$record_id = $id;
 } else if ($tab == 'enroll_class_member') {
 	$class_schedule_id = isset($class_schedule_id) ? (int)$class_schedule_id : 0;
 	$member_id = isset($member_id) ? (int)$member_id : 0;

 	if ($class_schedule_id < 1 || $member_id < 1) {
 		$response['msg_code'] = "05";
 		$response['msg'] = "Invalid schedule or member.";
 	} else {
 		// Duplicate check
		$dup = $mysqli->executeQry("SELECT id FROM " . CLASS_MEMBERS . " WHERE class_schedule_id = '" . $class_schedule_id . "' AND member_id = '" . $member_id . "' AND status='Enrolled' LIMIT 1");
		$dup_row = $dup ? $mysqli->fetch_array($dup) : false;
 		if ($dup_row && isset($dup_row['id'])) {
 			$response['msg_code'] = "05";
 			$response['msg'] = "Member already enrolled for this schedule.";
 		} else {
 			$sql_cap = "SELECT c.capacity,
 								(SELECT COUNT(*) FROM " . CLASS_MEMBERS . " cm2
 								 WHERE cm2.class_schedule_id = s.id AND cm2.status='Enrolled') AS enrolled_count
 							FROM " . CLASS_SCHEDULE . " s
 							JOIN " . CLASSES . " c ON c.id = s.class_id
 							WHERE s.id = '" . $class_schedule_id . "'
 							LIMIT 1";
 			$res_cap = $mysqli->executeQry($sql_cap);
			$cap_row = $res_cap ? $mysqli->fetch_array($res_cap) : false;
 			$capacity = isset($cap_row['capacity']) ? (int)$cap_row['capacity'] : 0;
 			$enrolled_count = isset($cap_row['enrolled_count']) ? (int)$cap_row['enrolled_count'] : 0;

 			if ($capacity > 0 && $enrolled_count >= $capacity) {
 				$response['msg_code'] = "05";
 				$response['msg'] = "This class is full (capacity reached).";
 			} else {
 				$sql = "INSERT INTO " . CLASS_MEMBERS . " SET
 					class_schedule_id = '" . $class_schedule_id . "',
 					member_id = '" . $member_id . "',
 					status = 'Enrolled',
 					created_on = '" . date('Y-m-d H:i:s') . "'";
 				$res = $mysqli->executeQry($sql);
 				if ($res > 0) {
 					$response['msg_code'] = "00";
 					$response['msg'] = "Member enrolled successfully.";
 					$record_id = $mysqli->insert_id();
 				} else {
 					$response['msg_code'] = "05";
 					$response['msg'] = "Unable to enroll member at this time.";
 				}
 			}
 		}
 	}
 } else if ($tab == 'remove_class_member') {
 	$sql = "DELETE FROM " . CLASS_MEMBERS . " WHERE id = " . $id . " LIMIT 1";
 	$res = $mysqli->executeQry($sql);
 	if ($res > 0) {
 		$response['msg_code'] = "00";
 		$response['msg'] = "Enrollment removed successfully.";
 	} else {
 		$response['msg_code'] = "05";
 		$response['msg'] = "Unable to remove enrollment.";
 	}
 	$record_id = $id;
} else if ($tab == 'add_membership_type') {
	$name = isset($name) ? trim($name) : '';
	$description = isset($description) ? trim($description) : '';
	$status = isset($status) && $status != '' ? $status : 'Active';

	if ($name == '') {
		$response['msg_code'] = "05";
		$response['msg'] = "Name is required.";
	} else if (isset($edit_id) && $edit_id != '') {
		$sql = "UPDATE " . MEMBERSHIP_TYPES . " SET
			name = '" . addslashes($name) . "',
			description = '" . addslashes($description) . "',
			status = '" . addslashes($status) . "'
		WHERE id = '" . $edit_id . "' LIMIT 1";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "Membership type updated.";
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to update membership type.";
		}
		$record_id = $edit_id;
	} else {
		$sql = "INSERT INTO " . MEMBERSHIP_TYPES . " SET
			name = '" . addslashes($name) . "',
			description = '" . addslashes($description) . "',
			status = '" . addslashes($status) . "',
			created_at = NOW()";
		$res = $mysqli->executeQry($sql);
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "Membership type added.";
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to add membership type.";
		}
		$record_id = $mysqli->insert_id();
	}
} else if ($tab == 'toggle_membership_type') {
	$id = isset($id) ? (int)$id : 0;
	$status = isset($status) ? $status : '';
	if ($id < 1 || ($status != 'Active' && $status != 'Inactive')) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid request.";
	} else {
		$res = $mysqli->executeQry("UPDATE " . MEMBERSHIP_TYPES . " SET status = '" . addslashes($status) . "' WHERE id = '" . $id . "' LIMIT 1");
		if ($res > 0) {
			$response['msg_code'] = "00";
			$response['msg'] = "Status updated.";
		} else {
			$response['msg_code'] = "05";
			$response['msg'] = "Unable to update status.";
		}
		$record_id = $id;
	}
} else if ($tab == 'delete_membership_type') {
	$id = isset($id) ? (int)$id : 0;
	if ($id < 1) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid request.";
	} else {
		// Prevent delete if used
		$chk = $mysqli->executeQry("SELECT COUNT(id) AS cnt FROM " . MEMBERS . " WHERE membership_type_id = '" . $id . "'");
		$row = $chk ? $mysqli->fetch_array($chk) : false;
		$cnt = $row && isset($row['cnt']) ? (int)$row['cnt'] : 0;
		if ($cnt > 0) {
			$response['msg_code'] = "05";
			$response['msg'] = "Cannot delete: this membership type is already used by members.";
		} else {
			$res = $mysqli->executeQry("DELETE FROM " . MEMBERSHIP_TYPES . " WHERE id = '" . $id . "' LIMIT 1");
			if ($res > 0) {
				$response['msg_code'] = "00";
				$response['msg'] = "Membership type deleted.";
			} else {
				$response['msg_code'] = "05";
				$response['msg'] = "Unable to delete membership type.";
			}
			$record_id = $id;
		}
	}
} else if ($tab == 'get_member_profile') {
	$member_row_id = isset($member_row_id) ? (int)$member_row_id : 0;
	if ($member_row_id < 1) {
		echo '<div class="alert alert-danger">Invalid member.</div>';
		exit;
	}

	$m_q = $mysqli->executeQry("SELECT m.*, p.title AS plan_title, mt.name AS mt_name
		FROM " . MEMBERS . " m
		LEFT JOIN " . PLANS . " p ON p.id = m.plan_id
		LEFT JOIN " . MEMBERSHIP_TYPES . " mt ON mt.id = m.membership_type_id
		WHERE m.id = '" . $member_row_id . "' LIMIT 1");
	$m = $m_q ? $mysqli->fetch_assoc($m_q) : false;
	if (!$m) {
		echo '<div class="alert alert-danger">Member not found.</div>';
		exit;
	}

	$membership_id = $m['member_id'];
	$total = (float)$m['total_amount'];
	$paid = (float)$m['paid_amount'];
	$pending = (float)$m['pending_amount'];

	// Payment history (membership only)
	$payRes = $mysqli->executeQry("SELECT * FROM " . REVENUE . " WHERE member_id = '" . addslashes($membership_id) . "' AND payment_type='MEMBERSHIP' ORDER BY received_on DESC");

	// PT overview
	$ptRes = $mysqli->executeQry("SELECT pm.*, pp.title AS pt_title, e.name AS trainer_name
		FROM " . PT_MEMBERS . " pm
		LEFT JOIN " . PT_PLANS . " pp ON pp.id = pm.pt_plan_id
		LEFT JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
		WHERE pm.member_id = '" . addslashes($membership_id) . "'
		ORDER BY pm.id DESC");

	// Class enrollments
	$classRes = $mysqli->executeQry("SELECT cm.created_on, cm.status, c.title, cs.schedule_date, cs.start_time, cs.end_time
		FROM " . CLASS_MEMBERS . " cm
		INNER JOIN " . CLASS_SCHEDULE . " cs ON cs.id = cm.class_schedule_id
		INNER JOIN " . CLASSES . " c ON c.id = cs.class_id
		WHERE cm.member_id = '" . (int)$member_row_id . "'
		ORDER BY cm.id DESC");

	// PT vs membership warning
	$warn = '';
	if ($ptRes) {
		$ptTop = $mysqli->fetch_assoc($ptRes);
		if ($ptTop && isset($ptTop['end_date']) && $ptTop['end_date'] > $m['end_date']) {
			$warn = '<div class="alert alert-warning">PT duration exceeds membership validity. Consider renewing membership.</div>';
		}
		// rewind not possible; re-run
		$ptRes = $mysqli->executeQry("SELECT pm.*, pp.title AS pt_title, e.name AS trainer_name
			FROM " . PT_MEMBERS . " pm
			LEFT JOIN " . PT_PLANS . " pp ON pp.id = pm.pt_plan_id
			LEFT JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
			WHERE pm.member_id = '" . addslashes($membership_id) . "'
			ORDER BY pm.id DESC");
	}

	echo '<div class="mb-3">';
	echo $warn;
	echo '<h5 class="mb-1">' . htmlspecialchars($m['name']) . '</h5>';
	echo '<div class="text-muted">Membership ID: <b>' . htmlspecialchars($membership_id) . '</b></div>';
	echo '<div class="text-muted">Membership Type: <b>' . htmlspecialchars($m['mt_name'] ? $m['mt_name'] : '-') . '</b></div>';
	echo '<div class="text-muted">Plan: <b>' . htmlspecialchars($m['plan_title'] ? $m['plan_title'] : '-') . '</b></div>';
	echo '<div class="text-muted">Validity: <b>' . htmlspecialchars($m['start_date']) . '</b> to <b>' . htmlspecialchars($m['end_date']) . '</b></div>';
	echo '</div>';

	echo '<div class="card mb-3"><div class="card-body">';
	echo '<div class="d-flex justify-content-between"><span>Total</span><b>' . number_format($total, 2) . '</b></div>';
	echo '<div class="d-flex justify-content-between"><span>Paid</span><b>' . number_format($paid, 2) . '</b></div>';
	echo '<div class="d-flex justify-content-between"><span>Pending</span><b>' . number_format($pending, 2) . '</b></div>';
	echo '<div class="mt-2">';
	echo '<button class="btn btn-sm btn-primary me-1" onclick="open_update_payment(' . (int)$member_row_id . ')">Update Payment</button>';
	echo '<a class="btn btn-sm btn-success me-1" href="index.php?' . $mysqli->encode('stat=pt_members') . '">Assign PT</a>';
	echo '<a class="btn btn-sm btn-info" href="index.php?' . $mysqli->encode('stat=class_members') . '">Enroll in Class</a>';
	echo '</div>';
	echo '</div></div>';

	echo '<div class="card mb-3"><div class="card-header"><h6 class="mb-0">Payment History</h6></div><div class="card-body p-0">';
	echo '<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Date</th><th>Paid</th><th>Status</th><th>Pending</th></tr></thead><tbody>';
	if ($payRes) {
		$has = false;
		while ($pr = $mysqli->fetch_assoc($payRes)) {
			$has = true;
			echo '<tr>';
			echo '<td>' . htmlspecialchars($pr['received_on']) . '</td>';
			echo '<td>' . htmlspecialchars($pr['amount_received']) . '</td>';
			echo '<td>' . htmlspecialchars(isset($pr['payment_status']) ? $pr['payment_status'] : 'Paid') . '</td>';
			echo '<td>' . htmlspecialchars(isset($pr['pending_amount']) ? $pr['pending_amount'] : '') . '</td>';
			echo '</tr>';
		}
		if (!$has) echo '<tr><td colspan="4" class="text-center text-muted">No payments yet.</td></tr>';
	} else {
		echo '<tr><td colspan="4" class="text-center text-muted">No payments yet.</td></tr>';
	}
	echo '</tbody></table></div></div></div>';

	echo '<div class="card mb-3"><div class="card-header"><h6 class="mb-0">PT</h6></div><div class="card-body p-0">';
	echo '<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Plan</th><th>Trainer</th><th>Start</th><th>End</th><th>Status</th></tr></thead><tbody>';
	if ($ptRes) {
		$has = false;
		while ($pt = $mysqli->fetch_assoc($ptRes)) {
			$has = true;
			echo '<tr>';
			echo '<td>' . htmlspecialchars($pt['pt_title'] ? $pt['pt_title'] : '-') . '</td>';
			echo '<td>' . htmlspecialchars($pt['trainer_name'] ? $pt['trainer_name'] : '-') . '</td>';
			echo '<td>' . htmlspecialchars($pt['start_date']) . '</td>';
			echo '<td>' . htmlspecialchars($pt['end_date']) . '</td>';
			echo '<td>' . htmlspecialchars($pt['status']) . '</td>';
			echo '</tr>';
		}
		if (!$has) echo '<tr><td colspan="5" class="text-center text-muted">No PT assigned.</td></tr>';
	} else {
		echo '<tr><td colspan="5" class="text-center text-muted">No PT assigned.</td></tr>';
	}
	echo '</tbody></table></div></div></div>';

	echo '<div class="card"><div class="card-header"><h6 class="mb-0">Classes</h6></div><div class="card-body p-0">';
	echo '<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Class</th><th>Date</th><th>Time</th><th>Status</th></tr></thead><tbody>';
	if ($classRes) {
		$has = false;
		while ($cl = $mysqli->fetch_assoc($classRes)) {
			$has = true;
			echo '<tr>';
			echo '<td>' . htmlspecialchars($cl['title']) . '</td>';
			echo '<td>' . htmlspecialchars($cl['schedule_date']) . '</td>';
			echo '<td>' . htmlspecialchars($cl['start_time'] . '-' . $cl['end_time']) . '</td>';
			echo '<td>' . htmlspecialchars($cl['status']) . '</td>';
			echo '</tr>';
		}
		if (!$has) echo '<tr><td colspan="4" class="text-center text-muted">No classes enrolled.</td></tr>';
	} else {
		echo '<tr><td colspan="4" class="text-center text-muted">No classes enrolled.</td></tr>';
	}
	echo '</tbody></table></div></div></div>';
	exit;
} else if ($tab == 'add_member_payment') {
	$member_row_id = isset($member_row_id) ? (int)$member_row_id : 0;
	$pay_amount = isset($pay_amount) ? (float)$pay_amount : 0;
	$pay_mode = isset($pay_mode) ? $pay_mode : 'Cash';
	if ($member_row_id < 1 || $pay_amount <= 0) {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid payment.";
	} else {
		$m = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "' . $member_row_id . '"');
		if (!$m) {
			$response['msg_code'] = "05";
			$response['msg'] = "Member not found.";
		} else {
			$total = (float)$m['total_amount'];
			if ($total <= 0) {
				// fallback: plan price
				$pd = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "' . $m['plan_id'] . '"');
				$total = $pd ? (float)$pd['price'] : 0;
			}
			$paid = (float)$m['paid_amount'] + $pay_amount;
			if ($paid > $total) $paid = $total;
			$pending = $total - $paid;
			if ($pending < 0) $pending = 0;
			$status = ($pending <= 0) ? 'Paid' : 'Partial';

			// Update member summary
			$mysqli->executeQry("UPDATE " . MEMBERS . " SET total_amount='" . $total . "', paid_amount='" . $paid . "', pending_amount='" . $pending . "', payment_status='" . $status . "', discounted_price='" . $total . "' WHERE id='" . $member_row_id . "' LIMIT 1");

			// Insert ledger row
			$mysqli->executeQry("INSERT INTO " . REVENUE . " SET member_id='" . addslashes($m['member_id']) . "', amount_received='" . $pay_amount . "', total_amount='" . $total . "', pending_amount='" . $pending . "', payment_status='" . $status . "', start_date='" . $m['start_date'] . "', end_date='" . $m['end_date'] . "', received_on='" . date('Y-m-d H:i:s') . "', payment_type='MEMBERSHIP'");

			$response['msg_code'] = "00";
			$response['msg'] = "Payment updated.";
			$record_id = $member_row_id;
		}
	}
} else if ($tab == 'send_whatsapp_single') {
	$member_id = isset($member_id) ? (int)$member_id : 0;
	$message_type = isset($message_type) ? $message_type : 'custom';
	$message_text = isset($message_text) ? trim($message_text) : '';

	if ($member_id < 1 || $message_text == '') {
		$response['msg_code'] = "05";
		$response['msg'] = "Invalid request.";
	} else {
		$member = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "' . $member_id . '"');
		$toMobile = $member && isset($member['mobile']) ? $member['mobile'] : '';
		$name = $member && isset($member['name']) ? $member['name'] : '';

		if (empty($toMobile)) {
			$response['msg_code'] = "05";
			$response['msg'] = "Mobile number not found.";
		} else {
			$ok = $mysqli->sendWhatsAppMessage($toMobile, $message_text);
			$status = $ok ? 'sent' : 'failed';

			$sql_log = "INSERT INTO " . MESSAGE_LOGS . " SET
				member_id = '" . $member_id . "',
				type = 'whatsapp',
				message_type = '" . addslashes($message_type) . "',
				status = '" . $status . "',
				message = '" . addslashes($message_text) . "',
				provider_response = '" . ($ok ? 'ok' : 'not configured / failed') . "',
				created_at = NOW()";
			$mysqli->executeQry($sql_log);

			$response['msg_code'] = "00";
			$response['msg'] = $ok ? "WhatsApp sent to " . $name . "." : "WhatsApp attempt logged (provider not configured).";
			$record_id = $member_id;
		}
	}
} else if ($tab == 'send_whatsapp_bulk') {
	$message_type = isset($message_type) ? $message_type : 'custom';
	$message_text = isset($message_text) ? trim($message_text) : '';

	if ($message_text == '') {
		$response['msg_code'] = "05";
		$response['msg'] = "Message cannot be empty.";
	} else {
		$sql = "SELECT id, mobile, name FROM " . MEMBERS . " 
			WHERE status = 'Active'
			AND (membership_type = 'Single' OR (membership_type = 'Family' AND family_head = '1'))";
		$result = $mysqli->executeQry($sql);

		$total = 0;
		$sent = 0;
		$failed = 0;

		while ($row = $mysqli->fetch_assoc($result)) {
			$total++;
			$toMobile = isset($row['mobile']) ? $row['mobile'] : '';
			if (empty($toMobile)) {
				$failed++;
				continue;
			}
			$ok = $mysqli->sendWhatsAppMessage($toMobile, $message_text);
			$status = $ok ? 'sent' : 'failed';
			if ($ok) $sent++; else $failed++;

			$sql_log = "INSERT INTO " . MESSAGE_LOGS . " SET
				member_id = '" . (int)$row['id'] . "',
				type = 'whatsapp',
				message_type = '" . addslashes($message_type) . "',
				status = '" . $status . "',
				message = '" . addslashes($message_text) . "',
				provider_response = '" . ($ok ? 'ok' : 'not configured / failed') . "',
				created_at = NOW()";
			$mysqli->executeQry($sql_log);
		}

		$response['msg_code'] = "00";
		$response['msg'] = "Bulk WhatsApp done. Total: " . $total . ", Sent: " . $sent . ", Failed: " . $failed . ".";
		$record_id = 0;
	}
} else {
	$response['msg_code'] = "102";
	$response['msg'] = "Option not found";
}
$mysqli->autocommit(true);
$logid = $mysqli->Resquest_Response_log($logid, '', $response, '', $record_id, $log);
echo json_encode($response);
$log = array();
exit;
