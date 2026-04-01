<?php
include 'check_session.php';
 
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
	echo "<script language='javascript' type='text/javascript'>";
    echo "alert('Request not identified as ajax request');";
    echo "</script>";
	$URL="index.php";
	echo "<script>location.href='$URL'</script>";
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	echo "<script language='javascript' type='text/javascript'>";
    echo "alert('Bad Request method');";
    echo "</script>";
	$URL="index.php";
	echo "<script>location.href='$URL'</script>";
}
#print_r($_POST);
extract($_REQUEST);
$table = "";
if ($tab == 'view_members')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	// $page/$disp_rec can arrive as strings from AJAX. Cast defensively to avoid
	// "Unsupported operand types: string - int" (PHP 8+).
	if (isset($page)) {
		$page = (int)$page;
		if ($page < 1) {
			$page = 1;
		}
	} else {
		$page = 1;
	}
	$disp_rec = (int)$disp_rec;
	if ($disp_rec < 1) {
		$disp_rec = 10;
		$per_page = 10;
	}
	if (isset($record_limit)) {
		$per_page = $disp_rec;
	}

	$start    = (($page - 1) * $disp_rec);
	$cur_page = $page;
		
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
	if (!empty($payment_status_filter) || $payment_status_filter != "") {
		$con .= " and payment_status = '" . trim($payment_status_filter) . "'";
	}
	if (!empty($payment_mode_filter) || $payment_mode_filter != "") {
		$con .= " and payment_mode = '" . trim($payment_mode_filter) . "'";
	}
	if (!empty($plan_type) || $plan_type != "") {
		$con .= " and plan_id IN (" . $plan_type . ")";
	}
	if (!empty($freezed) || $freezed != "") {
		$con .= " and is_freezed = '" . trim($freezed) . "'";
	}
	
	 $sql = "select * from ".MEMBERS." where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".MEMBERS." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="single-select form-control wide">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
		
			$table .= '<th><nobr>Action</nobr></th>';
		
		$table .= '<th><nobr>Status</nobr></th><th><nobr>Payment</nobr></th><th><nobr>Membership ID</nobr></th><th><nobr>Name</nobr></th>';		
		//$table .= '<th><nobr>Email</nobr></th>';		
		$table .= '<th><nobr>Mobile</nobr></th>
		<th><nobr>Gender</nobr></th>
		<th><nobr>Plan</nobr></th>
		<th><nobr>Start Date</nobr></th>
		<th><nobr>End Date</nobr></th>
		
		<th><nobr>Payment Mode</nobr></th>';
		if($_SESSION['user_type'] == 'SUPERADMIN'){
		$table .= '<th><nobr>Amount Paid</nobr></th>';
		}
		$table .= '<th><nobr>Created On</nobr></th>
		</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);
					$plan_details = $mysqli->singleRowAssoc_new('*', PLANS, 'id = "'.$plan_id.'"');
					$btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 user-edit-form' data-hover='tooltip' ".$data."  data-id='".$mysqli->encode($id)."'  data-placement='top' title='Click here edit' id='".$id."' ><i class='fa  fa-pencil'></i></span>";
					$inv_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-warning shadow btn-xs sharp me-1' data-hover='tooltip' ".$data." onclick='print_invoice(this.id);' data-id='".$mysqli->encode($id)."'  data-placement='top' title='Click here to print invoice form' id='".$id."' ><i class='fa fa-print'></i></span>";
					//$inv_btn = "&nbsp;<a href='".APPLICATION_URL."backend/invoice/".$invoice."'  download='' style='cursor:pointer;' class='btn btn-warning shadow btn-xs sharp me-1' data-hover='tooltip'   data-placement='top' title='Click here to print invoice' id='".$id."' ><i class='fa fa-print'></i></a>";
					$profile_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-info shadow btn-xs sharp me-1' data-hover='tooltip' data-placement='top' title='View Profile' onclick='view_member_profile(\"" . $rows['id'] . "\");'><i class='fa fa-user'></i></span>";
					$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $rows['id'] . "' onclick='delete_rec(\"" . $rows['id'] . "\")'><i class='fa fa-trash'></i></span>";
					
					$feeze_btn = "&nbsp;<span style='cursor: pointer;' class='btn btn-light shadow btn-xs sharp' data-hover='tooltip' data-placement='top' ".$data." onclick='freeze_popup(\"" . $rows['id'] . "\");' title='Click here to freeze' id='freeze" . $rows['id'] . "' ><i class='fa fa-lock'></i></span>";
					
					$renew_btn = "&nbsp;<span style='cursor: pointer;' class='btn btn-success shadow btn-xs sharp' data-hover='tooltip' data-placement='top' ".$data." onclick='renew_popup(\"" . $rows['id'] . "\");' title='Click here to renew membership' id='renew" . $rows['id'] . "' ><i class='fa fa-refresh' aria-hidden='true'></i></span>";

					$wa_btn = "";
					// Show only for members who are allowed to receive notifications (single / family head).
					if($membership_type == 'Single' || ($membership_type == 'Family' && $family_head == '1')){
						$wa_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-success shadow btn-xs sharp' data-hover='tooltip' data-placement='top' ".$data." onclick='send_whatsapp_member(this);' title='Click here to send WhatsApp'><i class='fa fa-whatsapp' aria-hidden='true'></i></span>";
					}

					

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
								
					$table .= "<td><nobr>";
					$table .= $btn;
					$table .= $profile_btn;
					$table .= $del_btn;
					if($membership_type == 'Single' || $membership_type == 'Family' && $family_head == '1'){
					$table .= $inv_btn;
					}
					if($wa_btn != ''){
						$table .= $wa_btn;
					}
					if($is_freezed != '1'){
					$table .= $feeze_btn;
					}
					if (($membership_type == 'Single' || ($membership_type == 'Family' && $family_head == '1')) && $status == 'Expired') {
					$table .= $renew_btn;
					}
					$table .="</nobr></td>";
					if($status == 'Active'){
					$status = '<span class="badge light badge-success badge-sm">'.$status.'</span>';
					}else{
					$status = '<span class="badge light badge-warning badge-sm">'.$status.'</span>';	
					}
					
					$member_id = '<span class="badge light badge-danger badge-sm">'.$member_id.'</span>';	
					
					$table .= "<td><center><nobr>".$status."</nobr></center></td>";		
					if($payment_status == 'Paid'){
					$payment_status = '<span class="badge light badge-success badge-sm">'.$payment_status.'</span>';
					}else{
					$payment_status = '<span class="badge light badge-warning badge-sm">'.$payment_status.'</span>';	
					}
					if($is_freezed == '1' &&  date('Y-m-d') <= $membership_freezed_till ){
					$freezed = '<br><span class="badge light badge-warning badge-sm">Freezed till '.$membership_freezed_till.'</span>';	
					}else{
						$freezed = '';
					}
					$table .= "<td><center><nobr>".$payment_status."</nobr></center></td>";
					$table .= "<td><center><nobr>".$member_id."</nobr></center></td>";		
					$table .= '<td><nobr><div class="d-flex align-items-center">
						<img src="uploads/profile/'.$picture.'" class="rounded-lg me-2" width="60" alt="">
						<span class="w-space-no">'.$name.' '.$freezed.'
					</div></nobr></td>';
					//$table .= "<td><nobr>".$email."</nobr></td>";
					$table .= "<td><nobr>".$mobile."</nobr></td>";					
					$table .= "<td><nobr>".$gender."</nobr></td>";					
					$table .= "<td><nobr>".$plan_details['title']."</nobr></td>";					
					$table .= "<td><nobr>".$start_date."</nobr></td>";					
					$table .= "<td><nobr>".$end_date."</nobr></td>";	
										
					$table .= "<td><center><nobr>".$payment_mode."</nobr></center></td>";					
					if($_SESSION['user_type'] == 'SUPERADMIN'){
					$table .= "<td><center><nobr>".$discounted_price."</nobr></center></td>";
					}			
					$table .= "<td><nobr>".$mysqli->formatdate($created_on,"j-M-Y h:i:A")."</nobr></td>";							
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}
if ($tab == 'view_recent_visitors')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}
		
	if (!empty($membership_id) || $membership_id != "") {
		$con .= " and member_id = '" . trim($membership_id) . "'";
	}
	if (!empty($att_date) || $att_date != "") {
		$con .= " and attendance_date = '" . trim($att_date) . "'";
	}else{
		$att_date = date('Y-m-d');
	$con .="and attendance_date = '".$att_date."'";
	}
	
	 $sql = "select * from ".ATTENDANCE." where 1 ".$con."  ORDER BY attendance_date DESC, sign_in DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".ATTENDANCE." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
		$table .= '<th><nobr>Membership ID</nobr></th><th><nobr>Name</nobr></th>';			
		$table .= '
		<th><nobr>Date</nobr></th>
		<th><nobr>Sign In</nobr></th>
		<th><nobr>Sign Out</nobr></th>';
		
		$table .= '</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);
					$member_details = $mysqli->singleRowAssoc_new('*', MEMBERS, 'id = "'.$user_id.'"');
					
					

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
					
					
					$member_id = '<span class="badge light badge-success badge-sm">'.$member_details['member_id'].'</span>';	
					$table .= "<td><center><nobr>".$member_id."</nobr></center></td>";		
					$img = (!empty($member_details['picture']) && file_exists('uploads/profile/'.$member_details['picture']))
					? 'uploads/profile/'.$member_details['picture']
					: 'images/def_avtar.png'; // your default avatar
											
					$table .= '
					<td>
						<nobr>
							<div class="d-flex align-items-center">
								<img src="'.$img.'" class="rounded-lg me-2" width="40" alt="">
								<span class="w-space-no">'.$member_details['name'].'</span>
							</div>
						</nobr>
					</td>';				
					$table .= "<td><nobr>".$attendance_date."</nobr></td>";					
					$table .= "<td><nobr>".$mysqli->formatdate($sign_in,"h:i:s A")."</nobr></td>";				
					if($sign_out == '00:00:00'){
						$sign_out = '<span class="badge light badge-danger badge-sm">Not Punched Out</span>';
					}else{
						$sign_out = $mysqli->formatdate($sign_out,"h:i:s A");
					}
					$table .= "<td><nobr>".$sign_out."</nobr></td>";								
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}
if ($tab == 'view_plans')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}
	
	
	$sql = "select * from ".PLANS." where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".PLANS." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
		
			$table .= '<th><nobr>Action</nobr></th>';
		
		$table .= '<th><nobr>Title</nobr></th>';		
		$table .= '<th><nobr>Plan type</nobr></th>';		
		$table .= '<th><nobr>Price</nobr></th>
		<th><nobr>Duration</nobr></th>
		</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);
					$btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1' data-hover='tooltip' ".$data."  data-id='".$mysqli->encode($id)."'  data-placement='top' onclick='edit_plans(\"" . $rows['id'] . "\")' title='Click here edit' id='".$id."' ><i class='fa  fa-pencil'></i></span>";
					
					$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $rows['id'] . "' onclick='delete_rec(\"" . $rows['id'] . "\")'><i class='fa fa-trash'></i></span>";

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
								
					$table .= "<td><nobr>";
					$table .= $btn;
					$table .= $del_btn;
					$table .="</nobr></td>";
					
					$table .= "<td><nobr>".$title."</nobr></td>";
					$table .= "<td><nobr>".$plan_type."</nobr></td>";					
					$table .= "<td><nobr>".$price."</nobr></td>";					
					$table .= "<td><nobr>".$duration."</nobr></td>";						
								
					//$table .= "<td><nobr>".$mysqli->formatdate($created_on,"j-M-Y h:i:A")."</nobr></td>";							
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_employees')
{
	$con = "";
	if (!isset($sort_param))
		$sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}
	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}
	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}

	$sql = "select * from ".EMPLOYEES." where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "select count(id) as count_rows from ".EMPLOYEES." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123);
	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load Employees. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = $num_arr['count_rows'];
	}

	if ($num > 0) {
		$count = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page = $cur_page;
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
			<th><nobr>#</nobr></th>
			<th><nobr>Action</nobr></th>
			<th><nobr>Name</nobr></th>
			<th><nobr>Phone</nobr></th>
			<th><nobr>Designation</nobr></th>
			<th><nobr>Joining Date</nobr></th>
			<th><nobr>Status</nobr></th>
		</tr>
		</thead>
		<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . $value . "' ";
			}

			extract($rows);
			$i = ($start + $n);

			$edit_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 employee-edit-form' data-hover='tooltip' ".$data." data-placement='top' title='Click here edit' id='".$id."' ><i class='fa  fa-pencil'></i></span>";
			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del".$id."' onclick='delete_employee(\"".$id."\")'><i class='fa fa-trash'></i></span>";

			$status_badge = ($status == 'Active')
				? '<span class="badge light badge-success badge-sm">'.$status.'</span>'
				: '<span class="badge light badge-warning badge-sm">'.$status.'</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>".$i."</nobr></td>";
			$table .= "<td><nobr>".$edit_btn.$del_btn."</nobr></td>";
			$table .= "<td><nobr>".$name."</nobr></td>";
			$table .= "<td><nobr>".$phone."</nobr></td>";
			$table .= "<td><nobr>".$designation."</nobr></td>";
			$table .= "<td><nobr>".$mysqli->formatdate($joining_date,"j-M-Y")."</nobr></td>";
			$table .= "<td><center><nobr>".$status_badge."</nobr></center></td>";
			$table .= "</tr>";

			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_pt_plans')
{
	$con = "";
	if (!isset($sort_param))
		$sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}
	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	// Cast defensively to avoid PHP 8+ "string - int" issues.
	if (isset($page)) {
		$page = (int)$page;
		if ($page < 1) $page = 1;
	}
	$disp_rec = (int)$disp_rec;
	if ($disp_rec < 1) {
		$disp_rec = 10;
	}
	if (isset($record_limit)) {
		$disp_rec = (int)$record_limit;
	}
	$per_page = $disp_rec;

	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "select * from " . PT_PLANS . " where 1 " . $con . " order by id DESC limit " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "select count(id) as count_rows from " . PT_PLANS . " where 1 " . $con;
	$result123 = $mysqli->executeQry($sql123);
	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load PT plans. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$count = $num;
		$no_of_paginations = ceil($count / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
					<select name="record_limit_change" id="record_limit_change" class="form-control">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="200">200</option>
					</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
			<thead>
				<tr>
					<th><nobr>#</nobr></th>
					<th><nobr>Action</nobr></th>
					<th><nobr>Title</nobr></th>
					<th><nobr>Total Sessions</nobr></th>
					<th><nobr>Price</nobr></th>
				</tr>
			</thead>
			<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . $value . "' ";
			}

			extract($rows);
			$i = ($start + $n);

			$edit_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 pt_plan-edit-form' data-hover='tooltip' " . $data . " data-placement='top' title='Click here edit' id='" . $id . "' ><i class='fa  fa-pencil'></i></span>";
			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $id . "' onclick='delete_pt_plan(\"" . $id . "\")'><i class='fa fa-trash'></i></span>";

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $edit_btn . $del_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $title . "</nobr></td>";
			$table .= "<td><nobr>" . $total_sessions . "</nobr></td>";
			$table .= "<td><nobr>" . $price . "</nobr></td>";
			$table .= "</tr>";

			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_pt_members')
{
	$con = "";
	// Optional filters from pt_members.php (ajax serialize from #frm_search)
	if (!empty($trainer_id) && $trainer_id != "") {
		$con .= " AND pm.trainer_id = '" . trim($trainer_id) . "'";
	}
	// Receptionist role: no access to PT session listings.
	if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'RECEPTIONIST') {
		$con .= " AND 1=0 ";
	}
	// Trainer role: enforce ownership so trainers only see assigned PT.
	if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'TRAINER') {
		$con .= " AND pm.trainer_id = '" . (int)$_SESSION['user_id'] . "'";
	}
	if (!empty($pt_status) && $pt_status != "") {
		$con .= " AND pm.status = '" . trim($pt_status) . "'";
	}
	if (!empty($from_date) && $from_date != "") {
		$con .= " AND pm.start_date >= '" . trim($from_date) . "'";
	}
	if (!empty($to_date) && $to_date != "") {
		$con .= " AND pm.start_date <= '" . trim($to_date) . "'";
	}
	if (!isset($sort_param))
		$sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}
	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
	}

	if (isset($page)) {
		$page = (int)$page;
		if ($page < 1) $page = 1;
	}
	$disp_rec = (int)$disp_rec;
	if ($disp_rec < 1) $disp_rec = 10;
	if (isset($record_limit)) {
		$disp_rec = (int)$record_limit;
	}
	$per_page = $disp_rec;

	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT pm.id, pm.member_id, m.name AS member_name, pm.trainer_id, e.name AS trainer_name, pm.pt_plan_id, p.title AS plan_title, pm.start_date, pm.total_sessions, pm.sessions_used, pm.end_date, pm.status
			FROM " . PT_MEMBERS . " pm
			JOIN " . PT_PLANS . " p ON p.id = pm.pt_plan_id
			JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
			JOIN " . MEMBERS . " m ON m.member_id = pm.member_id
			WHERE 1 " . $con . "
			ORDER BY pm.id DESC
			LIMIT " . $start . "," . $disp_rec;

	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(pm.id) AS count_rows
			FROM " . PT_MEMBERS . " pm
			JOIN " . PT_PLANS . " p ON p.id = pm.pt_plan_id
			JOIN " . EMPLOYEES . " e ON e.id = pm.trainer_id
			JOIN " . MEMBERS . " m ON m.member_id = pm.member_id
			WHERE 1 " . $con;

	$result123 = $mysqli->executeQry($sql123);
	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load PT sessions. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$count = $num;
		$no_of_paginations = ceil($count / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
					<select name="record_limit_change" id="record_limit_change" class="form-control">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="200">200</option>
					</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
			<thead>
				<tr>
					<th><nobr>#</nobr></th>
					<th><nobr>Action</nobr></th>
					<th><nobr>Member ID</nobr></th>
					<th><nobr>Member</nobr></th>
					<th><nobr>Trainer</nobr></th>
					<th><nobr>PT Plan</nobr></th>
					<th><nobr>Start Date</nobr></th>
					<th><nobr>Sessions</nobr></th>
					<th><nobr>Remaining</nobr></th>
					<th><nobr>Status</nobr></th>
				</tr>
			</thead>
			<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . $value . "' ";
			}

			extract($rows);
			$i = ($start + $n);
			$remaining = (int)$total_sessions - (int)$sessions_used;

			if ($status !== 'Completed' && $remaining > 0) {
				$action_btn = "<span style='cursor:pointer;' class='btn btn-success shadow btn-xs sharp me-1' onclick='mark_pt_session(\"" . $id . "\")' title='Mark next session done'><i class='fa fa-check'></i></span>";
			} else {
				$action_btn = "<span class='badge light badge-success badge-sm'>Completed</span>";
			}

			$status_badge = ($status === 'Completed')
				? '<span class="badge light badge-success badge-sm">Completed</span>'
				: '<span class="badge light badge-warning badge-sm">Active</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $action_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $member_id . "</nobr></td>";
			$table .= "<td><nobr>" . $member_name . "</nobr></td>";
			$table .= "<td><nobr>" . $trainer_name . "</nobr></td>";
			$table .= "<td><nobr>" . $plan_title . "</nobr></td>";
			$table .= "<td><nobr>" . $mysqli->formatdate($start_date, 'j-M-Y') . "</nobr></td>";
			$table .= "<td><nobr>" . (int)$sessions_used . "/" . (int)$total_sessions . "</nobr></td>";
			$table .= "<td><nobr>" . $remaining . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "</tr>";

			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_classes')
{
	$con = "";
	if (!isset($sort_param))
		$sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}
	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
	}
	if (isset($record_limit)) {
		$disp_rec = (int)$record_limit;
	}
	$page = (int)$page;
	$disp_rec = (int)$disp_rec;
	if ($disp_rec < 1) $disp_rec = 10;
	$per_page = $disp_rec;

	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT c.id, c.title, c.capacity, c.status, c.trainer_id AS trainer_id, e.name AS trainer_name
			FROM " . CLASSES . " c
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con . "
			ORDER BY c.id DESC
			LIMIT " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(c.id) AS count_rows
			FROM " . CLASSES . " c
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con;
	$result123 = $mysqli->executeQry($sql123);

	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load Classes. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$count = $num;
		$no_of_paginations = ceil($count / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
			<th><nobr>#</nobr></th>
			<th><nobr>Action</nobr></th>
			<th><nobr>Class</nobr></th>
			<th><nobr>Trainer</nobr></th>
			<th><nobr>Capacity</nobr></th>
			<th><nobr>Status</nobr></th>
		</tr>
		</thead>
		<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . $value . "' ";
			}
			extract($rows);
			$i = ($start + $n);

			$edit_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 class-edit-form' data-hover='tooltip' " . $data . " data-placement='top' title='Click here edit' id='" . $id . "' ><i class='fa  fa-pencil'></i></span>";
			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $id . "' onclick='delete_class(\"" . $id . "\")'><i class='fa fa-trash'></i></span>";

			$status_badge = ($status == 'Active')
				? '<span class="badge light badge-success badge-sm">' . $status . '</span>'
				: '<span class="badge light badge-warning badge-sm">' . $status . '</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $edit_btn . $del_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $title . "</nobr></td>";
			$table .= "<td><nobr>" . $trainer_name . "</nobr></td>";
			$table .= "<td><nobr>" . (int)$capacity . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "</tr>";
			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_class_schedule')
{
	$con = "";
	$page = isset($page) ? (int)$page : 1;
	$disp_rec = isset($disp_rec) && $disp_rec != "" ? (int)$disp_rec : 10;
	if (isset($record_limit)) $disp_rec = (int)$record_limit;
	if ($disp_rec < 1) $disp_rec = 10;
	$per_page = $disp_rec;
	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT s.id, s.class_id, c.title class_title, s.schedule_date, s.start_time, s.end_time, c.capacity, s.status
			FROM " . CLASS_SCHEDULE . " s
			JOIN " . CLASSES . " c ON c.id = s.class_id
			WHERE 1 " . $con . "
			ORDER BY s.id DESC
			LIMIT " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(s.id) AS count_rows
			FROM " . CLASS_SCHEDULE . " s
			JOIN " . CLASSES . " c ON c.id = s.class_id
			WHERE 1 " . $con;
	$result123 = $mysqli->executeQry($sql123);

	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load Class Schedule. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$no_of_paginations = ceil($num / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
			<th><nobr>#</nobr></th>
			<th><nobr>Action</nobr></th>
			<th><nobr>Class</nobr></th>
			<th><nobr>Date</nobr></th>
			<th><nobr>Time</nobr></th>
			<th><nobr>Capacity</nobr></th>
			<th><nobr>Status</nobr></th>
		</tr>
		</thead>
		<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . $value . "' ";
			}
			extract($rows);
			$i = ($start + $n);

			$edit_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 schedule-edit-form' data-hover='tooltip' " . $data . " data-placement='top' title='Click here edit' id='" . $id . "' ><i class='fa  fa-pencil'></i></span>";
			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $id . "' onclick='delete_schedule(\"" . $id . "\")'><i class='fa fa-trash'></i></span>";

			$status_badge = ($status == 'Active')
				? '<span class="badge light badge-success badge-sm">' . $status . '</span>'
				: '<span class="badge light badge-warning badge-sm">' . $status . '</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $edit_btn . $del_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $class_title . "</nobr></td>";
			$table .= "<td><nobr>" . $schedule_date . "</nobr></td>";
			$table .= "<td><nobr>" . $start_time . " - " . $end_time . "</nobr></td>";
			$table .= "<td><nobr>" . (int)$capacity . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "</tr>";
			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_class_members')
{
	$con = "";
	// Optional filters from class_members.php (ajax serialize from #frm_search)
	if (!empty($trainer_id) && $trainer_id != "") {
		$con .= " AND e.id = '" . trim($trainer_id) . "'";
	}
	// Trainer role: enforce ownership so trainers only see their class enrollments.
	if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'TRAINER') {
		$con .= " AND e.id = '" . (int)$_SESSION['user_id'] . "'";
	}
	if (!empty($class_status) && $class_status != "") {
		$con .= " AND cm.status = '" . trim($class_status) . "'";
	}
	if (!empty($from_date) && $from_date != "") {
		$con .= " AND s.schedule_date >= '" . trim($from_date) . "'";
	}
	if (!empty($to_date) && $to_date != "") {
		$con .= " AND s.schedule_date <= '" . trim($to_date) . "'";
	}
	// Receptionist role: no access to class enrollments.
	if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'RECEPTIONIST') {
		$con .= " AND 1=0 ";
	}
	$page = isset($page) ? (int)$page : 1;
	$disp_rec = isset($disp_rec) && $disp_rec != "" ? (int)$disp_rec : 10;
	if (isset($record_limit)) $disp_rec = (int)$record_limit;
	if ($disp_rec < 1) $disp_rec = 10;
	$per_page = $disp_rec;
	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT cm.id, cm.class_schedule_id, s.class_id, s.schedule_date, s.start_time, s.end_time,
				 m.id AS member_db_id, m.member_id AS member_membership_id, m.name AS member_name, cm.status
			FROM " . CLASS_MEMBERS . " cm
			JOIN " . CLASS_SCHEDULE . " s ON s.id = cm.class_schedule_id
			JOIN " . MEMBERS . " m ON m.id = cm.member_id
			JOIN " . CLASSES . " c ON c.id = s.class_id
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con . "
			ORDER BY cm.id DESC
			LIMIT " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(cm.id) AS count_rows
			FROM " . CLASS_MEMBERS . " cm
			JOIN " . CLASS_SCHEDULE . " s ON s.id = cm.class_schedule_id
			JOIN " . MEMBERS . " m ON m.id = cm.member_id
			JOIN " . CLASSES . " c ON c.id = s.class_id
			JOIN " . EMPLOYEES . " e ON e.id = c.trainer_id
			WHERE 1 " . $con;
	$result123 = $mysqli->executeQry($sql123);

	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load Class Enrollments. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$no_of_paginations = ceil($num / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
			<th><nobr>#</nobr></th>
			<th><nobr>Action</nobr></th>
			<th><nobr>Member</nobr></th>
			<th><nobr>Membership ID</nobr></th>
			<th><nobr>Date</nobr></th>
			<th><nobr>Time</nobr></th>
			<th><nobr>Status</nobr></th>
		</tr>
		</thead>
		<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			extract($rows);
			$i = ($start + $n);

			$status_badge = ($status == 'Enrolled')
				? '<span class="badge light badge-success badge-sm">' . $status . '</span>'
				: '<span class="badge light badge-warning badge-sm">' . $status . '</span>';

			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Remove' id='del" . $id . "' onclick='remove_enrollment(\"" . $id . "\")'><i class='fa fa-trash'></i></span>";

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $del_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $member_name . "</nobr></td>";
			$table .= "<td><nobr>" . $member_membership_id . "</nobr></td>";
			$table .= "<td><nobr>" . $schedule_date . "</nobr></td>";
			$table .= "<td><nobr>" . $start_time . " - " . $end_time . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "</tr>";
			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_message_logs')
{
	$page = isset($page) ? (int)$page : 1;
	if ($page < 1) $page = 1;

	$disp_rec = isset($disp_rec) && $disp_rec != "" ? (int)$disp_rec : 10;
	if (isset($record_limit)) $disp_rec = (int)$record_limit;
	if ($disp_rec < 1) $disp_rec = 10;

	$per_page = $disp_rec;
	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT ml.id, ml.member_id, m.name AS member_name, ml.type, ml.message_type, ml.status, ml.created_at
			FROM " . MESSAGE_LOGS . " ml
			LEFT JOIN " . MEMBERS . " m ON m.id = ml.member_id
			WHERE 1
			ORDER BY ml.id DESC
			LIMIT " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(ml.id) AS count_rows
			FROM " . MESSAGE_LOGS . " ml
			LEFT JOIN " . MEMBERS . " m ON m.id = ml.member_id
			WHERE 1";
	$result123 = $mysqli->executeQry($sql123);

	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load message logs. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$no_of_paginations = ceil($num / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
					<select name="record_limit_change" id="record_limit_change" class="form-control">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="200">200</option>
					</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
			<thead>
				<tr>
					<th><nobr>#</nobr></th>
					<th><nobr>Member</nobr></th>
					<th><nobr>Type</nobr></th>
					<th><nobr>Message Type</nobr></th>
					<th><nobr>Status</nobr></th>
					<th><nobr>Created At</nobr></th>
				</tr>
			</thead>
			<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			extract($rows);
			$i = ($start + $n);

			$status_badge = ($status == 'sent')
				? '<span class="badge light badge-success badge-sm">sent</span>'
				: '<span class="badge light badge-warning badge-sm">' . $status . '</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $member_name . "</nobr></td>";
			$table .= "<td><nobr>" . $type . "</nobr></td>";
			$table .= "<td><nobr>" . $message_type . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "<td><nobr>" . $mysqli->formatdate($created_at, 'j-M-Y h:i:A') . "</nobr></td>";
			$table .= "</tr>";

			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_membership_types')
{
	$page = isset($page) ? (int)$page : 1;
	if ($page < 1) $page = 1;

	$disp_rec = isset($disp_rec) && $disp_rec != "" ? (int)$disp_rec : 10;
	if (isset($record_limit)) $disp_rec = (int)$record_limit;
	if ($disp_rec < 1) $disp_rec = 10;

	$per_page = $disp_rec;
	$start = (($page - 1) * $disp_rec);
	$cur_page = $page;

	$sql = "SELECT * FROM " . MEMBERSHIP_TYPES . " ORDER BY id DESC LIMIT " . $start . "," . $disp_rec;
	$result = $mysqli->executeQry($sql);

	$sql123 = "SELECT COUNT(id) AS count_rows FROM " . MEMBERSHIP_TYPES;
	$result123 = $mysqli->executeQry($sql123);

	$num = 0;
	if ($result === false || $result123 === false) {
		$err = $mysqli->error();
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> Unable to load membership types. ' . htmlspecialchars($err) . '</div></div>';
	} else {
		$num_arr = $mysqli->fetch_array($result123);
		$num = (int)$num_arr['count_rows'];
	}

	if ($num > 0) {
		$no_of_paginations = ceil($num / $per_page);
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;

		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>
					<select name="record_limit_change" id="record_limit_change" class="form-control">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="200">200</option>
					</select>
				</div>
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>
					<input type="text" id="live_search" class="form-control" />
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="' . $num . '">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
			<thead>
				<tr>
					<th><nobr>#</nobr></th>
					<th><nobr>Action</nobr></th>
					<th><nobr>Name</nobr></th>
					<th><nobr>Status</nobr></th>
					<th><nobr>Created</nobr></th>
				</tr>
			</thead>
			<tbody id="tbody">';

		$n = 1;
		while ($rows = $mysqli->fetch_assoc($result)) {
			$data = "";
			foreach ($rows as $key => $value) {
				$data .= "data-" . $key . "='" . htmlspecialchars($value) . "' ";
			}
			extract($rows);
			$i = ($start + $n);

			$edit_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1 membership-type-edit-form' data-hover='tooltip' " . $data . " data-placement='top' title='Edit' id='" . $id . "' ><i class='fa fa-pencil'></i></span>";

			$toggle_to = ($status == 'Active') ? 'Inactive' : 'Active';
			$toggle_btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-secondary shadow btn-xs sharp me-1' data-hover='tooltip' title='Set " . $toggle_to . "' onclick='toggle_membership_type(\"" . $id . "\",\"" . $toggle_to . "\")'><i class='fa fa-toggle-on'></i></span>";

			$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Delete' onclick='delete_membership_type(\"" . $id . "\")'><i class='fa fa-trash'></i></span>";

			$status_badge = ($status == 'Active')
				? '<span class="badge light badge-success badge-sm">Active</span>'
				: '<span class="badge light badge-warning badge-sm">Inactive</span>';

			$table .= "<tr>";
			$table .= "<td><nobr>" . $i . "</nobr></td>";
			$table .= "<td><nobr>" . $edit_btn . $toggle_btn . $del_btn . "</nobr></td>";
			$table .= "<td><nobr>" . $name . "</nobr></td>";
			$table .= "<td><center><nobr>" . $status_badge . "</nobr></center></td>";
			$table .= "<td><nobr>" . $mysqli->formatdate($created_at, 'j-M-Y h:i:A') . "</nobr></td>";
			$table .= "</tr>";
			$n++;
		}

		$table .= '</tbody></table></div>';
		$table .= '<div style="background-color:#fff;" class="card-footer">';
		$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
		$table .= '</div>';
	} else {
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}

if ($tab == 'view_enquiries')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}
	
	
	$sql = "select * from ".ENQUIRIES." where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".ENQUIRIES." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
		
		$table .= '<th><nobr>Name</nobr></th>';		
		$table .= '<th><nobr>Email</nobr></th>';		
		$table .= '<th><nobr>Phone</nobr></th>
		<th><nobr>Message</nobr></th>
		<th><nobr>Enquired Received On</nobr></th>
		</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);
					$btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1' data-hover='tooltip' ".$data."  data-id='".$mysqli->encode($id)."'  data-placement='top' onclick='edit_plans(\"" . $rows['id'] . "\")' title='Click here edit' id='".$id."' ><i class='fa  fa-pencil'></i></span>";
					
					$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $rows['id'] . "' onclick='delete_rec(\"" . $rows['id'] . "\")'><i class='fa fa-trash'></i></span>";

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
								
					/* $table .= "<td><nobr>";
					$table .= $btn;
					$table .= $del_btn;
					$table .="</nobr></td>"; */
					
					$table .= "<td><nobr>".$name."</nobr></td>";
					$table .= "<td><nobr>".$email."</nobr></td>";					
					$table .= "<td><nobr>".$phone."</nobr></td>";					
					$table .= "<td><nobr>".$message."</nobr></td>";						
								
					$table .= "<td><nobr>".$mysqli->formatdate($recTimestamp,"j-M-Y h:i:A")."</nobr></td>";							
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}if ($tab == 'view_feedbacks')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}
	
	
	$sql = "select * from tbl_feedback where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from tbl_feedback where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
		
		$table .= '<th><nobr>Name</nobr></th>';			
		$table .= '
		<th><nobr>Feedback</nobr></th>
		<th><nobr>Feedback Received On</nobr></th>
		</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);
					$btn = "&nbsp;<span style='cursor:pointer;' class='btn btn-primary shadow btn-xs sharp me-1' data-hover='tooltip' ".$data."  data-id='".$mysqli->encode($id)."'  data-placement='top' onclick='edit_plans(\"" . $rows['id'] . "\")' title='Click here edit' id='".$id."' ><i class='fa  fa-pencil'></i></span>";
					
					$del_btn = "<span style='cursor: pointer;' class='btn btn-danger shadow btn-xs sharp' data-hover='tooltip' data-placement='top' title='Click here delete' id='del" . $rows['id'] . "' onclick='delete_rec(\"" . $rows['id'] . "\")'><i class='fa fa-trash'></i></span>";

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
								
					/* $table .= "<td><nobr>";
					$table .= $btn;
					$table .= $del_btn;
					$table .="</nobr></td>"; */
					
					$table .= "<td><nobr>".$name."</nobr></td>";				
					$table .= "<td><nobr>".$message."</nobr></td>";						
								
					$table .= "<td><nobr>".$mysqli->formatdate($recTimestamp,"j-M-Y h:i:A")."</nobr></td>";							
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}if ($tab == 'view_sent_notifications')
{
	$con = "";
	if (!isset($sort_param))
    $sort_param = "id";

	if (!isset($page) || $page < 1) {
		$page = 1;
	}

	if (!isset($disp_rec) || $disp_rec == "") {
		$disp_rec = 10;
		$per_page = 10;
	}

	if (isset($record_limit)) {
		$disp_rec = $record_limit;
		$per_page = $disp_rec;
	}

	if (isset($page)) {
		$start    = (($page - 1) * $disp_rec);
		$cur_page = $page;
	} else {
		$page  = 1;
		$start = 1;
	}
	
	
	$sql = "select * from ".NOTIFICATIONS." where 1 ".$con." order by id DESC limit ".$start.",".$disp_rec;
	$result = $mysqli->executeQry($sql) ; 
	 
	
	$sql123 = "select count(id) as count_rows from ".NOTIFICATIONS." where 1 ".$con;
	$result123 = $mysqli->executeQry($sql123) ; 
	$num_arr = $mysqli->fetch_array($result123);
	$num = $num_arr['count_rows'];
	$row = "";	 
	
	if($num > 0)
	{						
		$count             = $num;
		$no_of_paginations = ceil($count / $per_page);
		$cur_page          = $cur_page;
		$previous_btn      = true;
		$next_btn          = true;
		$first_btn         = true;
		$last_btn          = true;
	      
		$table = '
		<div class="card-body"><div class="row">
			<div class="col-md-3">
				<div class="form-group pull-left">
					<label for="input" class="control-label">Record Limit:<span></span></label>					
						<select name="record_limit_change" id="record_limit_change" class="form-control">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="120">120</option>
							<option value="150">150</option>
							<option value="200">200</option>
						</select>			
					
				</div>
			</div>
			<div class="col-md-6"></div>

			<div class="col-md-3">
				<div class="form-group pull-right">
					<label for="input" class="control-label">Search:<span></span></label>					
					<input type="text" id="live_search" class="form-control" />				
				</div>
			</div>
		</div>
		<input type="hidden" id="total_records" value="'.$num.'">
		<div style="margin: 2px; float: left;"></div>
		<table id="dynamic_table" class="table table-bordered table-hover table-responsive-sm">
		<thead>
		<tr>
		<th><nobr>#</nobr></th>';
			
		$table .= '<th><nobr>Email</nobr></th>	
		<th><nobr>Sent On</nobr></th>
		</tr>
		</thead>	
		<tbody id="tbody">';
			if($num>0)
			{
				$n = 1;
				$data = "";
				while($rows = $mysqli->fetch_assoc($result))
				{	 									
					$country_code = '';
					$i = ($start + $n);
				
				
					foreach($rows as $key => $value)
					{
						$data .= "data-".$key."='".$value."' ";											
					}
								
					extract($rows);

					$table .= "<tr>";
					$table .= "<td><nobr>".$i."</nobr></td>";
								
					$table .= "<td><nobr>".$email."</nobr></td>";
					$table .= "<td><nobr>".$mysqli->formatdate($sent_on,"j-M-Y h:i:A")."</nobr></td>";							
					$n++;
					$data = "";
				}
			}
			$table .= '</tbody></table></div>';	
			$table .= '<div style="background-color:#fff;" class="card-footer">';				
			$table .= $mysqli->custompaging_table_response($cur_page, $no_of_paginations, $previous_btn, $next_btn, $first_btn, $last_btn);
			$table .= '</div>';	
	}

	else
	{
		$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
	}
}




echo $table ;


	