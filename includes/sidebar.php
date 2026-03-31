<?php
if (!defined('ABSOLUTE_ROOT_PATH')) {
	echo "<script>location.href='../error_403.php'</script>";
	exit;
}
?>
<!--**********************************Sidebar start ***********************************-->
<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu mm-show" id="menu">
			<!--<li class="menu-title">YOUR COMPANY</li>-->
			<?php if (in_array($_SESSION['user_type'], ['SUPERADMIN','ADMIN','RECEPTIONIST','TRAINER'], true)) { ?>
			<?php $href = 'index.php?'.$mysqli->encode("stat=Dashboard");?>
				<li class="<?php if($stat == 'Dashboard') echo 'mm-active' ?>"><a href="<?php echo $href;?>" aria-expanded="false">
						<div <?php if ($stat == 'Dashboard') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 7.49999L10 1.66666L17.5 7.49999V16.6667C17.5 17.1087 17.3244 17.5326 17.0118 17.8452C16.6993 18.1577 16.2754 18.3333 15.8333 18.3333H4.16667C3.72464 18.3333 3.30072 18.1577 2.98816 17.8452C2.67559 17.5326 2.5 17.1087 2.5 16.6667V7.49999Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M7.5 18.3333V10H12.5V18.3333" stroke="#888888" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</div>
						<span class="nav-text">Dashboard</span>
					</a>
				</li>
				<?php } ?>

				<?php if ($_SESSION['user_type'] === 'RECEPTIONIST') { ?>
				<li class="<?php if ($stat == 'users') echo 'mm-active' ?>">
				<?php $href_1 = 'index.php?'.$mysqli->encode("stat=users");?>
					<a href="<?php echo $href_1;?>">
						<div <?php if ($stat == 'users') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-users"></i>
						</div>
						<span class="nav-text">Members</span>
					</a>
				</li>
				<?php } ?>

				<?php if ($_SESSION['user_type'] === 'TRAINER') { ?>
				<li class="<?php if ($stat == 'trainer_dashboard') echo 'mm-active' ?>">
				<?php $href_td = 'index.php?'.$mysqli->encode("stat=trainer_dashboard");?>
					<a href="<?php echo $href_td;?>">
						<div <?php if ($stat == 'trainer_dashboard') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-user-tie"></i>
						</div>
						<span class="nav-text">Trainer Dashboard</span>
					</a>
				</li>
				<?php } ?>
				<?php if ($_SESSION['user_type'] == 'SUPERADMIN') { ?>
				<li class="<?php if ($stat == 'plans') echo 'mm-active' ?>">
				<?php $href_1 = 'index.php?'.$mysqli->encode("stat=plans");?>
					<a href="<?php echo $href_1;?>">
						<div <?php if ($stat == 'plans') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">

								<path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
								<path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
							</svg>
						</div>
						<span class="nav-text">Plans</span>
					</a>
					
				</li>
				<?php } ?>
				<?php if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>
				<li class="<?php if ($stat == 'users') echo 'mm-active' ?>">
				<?php $href_1 = 'index.php?'.$mysqli->encode("stat=users");?>
					<a href="<?php echo $href_1;?>">
						<div <?php if ($stat == 'users') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-users"></i>
						</div>
						<span class="nav-text">Members</span>
					</a>
					
				</li>
				<li class="<?php if ($stat == 'membership_types') echo 'mm-active' ?>">
				<?php $href_mt = 'index.php?'.$mysqli->encode("stat=membership_types");?>
					<a href="<?php echo $href_mt;?>">
						<div <?php if ($stat == 'membership_types') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-id-card"></i>
						</div>
						<span class="nav-text">Membership Types</span>
					</a>
				</li>
				<li class="<?php if ($stat == 'employees') echo 'mm-active' ?>">
				<?php $href_emp = 'index.php?'.$mysqli->encode("stat=employees");?>
					<a href="<?php echo $href_emp;?>">
						<div <?php if ($stat == 'employees') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-briefcase"></i>
						</div>
						<span class="nav-text">Employees</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'pt_plans') echo 'mm-active' ?>">
				<?php $href_pt_plans = 'index.php?'.$mysqli->encode("stat=pt_plans");?>
					<a href="<?php echo $href_pt_plans;?>">
						<div <?php if ($stat == 'pt_plans') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-list-alt"></i>
						</div>
						<span class="nav-text">PT Plans</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'pt_members') echo 'mm-active' ?>">
				<?php $href_pt_members = 'index.php?'.$mysqli->encode("stat=pt_members");?>
					<a href="<?php echo $href_pt_members;?>">
						<div <?php if ($stat == 'pt_members') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-bolt"></i>
						</div>
						<span class="nav-text">PT Sessions</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'classes') echo 'mm-active' ?>">
				<?php $href_classes = 'index.php?'.$mysqli->encode("stat=classes");?>
					<a href="<?php echo $href_classes;?>">
						<div <?php if ($stat == 'classes') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-calendar"></i>
						</div>
						<span class="nav-text">Classes</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'class_schedule') echo 'mm-active' ?>">
				<?php $href_cs = 'index.php?'.$mysqli->encode("stat=class_schedule");?>
					<a href="<?php echo $href_cs;?>">
						<div <?php if ($stat == 'class_schedule') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-calendar"></i>
						</div>
						<span class="nav-text">Class Schedule</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'class_members') echo 'mm-active' ?>">
				<?php $href_cm = 'index.php?'.$mysqli->encode("stat=class_members");?>
					<a href="<?php echo $href_cm;?>">
						<div <?php if ($stat == 'class_members') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<i class="fa fa-user-plus"></i>
						</div>
						<span class="nav-text">Class Enrollments</span>
					</a>
				</li>

				<li class="<?php if ($stat == 'enquiry') echo 'mm-active' ?>">
				<?php $href_1 = 'index.php?'.$mysqli->encode("stat=enquiry");?>
					<a href="<?php echo $href_1;?>">
						<div <?php if ($stat == 'enquiry') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
								<path d="M12 16v.01" />
								<path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
							</svg>
						</div>
						<span class="nav-text">Enquiries</span>
					</a>
					
				</li><li class="<?php if ($stat == 'feedback') echo 'mm-active' ?>">
				<?php $href_f = 'index.php?'.$mysqli->encode("stat=feedback");?>
					<a href="<?php echo $href_f;?>">
						<div <?php if ($stat == 'feedback') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M2.87187 11.5983C1.79887 8.24832 3.05287 4.41932 6.56987 3.28632C8.41987 2.68932 10.4619 3.04132 11.9999 4.19832C13.4549 3.07332 15.5719 2.69332 17.4199 3.28632C20.9369 4.41932 22.1989 8.24832 21.1269 11.5983C19.4569 16.9083 11.9999 20.9983 11.9999 20.9983C11.9999 20.9983 4.59787 16.9703 2.87187 11.5983Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 6.70001C17.07 7.04601 17.826 8.00101 17.917 9.12201" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
						</div>
						<span class="nav-text">Feedbacks</span>
					</a>
					
				</li>
				<?php } ?>
				<?php if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>
			<li class="<?php if ($stat == 'messages') echo 'mm-active' ?>">
				<?php $href_1 = 'index.php?'.$mysqli->encode("stat=messages");?>
					<a href="<?php echo $href_1;?>">
						<div <?php if ($stat == 'messages') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
						</div>
						<span class="nav-text">Send Notifications</span>
					</a>
					
				</li>
			<?php } ?>

			<?php if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>
			<li class="<?php if ($stat == 'messaging') echo 'mm-active' ?>">
				<?php $href_m = 'index.php?'.$mysqli->encode("stat=messaging");?>
					<a href="<?php echo $href_m;?>">
						<div <?php if ($stat == 'messaging') echo "style='background:#0d99ff'" ?> class="menu-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
								<path d="M7.5 10h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
								<path d="M7.5 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
						</div>
						<span class="nav-text">Messaging</span>
					</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>

<!--
**********************************
Sidebar end
***********************************
-->