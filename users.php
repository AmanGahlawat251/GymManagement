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
	<!--**********************************  Sidebar Start  ***********************************-->
	<title>Members | <?php echo APPLICATION_NAME; ?> </title>
	<?php require_once("includes/sidebar.php"); ?>
	<!--**********************************  Sidebar End  ***********************************-->
	<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
	<style>
		.card-header .collapsed .fas {
			transform: rotate(180deg);
		}

		.select2-container {
			z-index: 100000;
		}
		
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #resetButton {
            
            display: none;
        } 
	.form-control:disabled, .form-control[readonly] {
    background: #efeaea !important;
    opacity: 1 !important;
}
	</style>

	<!--**********************************  Content body start  ***********************************-->
	<div class="content-body">
		<!-- row -->
		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:void(0);">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						Dashboard </a>
				</li>
				<li class="breadcrumb-item active"><a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
							<path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
						</svg>
						Members</a></li>
			</ol>
			<!--<a class="text-primary fs-13" data-bs-toggle="offcanvas" href="#offcanvasExample1" role="button" aria-controls="offcanvasExample1">+ Add Task</a>-->
		</div>
		<div class="container-fluid">

			<div class="col-xl-12">


				<div class="filter cm-content-box box-primary">
					<div class="content-title">
						<div class="cpa">
							<i class="fa-sharp fa-solid fa-filter me-2"></i>Filter
						</div>
						<!--<div class="tools">
							<a href="javascript:void(0);" class="SlideToolHeader"><i class="fal fa-angle-down"></i></a>
						</div>-->
					</div>
					<div class="cm-content-body form excerpt">
						<div class="card-body">
							<form onsubmit="return false;" id="frm_search" method="post">
								
								<div class="row">



									<div class="col-md-2">
										<label class="form-label">Search by membership ID</label>
										<input type="text" class="form-control  " maxlength="150" id="membership_id" name="membership_id" placeholder="" />
									</div>
									<div class="col-md-2">
										<label class="form-label">Search by name</label>
										<input type="text" class="form-control allowAlphaNumericSpace dataTables_filter" maxlength="150" id="search_user_name" name="search_user_name" placeholder="" />
									</div>

									<div class="col-md-2">
										<label class="form-label">Search by email</label>
										<input type="email" class="form-control validateEmail" data-regex="[^a-zA-Z0-9-.,_@/]" id="search_user_email" maxlength="150" name="search_user_email" placeholder="" />
									</div>

									<div class="col-md-2">
										<label class="form-label">Search by mobile</label>
										<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="search_user_contact" name="search_user_contact" />
									</div>
									<div class="col-md-2">
										<label class="form-label">Status</label>
										<select id="search_status" name="search_status" class="default-select form-control wide">
											<option value="">--Please select--</option>
											<option value="Active">Active</option>
											<option value="Expired">Expired</option>
										</select>
									</div>
									<div class="col-md-2">
										<label class="form-label">Payment Status</label>
										<select id="payment_status_filter" name="payment_status_filter" class="default-select form-control wide">
											<option value="">--Please select--</option>
											<option value="Paid">Paid</option>
											<option value="Partial">Partial</option>
											<option value="Pending">Pending</option>
										</select>
									</div>
									<div class="col-md-2">
										<label class="form-label">Payment Mode</label>
										<select id="payment_mode_filter" name="payment_mode_filter" class="default-select form-control wide">
											<option value="">--Please select--</option>
											<option value="Cash">Cash</option>
											<option value="UPI">UPI</option>
										</select>
									</div>
									<div class="col-md-2">
										<label class="form-label">Plan Type</label>
										<select id="plan_type" name="plan_type" class="default-select form-control wide">
											<option value="">--Please select--</option>
											<option value="2,4,6,8,10,12">With Coaching</option>
											<option value="1,3,5,7,9,11">Without Coaching</option>
										</select>
									</div>
									
									<div class="col-md-2">
										<label class="form-label">Freezed</label>
										<select id="freezed" name="freezed" class="default-select form-control wide">
											<option value="">--Please select--</option>
											<option value="1">Freezed</option>
											<option value="0">Not Freezed</option>
										</select>
									</div>

									<div class="col-md-2">
											<button style="margin-top: 30px;" id="search" type="submit" class="btn btn-sm btn-primary">Search</button>
											<button style="margin-top: 30px; margin-left:5px;" type="reset" onclick="window.location.reload();" class="btn btn-sm  btn-primary">Reset</button>
										
									</div>

								</div>
								<input type='hidden' name='tab' value="<?php echo 'view_members'; ?>" />					
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
						<h4 class="heading mb-0">Members List</h4>
						<ul class="nav nav-tabs dzm-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a href="javascript:void(0);" onclick="export_members()" class="btn btn-success text-white btn-sm" style="margin-right:10px;"><i class="fa fa-download"></i></a>
							</li><li class="nav-item" role="presentation">
								<a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#canvas_user" role="button" aria-controls="canvas_user">+ Add Members</a>
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
	<!--**********************************  Content body end  ***********************************-->


	<!--**********************************  Start add users  ***********************************-->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff"  id="canvas_user">
		<div class="offcanvas-header">
			<h5 class="modal-title" id="canvas_user_title">Add Member</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" onclick="window.location.reload();" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div class="container-fluid">

				<form onsubmit="return false;" id="frm_user" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xl-6 mb-3">
							<label class="form-label">Membership Type<span class="text-danger">*</span></label>
							<select id="membership_type_id" name="membership_type_id" class="single-select form-control wide" required>
								<?php
								$mt_q = $mysqli->executeQry("SELECT id, name FROM " . MEMBERSHIP_TYPES . " WHERE status='Active' ORDER BY name ASC");
								while ($mt = $mysqli->fetch_assoc($mt_q)) { ?>
									<option value="<?php echo $mt['id']; ?>"><?php echo $mt['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<!--<div class="col-xl-4 col-xxl-6 col-6 mb-3"><label class="form-check-label" for="sportsperson">Is Sports person?</label>
										<div class="form-check custom-checkbox mb-3 checkbox-success">
											<input type="checkbox" class="form-check-input" name="sportsperson" id="sportsperson" value="">
											
										</div>
						</div>-->
						<?php /* Family group removed; membership types handle it */ ?>
						<div class="col-xl-6 mb-3">
							<label for="plans" class="form-label">Membership Plans<span class="text-danger">*</span></label>
							<select id="plans" class="form-control single-select" onchange="getBase(this.value);" name="plans" required>
								
							</select>
						</div>
						<div class="col-xl-6 mb-3">
							<label for="user_name" class="form-label">Name<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowAlphaNumericSpace" maxlength="150" id="name" name="user_name" placeholder="" required />
						</div>
						<div class="col-xl-6 mb-3">
							<label for="user_email" class="form-label">Email<span class="text-danger">*</span></label>
							<input type="email" class="form-control validateEmail" data-regex="[^a-zA-Z0-9-.,_@/]" id="email" maxlength="150" name="user_email" placeholder="" required />
						</div>
						<div class="col-xl-6 mb-3">
							<label for="user_contact" class="form-label">Mobile<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="mobile" name="user_contact" required />
						</div>

						<div class="col-xl-6 mb-3">
							<label class="form-label">Gender<span class="text-danger">*</span></label>
							<select id="gender" name="gender" class="single-select form-control wide" required>
								<option value="">--Please select--</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
						<div class="col-xl-6 mb-3">
							<label for="user_address" class="form-label">Address<span class="text-danger">*</span></label>
							<input type="text" class="form-control removeChars" data-regex="[^a-zA-Z0-9-.,_@ /]" id="address" maxlength="150" name="user_address" required />
						</div>

						<div class="col-xl-6 mb-3">
							<label for="dob" class="form-label">Age<span class="text-danger">*</span></label>
							<input type="number"  class="form-control" id="age" name="age" required />
						</div>

						<div class="col-xl-6 mb-3">
							<label for="dob" class="form-label">Date of Birth</label>
							<input type="date" class="form-control" id="dob" name="dob" />
						</div>

						<div class="col-xl-6 mb-3">
							<label for="user_doj" class="form-label">Date of Joining<span class="text-danger">*</span></label>
							<input type="date" name="user_doj" id="joining_date" class="form-control" required  value="<?php echo date('Y-m-d');?>" required />
						</div>
						<div class="col-xl-6 mb-3">
							<label for="end_date" class="form-label">End Date</label>
							<input type="date" name="end_date" id="end_date" class="form-control" readonly />
						</div>
						<div class="col-xl-6 mb-3">
							<label class="form-label">Mode of Payement<span class="text-danger">*</span></label>
							<select id="payment_mode" class="single-select form-control wide" name="mode" required>
								<option value="Cash">Cash</option>
								<option value="UPI">UPI</option>
								
							</select>
						</div>
						<div class="col-xl-6 mb-3">
							<label for="paid" class="form-label">Plan Price (reference)</label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="base"  readonly />
						</div>
						<div class="col-xl-6 mb-3">
							<label for="final_amount_member" class="form-label">Final Amount<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="final_amount_member" name="final_amount_member" required />
							<small class="text-muted d-block mt-1">Enter final deal amount agreed with customer</small>
						</div>
						<div class="col-xl-6 mb-3">
							<label for="paid" class="form-label">Paid Amount<span class="text-danger">*</span></label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="paid_amount_member" name="paid" required />
						</div>
						<div class="col-xl-6 mb-3">
							<label class="form-label">Pending Amount</label>
							<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pending_amount" name="pending_amount" readonly />
						</div>
						<input type="hidden" id="total_amount" name="total_amount" value="0" />

						<div class="col-xl-6 mb-3">
							<label class="form-label">ID Proof (optional)</label>
							<input class="form-control" accept=".pdf,.jpg,.jpeg,.png" id="id_proof_file" name="id_proof_file" type="file">
						</div>

						<div class="col-xl-12 mb-2">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" id="enroll_pt" name="enroll_pt" value="1">
								<label class="form-check-label" for="enroll_pt">Enroll in PT (optional)</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" id="enroll_class" name="enroll_class" value="1">
								<label class="form-check-label" for="enroll_class">Enroll in Class (optional)</label>
							</div>
						</div>

						<div class="row" id="pt_block" style="display:none;">
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Plan<span class="text-danger">*</span></label>
								<select id="pt_plan_id" name="pt_plan_id" class="single-select form-control wide">
									<option value="">--Select PT Plan--</option>
									<?php
									$pt_q = $mysqli->executeQry("SELECT id, title FROM " . PT_PLANS . " ORDER BY id DESC");
									while ($pt = $mysqli->fetch_assoc($pt_q)) { ?>
										<option value="<?php echo $pt['id']; ?>"><?php echo $pt['title']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">Trainer<span class="text-danger">*</span></label>
								<select id="pt_trainer_id" name="pt_trainer_id" class="single-select form-control wide">
									<option value="">--Select Trainer--</option>
									<?php
									$tr_q = $mysqli->executeQry("SELECT id, name FROM " . EMPLOYEES . " WHERE designation='trainer' AND status='Active' ORDER BY name ASC");
									while ($tr = $mysqli->fetch_assoc($tr_q)) { ?>
										<option value="<?php echo $tr['id']; ?>"><?php echo $tr['name']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Plan Price</label>
								<input type="text" class="form-control allowOnlyNumeric" id="pt_plan_price" readonly />
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Final Amount<span class="text-danger">*</span></label>
								<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pt_total_amount" name="pt_total_amount" />
								<small class="text-muted d-block mt-1">Enter final PT deal amount agreed with customer</small>
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Paid Amount<span class="text-danger">*</span></label>
								<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pt_paid_amount" name="pt_paid_amount" />
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Pending Amount</label>
								<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pt_pending_amount" name="pt_pending_amount" readonly />
							</div>
							<div class="col-xl-6 mb-3">
								<label class="form-label">PT Payment Mode<span class="text-danger">*</span></label>
								<select id="pt_payment_mode" class="single-select form-control wide" name="pt_payment_mode">
									<option value="Cash">Cash</option>
									<option value="UPI">UPI</option>
								</select>
							</div>
						</div>

						<div class="row" id="class_block" style="display:none;">
							<div class="col-xl-12 mb-3">
								<label class="form-label">Class Schedule<span class="text-danger">*</span></label>
								<select id="class_schedule_id" name="class_schedule_id" class="single-select form-control wide">
									<option value="">--Select Schedule--</option>
									<?php
									$cs_q = $mysqli->executeQry("SELECT cs.id, c.title, cs.schedule_date, cs.start_time, cs.end_time
										FROM " . CLASS_SCHEDULE . " cs
										INNER JOIN " . CLASSES . " c ON c.id = cs.class_id
										WHERE cs.status='Active' AND c.status='Active'
										ORDER BY cs.schedule_date DESC, cs.start_time ASC");
									while ($cs = $mysqli->fetch_assoc($cs_q)) { ?>
										<option value="<?php echo $cs['id']; ?>">
											<?php echo $cs['title'] . " | " . $cs['schedule_date'] . " (" . $cs['start_time'] . "-" . $cs['end_time'] . ")"; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-xl-3 mb-3">
							<label>Profile Picture</label>
									 <!-- Input element -->
									<div id="cameraFrame" class="text-center" >
										<video id="video" autoplay="" playsinline=""></video>
									</div>
									<div id="capturedImageContainer" class="text-center" style="display:none;">
										<img id="capturedImage" class="img-fluid">
										<input type="hidden" name="image" id="image" class="image-tag">
									</div>            
									<a class="btn btn-primary btn-sm" href="javascript:void(0);" id="captureButton" onclick="captureImage()">Capture</a>
									<a style="display:none;" href="javascript:void(0);" onclick="resetCapture()" id="reset-btn" class="btn btn-primary btn-sm mt-2">Reset</a>
						</div>
						<div class="col-xl-6 mb-3">
							
						</div>
						<input type='hidden' name='tab' value="<?php echo 'add_members'; ?>" />
						<input type='hidden' name='picture' id='picture' />
						<input type='hidden' name='edit_id' id='id' />
						<input type="hidden" name="family_head" id="family_head" value="0" />
						<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>		
						<div id="final_btns" style="display:none;">
							<button type="submit" class="btn btn-primary me-1">Submit</button>
							<button type="button" data-bs-dismiss="offcanvas" class="btn btn-danger light ms-1">Close</button>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
	<!--**********************************  End add users  ***********************************-->
	<!-- Member Profile Offcanvas -->
	<div data-bs-backdrop="static" class="offcanvas offcanvas-end customeoff" id="canvas_member_profile">
		<div class="offcanvas-header">
			<h5 class="modal-title">Member Profile</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="offcanvas-body">
			<div id="member_profile_body">
				<div class="text-center text-muted">Loading...</div>
			</div>
		</div>
	</div>

	<!-- Update Payment Modal -->
	<div class="modal fade" id="modal_update_payment">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Payment</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form method="POST" onsubmit="return false;" id="frm_update_payment" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="row">
							<div class="col-xl-12 mb-3">
								<label class="form-label">Pay Amount<span class="text-danger">*</span></label>
								<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="pay_amount" name="pay_amount" required />
							</div>
							<div class="col-xl-12 mb-3">
								<label class="form-label">Mode<span class="text-danger">*</span></label>
								<select id="pay_mode" class="single-select form-control wide" name="pay_mode" required>
									<option value="Cash">Cash</option>
									<option value="UPI">UPI</option>
								</select>
							</div>
						</div>
						<input type="hidden" name="tab" value="<?php echo 'add_member_payment'; ?>" />
						<input type="hidden" name="member_row_id" id="member_row_id" value="" />
						<input type="hidden" name="url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php /* Family ID modal removed (membership type flow) */ ?>
	<!-- <div class="modal fade" id="modal_generate_id">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Generate Family ID</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
												<form id="frm_family">
                                                <div class="modal-body">
													<div class="col-xl-12 mb-3">
														<label for="family_title" class="form-label">Family title<span class="text-danger">*</span></label>
														<input type="text" class="form-control allowAlphaNumericSpacenew" maxlength="150" id="family_title" name="family_title" placeholder="" required />
													</div>
												</div>
												
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
												</form>
                                            </div>
                                        </div>
                                    </div>		 -->
									
									<?php /* Declaration upload removed for gym version */ ?>
									<div class="modal fade" id="freeze_popup">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Freeze Membership</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
												<form method="POST" onsubmit="return false;" id="freeze_member">
                                                <div class="modal-body">
													<div class="col-xl-12 mb-3">
														<div class="mb-3">
														  <label for="dec_file" class="form-label">Freeze membership (In Days)<span class="text-danger"style="font-size: 10px;"> Only 15 Days are allowed to freeze membership</span></label>
														  <input type="number"  class="form-control allowOnlyNumeric" id="freeze" name="freeze"  required />
														</div>
													</div>
												</div>
												<input type='hidden' name='tab' value="<?php echo 'freeze_membership'; ?>" />
												<input type='hidden' name='edit_id' id='edit_id' value=""/>
												<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>		
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                </div>
												</form>
                                            </div>
                                        </div>
                                    </div>	
									<div class="modal fade" id="renew_popup">
                                        <div class="modal-dialog  modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="renewHead"></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
												<form method="POST" onsubmit="return false;" id="renew_member">
                                                <div class="modal-body">
												<div class="row">
													<div class="col-xl-3 mb-3">
														<label for="user_doj" class="form-label">Date of Renewal<span class="text-danger">*</span></label>
														<input type="date" name="user_doj" id="user_doj" class="form-control" required  value="<?php echo date('Y-m-d');?>" required />
													</div>
													<div class="col-xl-3 mb-3">
														<label for="plans" class="form-label">Membership Plans<span class="text-danger">*</span></label>
														<select id="renew_plans" class="form-control" onchange="getRenewBase(this.value);" name="renew_plans" required>
															
														</select>
													</div>
													<div class="col-xl-3 mb-3">
														<label for="paid" class="form-label">Base Price</label>
														<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="base_price"  readonly />
													</div>
													<div class="col-xl-3 mb-3">
														<label for="paid" class="form-label">Amount Paid<span class="text-danger">*</span></label>
														<input type="text" class="form-control allowOnlyNumeric" maxlength="10" id="paid_amount" name="paid" required />
													</div>
												</div>
												</div>
												<input type='hidden' name='tab' value="<?php echo 'renew_membership'; ?>" />
												<input type='hidden' name='member_id' id='member_id' value=""/>
												<input type='hidden' name='edit_id' id='edit_id' value=""/>
												<input type="hidden" name="url" id="notes_url" value="<?php echo "index.php?".$mysqli->encode("stat=ajax"); ?>" required>		
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Renew Membership</button>
                                                </div>
												</form>
                                            </div>
                                        </div>
                                    </div>								
<?php 
include_once("includes/footer.php") ; 
include_once("includes/dynamic_table.php") ; 
?>
	<script src="js/croppie.min.js"></script>
	<script src="vendor/select2/js/select2.full.min.js"></script>
	<script src="js/cms.js"></script>
	<script src="js/plugins-init/select2-init.js"></script>
	<script>
	$(document).ready(function() {
		$('.single-select').select2({ dropdownParent: $('#canvas_user') });
		$('#renew_popup #renew_plans').select2({ dropdownParent: $('#renew_popup') });
		getPlans();
	});

	$(document).on('change', '#enroll_pt', function () {
		if ($(this).is(':checked')) {
			$('#pt_block').show();
			$('#pt_plan_id').prop('required', true);
			$('#pt_trainer_id').prop('required', true);
			$('#pt_total_amount').prop('required', true);
			$('#pt_paid_amount').prop('required', true);
			$('#pt_payment_mode').prop('required', true);
		} else {
			$('#pt_block').hide();
			$('#pt_plan_id').prop('required', false).val('').trigger('change');
			$('#pt_trainer_id').prop('required', false).val('').trigger('change');
			$('#pt_total_amount').prop('required', false).val('');
			$('#pt_paid_amount').prop('required', false).val('');
			$('#pt_pending_amount').val('');
			$('#pt_plan_price').val('');
			$('#pt_payment_mode').prop('required', false).val('Cash').trigger('change');
		}
	});

	$(document).on('change', '#pt_plan_id', function () {
		var pid = $(this).val();
		if (!pid) {
			$('#pt_plan_price').val('');
			$('#pt_total_amount').val('');
			$('#pt_paid_amount').val('');
			$('#pt_pending_amount').val('');
			return;
		}
		$.ajax({
			type: "POST",
			url: window.AJAX_URL,
			data: { tab: 'get_pt_plan_price', pt_plan_id: pid },
			success: function (data) {
				var obj = $.parseJSON(data);
				var price = parseFloat(obj.price || 0);
				$('#pt_plan_price').val(price.toFixed(2));
				// Default: Final Amount = PT plan price, Paid Amount = 0 (can be changed by receptionist)
				$('#pt_total_amount').val(price.toFixed(2));
				$('#pt_paid_amount').val('0');
				recalcPtPending();
			},
			error: function () {
				toastr.error("Unable to load PT plan price.");
			}
		});
	});

	function recalcPtPending() {
		var total = parseFloat($('#pt_total_amount').val() || 0);
		var paid = parseFloat($('#pt_paid_amount').val() || 0);
		if (total < 0) total = 0;
		if (paid < 0) paid = 0;
		// Validation: Paid Amount must not exceed Final Amount
		if (paid > total) {
			paid = total;
			$('#pt_paid_amount').val(paid);
		}
		var pending = total - paid;
		if (pending < 0) pending = 0;
		$('#pt_pending_amount').val(pending.toFixed(2));
	}

	$(document).on('keyup change', '#pt_total_amount,#pt_paid_amount', function () {
		recalcPtPending();
	});

	$(document).on('change', '#enroll_class', function () {
		if ($(this).is(':checked')) {
			$('#class_block').show();
			$('#class_schedule_id').prop('required', true);
		} else {
			$('#class_block').hide();
			$('#class_schedule_id').prop('required', false).val('').trigger('change');
		}
	});
	
	// Family ID generation removed.

	// Timing removed for gym version.

	function getPlans(){
		$("#preloader").show();
		 
		var tab = "get_plans";
		$.ajax({
				  url:"index.php?<?php echo $mysqli->encode('stat=custom_ajax');?>",
				  method:"POST",
				  data: {tab:tab,type:'Single'},
				  success: function (data) {
							$("#preloader").hide();
							
							$('#frm_user #plans').html(data);
							
							 // Refresh Bootstrap Select plugin after updating options
							$('#frm_user #plans').selectpicker('refresh');
							
							
						},
			   });
	}
	function getRenewPlans(type){
		
		var tab = "get_plans";
		
		$.ajax({
				  url:"index.php?<?php echo $mysqli->encode('stat=custom_ajax');?>",
				  method:"POST",
				  data: {tab:tab,type:type},
				  success: function (data) {
							
							$('#renew_member #renew_plans').html(data);
							
						},
			   });
	}
	function getBase(id){
		
		$("#preloader").show();
		var tab = "get_base_price";
		
		$.ajax({
				  url:"index.php?<?php echo $mysqli->encode('stat=ajax');?>",
				  method:"POST",
				  data: {tab:tab,id:id},
				  success: function (data) {
					  var obj = $.parseJSON(data);
					var price = parseFloat(obj.price || 0);
					var duration = parseInt(obj.duration || 0, 10);
							$("#preloader").hide();
							$('#frm_user #base').val(price);
							$('#final_amount_member').val(price);
							$('#total_amount').val(price);
							recalcPending();
							recalcEndDate(duration);
							
						},
			   });
	}

	function recalcPending() {
		var total = parseFloat($('#final_amount_member').val() || 0);
		var paid = parseFloat($('#paid_amount_member').val() || 0);
		if (total < 0) total = 0;
		if (paid < 0) paid = 0;
		// Validation: Paid Amount must not exceed Final Amount
		if (paid > total) {
			paid = total;
			$('#paid_amount_member').val(paid);
		}
		// Persist final amount for backend (backward compatible: backend reads `total_amount`)
		$('#total_amount').val(total);
		var pending = total - paid;
		if (pending < 0) pending = 0;
		$('#pending_amount').val(pending.toFixed(2));
	}

	$(document).on('keyup change', '#paid_amount_member,#final_amount_member', function () {
		recalcPending();
	});

	function recalcEndDate(durationDays) {
		var startDate = $('#joining_date').val();
		if (!startDate || !durationDays || durationDays < 1) {
			$('#end_date').val('');
			return;
		}
		// existing backend logic uses (duration - 1) days
		var totalDays = durationDays - 1;
		var dt = new Date(startDate + 'T00:00:00');
		dt.setDate(dt.getDate() + totalDays);
		var yyyy = dt.getFullYear();
		var mm = String(dt.getMonth() + 1).padStart(2, '0');
		var dd = String(dt.getDate()).padStart(2, '0');
		$('#end_date').val(yyyy + '-' + mm + '-' + dd);
	}

	$(document).on('change', '#joining_date', function () {
		var planId = $('#plans').val();
		if (planId) getBase(planId);
	});
	function getRenewBase(id){
		
		var tab = "get_base_price";
		
		$.ajax({
				  url:"index.php?<?php echo $mysqli->encode('stat=ajax');?>",
				  method:"POST",
				  data: {tab:tab,id:id},
				  success: function (data) {
					  var obj = $.parseJSON(data);
					var price = obj.price;
							
							$('#renew_member #base_price').val(price);
							
						},
			   });
	}
	// Family helper functions removed.
	// Declaration removed for gym version.
	function print_invoice(id){
		$("#preloader").show();
		var tab = "print_invoice";
		
		$.ajax({
				  url:"index.php?<?php echo $mysqli->encode('stat=custom_ajax');?>",
				  method:"POST",
				  data: {tab:tab,id:id},
				  success: function (data) {
							$("#preloader").hide();
							console.log(data);
							if (data) {
								var pdfUrl = data;
								window.open(pdfUrl, '_blank');
							} else {
								console.error('PDF path is not available.');
							}
						},
			   });
	}		
		</script>
 <script>
        document.addEventListener("DOMContentLoaded", function () {
            const video = document.getElementById('video');
            const cameraFrame = document.getElementById('cameraFrame');
            const captureButton = document.getElementById('captureButton');
            const capturedImageContainer = document.getElementById('capturedImageContainer');
            const capturedImage = document.getElementById('capturedImage');

            // Check if the browser supports getUserMedia
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: { exact: 'environment' } } })
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch(function (error) {
                        console.error('Error accessing camera:', error);
                        // Fallback to front camera if back camera is not available
                        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                            .then(function (stream) {
                                video.srcObject = stream;
                                video.play();
                            })
                            .catch(function (err) {
                                console.error('Error accessing front camera:', err);
                            });
                    });
            } else {
                console.error('getUserMedia is not supported in this browser');
            }
            video.addEventListener('loadedmetadata', function () {
                // Now that the video metadata is loaded, enable the capture button
                captureButton.disabled = false;
            });

            window.captureImage = function () {
                const displaySize = { width: video.videoWidth, height: video.videoHeight };

                const canvas = document.createElement('canvas');
                canvas.width = displaySize.width;
                canvas.height = displaySize.height;

                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, displaySize.width, displaySize.height);

                // Set the captured image source
                capturedImage.src = canvas.toDataURL('image/png');
				
				$('#image').val(capturedImage.src);
				$('#cameraFrame').hide();
				$('#captureButton').hide();
				$('#reset-btn').show();
				$('#capturedImageContainer').show();
				$('#final_btns').show();
                // Display the captured image
                //capturedImageContainer.style.display = 'block';
				capturedImage.focus();
            };

            window.resetCapture = function () {
                // Reset the captured image and hide the container and reset button
                capturedImage.src = '';
                $('#capturedImageContainer').hide();
               $('#cameraFrame').show();
			   $('#captureButton').show();
			   $('#reset-btn').hide();
			   $('#final_btns').hide();
            };
        });
		function delete_rec(id){
		
		Swal.fire({
                //title: 'Alert!',
                text: "Are you sure you want to delete this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                confirmButtonClass: 'btn btn-success',
                cancelButtonText: 'No',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function(result) {
                
                if (result.value) {
                    delete_record(id);
                } 
            });
		
	}
	function delete_record(id)
	{
		
		$('#preloader').show();
		 var frm = {
            id: id,
            tab: 'delete_member'
        }
        $.ajax({
            type: "POST",
            url:"index.php?<?php echo $mysqli->encode('stat=ajax');?>",
            data: frm,
            datatype: "json",
            success: function(response) {
				$('#preloader').hide();
				var obj = $.parseJSON(response);
				if (obj.msg_code != '00') {
					toastr.error(obj.msg);
				 }
				else {
					toastr.error(obj.msg);
					window.setTimeout(function () { location.reload() }, 1000);
					
					
			  }
            }

        });
	} 
		function export_members() {
        //console.log('hi');
        $('#preloader').show();
        var membership_id = $('#membership_id').val();
        var search_user_name = $('#search_user_name').val();
        var search_user_email = $('#search_user_email').val();
        var search_user_contact = $('#search_user_contact').val();
        var search_status = $('#search_status').val();
        var plan_type = $('#plan_type').val();
        var data = {};
        data.tab = 'export_member';
        data.membership_id = membership_id;
        data.search_user_name = search_user_name;
        data.search_user_email = search_user_email;
        data.search_user_contact = search_user_contact;
        data.search_status = search_status;
        data.plan_type = plan_type;
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
/* 	
const sportspersonCheckbox = document.getElementById('sportsperson');

// Function to handle checkbox state change
function handleCheckboxChange() {
    // Check if the checkbox is checked
    if (sportspersonCheckbox.checked) {
        // If checked, set value to 1
        sportspersonCheckbox.value = 1;
    } else {
        // If not checked, set value to 0
        sportspersonCheckbox.value = 0;
    }
}

// Add an event listener to the checkbox to listen for state changes
sportspersonCheckbox.addEventListener('change', handleCheckboxChange);

// Initialize the checkbox value based on its current state
handleCheckboxChange(); */
$(function() {
    // Initialize Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

    </script>

	</body>

</html>