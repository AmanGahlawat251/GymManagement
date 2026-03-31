<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--**********************************  Header Start  ***********************************-->
	<?php require_once("includes/config.php"); ?>
	<!--**********************************  Header End  ***********************************-->
	<!--**********************************  Header Start  ***********************************-->
	<?php require_once("includes/header.php"); ?>
	<!--**********************************  Header End  ***********************************-->
	<title>Dashboard | <?php echo APPLICATION_NAME; ?> </title>
	<!--**********************************  Sidebar Start  ***********************************-->
	<?php require_once("includes/sidebar.php"); ?>
	<!--**********************************  Sidebar End  ***********************************-->
	<!--**********************************  Content body start  ***********************************-->

	<div class="content-body">
		<!-- row -->

		<div class="page-titles">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="javascript:void(0)">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				</li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
			</ol>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 wid-1001">
					<div class="row">
						<!--
						<div class="col-xl-3 col-sm-6">
							<div class="card same-card">
								<div class="card-body d-flex align-items-center  py-2" style="position: relative;">
									<div id="AllProject" style="min-height: 103.7px;">
									</div>
									<ul class="project-list">
										<li>
											<h6>All Exams</h6>
										</li>
										<li>
											<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="10" height="10" rx="3" fill="#3AC977"></rect>
											</svg>
											Done
										</li>
										<li>
											<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="10" height="10" rx="3" fill="var(--primary)"></rect>
											</svg>
											Ongoing
										</li>
										<li>
											<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="10" height="10" rx="3" fill="var(--secondary)"></rect>
											</svg>
											Not Started
										</li>
									</ul>
									<input type="hidden" id="total_project" value="11">
									<input type="hidden" id="not_start_project" value="1">
									<input type="hidden" id="in_progress_project" value="9">
									<input type="hidden" id="completed_project" value="1">
									<div class="resize-triggers">
										<div class="expand-trigger">
											<div style="width: 302px; height: 124px;"></div>
										</div>
										<div class="contract-trigger"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 same-card">
							<div class="card">
								<div class="card-body depostit-card">
									<div class="depostit-card-media d-flex justify-content-between style-1">
										<div>
											<h6>Exam Not Started </h6>
											<h3>1</h3>
										</div>
										<div class="icon-box bg-primary-light">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M16.3787 1.875H15.625V1.25C15.625 1.08424 15.5592 0.925268 15.4419 0.808058C15.3247 0.690848 15.1658 0.625 15 0.625C14.8342 0.625 14.6753 0.690848 14.5581 0.808058C14.4408 0.925268 14.375 1.08424 14.375 1.25V1.875H10.625V1.25C10.625 1.08424 10.5592 0.925268 10.4419 0.808058C10.3247 0.690848 10.1658 0.625 10 0.625C9.83424 0.625 9.67527 0.690848 9.55806 0.808058C9.44085 0.925268 9.375 1.08424 9.375 1.25V1.875H5.625V1.25C5.625 1.08424 5.55915 0.925268 5.44194 0.808058C5.32473 0.690848 5.16576 0.625 5 0.625C4.83424 0.625 4.67527 0.690848 4.55806 0.808058C4.44085 0.925268 4.375 1.08424 4.375 1.25V1.875H3.62125C2.99266 1.87599 2.3901 2.12614 1.94562 2.57062C1.50114 3.0151 1.25099 3.61766 1.25 4.24625V17.0037C1.25099 17.6323 1.50114 18.2349 1.94562 18.6794C2.3901 19.1239 2.99266 19.374 3.62125 19.375H16.3787C17.0073 19.374 17.6099 19.1239 18.0544 18.6794C18.4989 18.2349 18.749 17.6323 18.75 17.0037V4.24625C18.749 3.61766 18.4989 3.0151 18.0544 2.57062C17.6099 2.12614 17.0073 1.87599 16.3787 1.875ZM17.5 17.0037C17.499 17.3008 17.3806 17.5854 17.1705 17.7955C16.9604 18.0056 16.6758 18.124 16.3787 18.125H3.62125C3.32418 18.124 3.03956 18.0056 2.8295 17.7955C2.61944 17.5854 2.50099 17.3008 2.5 17.0037V4.24625C2.50099 3.94918 2.61944 3.66456 2.8295 3.4545C3.03956 3.24444 3.32418 3.12599 3.62125 3.125H4.375V3.75C4.375 3.91576 4.44085 4.07473 4.55806 4.19194C4.67527 4.30915 4.83424 4.375 5 4.375C5.16576 4.375 5.32473 4.30915 5.44194 4.19194C5.55915 4.07473 5.625 3.91576 5.625 3.75V3.125H9.375V3.75C9.375 3.91576 9.44085 4.07473 9.55806 4.19194C9.67527 4.30915 9.83424 4.375 10 4.375C10.1658 4.375 10.3247 4.30915 10.4419 4.19194C10.5592 4.07473 10.625 3.91576 10.625 3.75V3.125H14.375V3.75C14.375 3.91576 14.4408 4.07473 14.5581 4.19194C14.6753 4.30915 14.8342 4.375 15 4.375C15.1658 4.375 15.3247 4.30915 15.4419 4.19194C15.5592 4.07473 15.625 3.91576 15.625 3.75V3.125H16.3787C16.6758 3.12599 16.9604 3.24444 17.1705 3.4545C17.3806 3.66456 17.499 3.94918 17.5 4.24625V17.0037Z" fill="var(--primary)"></path>
												<path d="M7.68311 7.05812L6.24999 8.49125L5.44186 7.68312C5.38421 7.62343 5.31524 7.57581 5.23899 7.54306C5.16274 7.5103 5.08073 7.49306 4.99774 7.49234C4.91475 7.49162 4.83245 7.50743 4.75564 7.53886C4.67883 7.57028 4.60905 7.61669 4.55037 7.67537C4.49168 7.73406 4.44528 7.80384 4.41385 7.88065C4.38243 7.95746 4.36661 8.03976 4.36733 8.12275C4.36805 8.20573 4.3853 8.28775 4.41805 8.364C4.45081 8.44025 4.49842 8.50922 4.55811 8.56687L5.80811 9.81687C5.92532 9.93404 6.08426 9.99986 6.24999 9.99986C6.41572 9.99986 6.57466 9.93404 6.69186 9.81687L8.56686 7.94187C8.68071 7.82399 8.74371 7.66612 8.74229 7.50224C8.74086 7.33837 8.67513 7.18161 8.55925 7.06573C8.44337 6.94985 8.28661 6.88412 8.12274 6.8827C7.95887 6.88127 7.80099 6.94427 7.68311 7.05812Z" fill="var(--primary)"></path>
												<path d="M15 8.125H10.625C10.4592 8.125 10.3003 8.19085 10.1831 8.30806C10.0658 8.42527 10 8.58424 10 8.75C10 8.91576 10.0658 9.07473 10.1831 9.19194C10.3003 9.30915 10.4592 9.375 10.625 9.375H15C15.1658 9.375 15.3247 9.30915 15.4419 9.19194C15.5592 9.07473 15.625 8.91576 15.625 8.75C15.625 8.58424 15.5592 8.42527 15.4419 8.30806C15.3247 8.19085 15.1658 8.125 15 8.125Z" fill="var(--primary)"></path>
												<path d="M7.68311 12.6831L6.24999 14.1162L5.44186 13.3081C5.38421 13.2484 5.31524 13.2008 5.23899 13.1681C5.16274 13.1353 5.08073 13.1181 4.99774 13.1173C4.91475 13.1166 4.83245 13.1324 4.75564 13.1639C4.67883 13.1953 4.60905 13.2417 4.55037 13.3004C4.49168 13.3591 4.44528 13.4288 4.41385 13.5056C4.38243 13.5825 4.36661 13.6648 4.36733 13.7477C4.36805 13.8307 4.3853 13.9127 4.41805 13.989C4.45081 14.0653 4.49842 14.1342 4.55811 14.1919L5.80811 15.4419C5.92532 15.559 6.08426 15.6249 6.24999 15.6249C6.41572 15.6249 6.57466 15.559 6.69186 15.4419L8.56686 13.5669C8.68071 13.449 8.74371 13.2911 8.74229 13.1272C8.74086 12.9634 8.67513 12.8066 8.55925 12.6907C8.44337 12.5749 8.28661 12.5091 8.12274 12.5077C7.95887 12.5063 7.80099 12.5693 7.68311 12.6831Z" fill="var(--primary)"></path>
												<path d="M15 13.75H10.625C10.4592 13.75 10.3003 13.8158 10.1831 13.9331C10.0658 14.0503 10 14.2092 10 14.375C10 14.5408 10.0658 14.6997 10.1831 14.8169C10.3003 14.9342 10.4592 15 10.625 15H15C15.1658 15 15.3247 14.9342 15.4419 14.8169C15.5592 14.6997 15.625 14.5408 15.625 14.375C15.625 14.2092 15.5592 14.0503 15.4419 13.9331C15.3247 13.8158 15.1658 13.75 15 13.75Z" fill="var(--primary)"></path>
											</svg>
										</div>
									</div>
									<div class="progress-box mt-0">
										<div class="d-flex justify-content-between">
											<p class="mb-0">Papers Not Finished</p>
											<p class="mb-0">1/11</p>
										</div>
										<div class="progress">
											<div class="progress-bar bg-primary" style="width:9.0909090909091%; height:5px; border-radius:4px;" role="progressbar"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 same-card">
							<div class="card">
								<div class="card-body depostit-card">
									<div class="depostit-card-media d-flex justify-content-between style-1">
										<div>
											<h6>Exams Ongoing</h6>
											<h3>9</h3>
										</div>
										<div class="icon-box bg-primary-light">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M16.3787 1.875H15.625V1.25C15.625 1.08424 15.5592 0.925268 15.4419 0.808058C15.3247 0.690848 15.1658 0.625 15 0.625C14.8342 0.625 14.6753 0.690848 14.5581 0.808058C14.4408 0.925268 14.375 1.08424 14.375 1.25V1.875H10.625V1.25C10.625 1.08424 10.5592 0.925268 10.4419 0.808058C10.3247 0.690848 10.1658 0.625 10 0.625C9.83424 0.625 9.67527 0.690848 9.55806 0.808058C9.44085 0.925268 9.375 1.08424 9.375 1.25V1.875H5.625V1.25C5.625 1.08424 5.55915 0.925268 5.44194 0.808058C5.32473 0.690848 5.16576 0.625 5 0.625C4.83424 0.625 4.67527 0.690848 4.55806 0.808058C4.44085 0.925268 4.375 1.08424 4.375 1.25V1.875H3.62125C2.99266 1.87599 2.3901 2.12614 1.94562 2.57062C1.50114 3.0151 1.25099 3.61766 1.25 4.24625V17.0037C1.25099 17.6323 1.50114 18.2349 1.94562 18.6794C2.3901 19.1239 2.99266 19.374 3.62125 19.375H16.3787C17.0073 19.374 17.6099 19.1239 18.0544 18.6794C18.4989 18.2349 18.749 17.6323 18.75 17.0037V4.24625C18.749 3.61766 18.4989 3.0151 18.0544 2.57062C17.6099 2.12614 17.0073 1.87599 16.3787 1.875ZM17.5 17.0037C17.499 17.3008 17.3806 17.5854 17.1705 17.7955C16.9604 18.0056 16.6758 18.124 16.3787 18.125H3.62125C3.32418 18.124 3.03956 18.0056 2.8295 17.7955C2.61944 17.5854 2.50099 17.3008 2.5 17.0037V4.24625C2.50099 3.94918 2.61944 3.66456 2.8295 3.4545C3.03956 3.24444 3.32418 3.12599 3.62125 3.125H4.375V3.75C4.375 3.91576 4.44085 4.07473 4.55806 4.19194C4.67527 4.30915 4.83424 4.375 5 4.375C5.16576 4.375 5.32473 4.30915 5.44194 4.19194C5.55915 4.07473 5.625 3.91576 5.625 3.75V3.125H9.375V3.75C9.375 3.91576 9.44085 4.07473 9.55806 4.19194C9.67527 4.30915 9.83424 4.375 10 4.375C10.1658 4.375 10.3247 4.30915 10.4419 4.19194C10.5592 4.07473 10.625 3.91576 10.625 3.75V3.125H14.375V3.75C14.375 3.91576 14.4408 4.07473 14.5581 4.19194C14.6753 4.30915 14.8342 4.375 15 4.375C15.1658 4.375 15.3247 4.30915 15.4419 4.19194C15.5592 4.07473 15.625 3.91576 15.625 3.75V3.125H16.3787C16.6758 3.12599 16.9604 3.24444 17.1705 3.4545C17.3806 3.66456 17.499 3.94918 17.5 4.24625V17.0037Z" fill="var(--primary)"></path>
												<path d="M7.68311 7.05812L6.24999 8.49125L5.44186 7.68312C5.38421 7.62343 5.31524 7.57581 5.23899 7.54306C5.16274 7.5103 5.08073 7.49306 4.99774 7.49234C4.91475 7.49162 4.83245 7.50743 4.75564 7.53886C4.67883 7.57028 4.60905 7.61669 4.55037 7.67537C4.49168 7.73406 4.44528 7.80384 4.41385 7.88065C4.38243 7.95746 4.36661 8.03976 4.36733 8.12275C4.36805 8.20573 4.3853 8.28775 4.41805 8.364C4.45081 8.44025 4.49842 8.50922 4.55811 8.56687L5.80811 9.81687C5.92532 9.93404 6.08426 9.99986 6.24999 9.99986C6.41572 9.99986 6.57466 9.93404 6.69186 9.81687L8.56686 7.94187C8.68071 7.82399 8.74371 7.66612 8.74229 7.50224C8.74086 7.33837 8.67513 7.18161 8.55925 7.06573C8.44337 6.94985 8.28661 6.88412 8.12274 6.8827C7.95887 6.88127 7.80099 6.94427 7.68311 7.05812Z" fill="var(--primary)"></path>
												<path d="M15 8.125H10.625C10.4592 8.125 10.3003 8.19085 10.1831 8.30806C10.0658 8.42527 10 8.58424 10 8.75C10 8.91576 10.0658 9.07473 10.1831 9.19194C10.3003 9.30915 10.4592 9.375 10.625 9.375H15C15.1658 9.375 15.3247 9.30915 15.4419 9.19194C15.5592 9.07473 15.625 8.91576 15.625 8.75C15.625 8.58424 15.5592 8.42527 15.4419 8.30806C15.3247 8.19085 15.1658 8.125 15 8.125Z" fill="var(--primary)"></path>
												<path d="M7.68311 12.6831L6.24999 14.1162L5.44186 13.3081C5.38421 13.2484 5.31524 13.2008 5.23899 13.1681C5.16274 13.1353 5.08073 13.1181 4.99774 13.1173C4.91475 13.1166 4.83245 13.1324 4.75564 13.1639C4.67883 13.1953 4.60905 13.2417 4.55037 13.3004C4.49168 13.3591 4.44528 13.4288 4.41385 13.5056C4.38243 13.5825 4.36661 13.6648 4.36733 13.7477C4.36805 13.8307 4.3853 13.9127 4.41805 13.989C4.45081 14.0653 4.49842 14.1342 4.55811 14.1919L5.80811 15.4419C5.92532 15.559 6.08426 15.6249 6.24999 15.6249C6.41572 15.6249 6.57466 15.559 6.69186 15.4419L8.56686 13.5669C8.68071 13.449 8.74371 13.2911 8.74229 13.1272C8.74086 12.9634 8.67513 12.8066 8.55925 12.6907C8.44337 12.5749 8.28661 12.5091 8.12274 12.5077C7.95887 12.5063 7.80099 12.5693 7.68311 12.6831Z" fill="var(--primary)"></path>
												<path d="M15 13.75H10.625C10.4592 13.75 10.3003 13.8158 10.1831 13.9331C10.0658 14.0503 10 14.2092 10 14.375C10 14.5408 10.0658 14.6997 10.1831 14.8169C10.3003 14.9342 10.4592 15 10.625 15H15C15.1658 15 15.3247 14.9342 15.4419 14.8169C15.5592 14.6997 15.625 14.5408 15.625 14.375C15.625 14.2092 15.5592 14.0503 15.4419 13.9331C15.3247 13.8158 15.1658 13.75 15 13.75Z" fill="var(--primary)"></path>
											</svg>
										</div>
									</div>
									<div class="progress-box mt-0">
										<div class="d-flex justify-content-between">
											<p class="mb-0">Papers Not Finished</p>
											<p class="mb-0">9/11</p>
										</div>
										<div class="progress">
											<div class="progress-bar bg-primary" style="width:81.818181818182%; height:5px; border-radius:4px;" role="progressbar"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 same-card">
							<div class="card">
								<div class="card-body depostit-card">
									<div class="depostit-card-media d-flex justify-content-between style-1">
										<div>
											<h6>Exams Done</h6>
											<h3>1</h3>
										</div>
										<div class="icon-box bg-primary-light">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M16.3787 1.875H15.625V1.25C15.625 1.08424 15.5592 0.925268 15.4419 0.808058C15.3247 0.690848 15.1658 0.625 15 0.625C14.8342 0.625 14.6753 0.690848 14.5581 0.808058C14.4408 0.925268 14.375 1.08424 14.375 1.25V1.875H10.625V1.25C10.625 1.08424 10.5592 0.925268 10.4419 0.808058C10.3247 0.690848 10.1658 0.625 10 0.625C9.83424 0.625 9.67527 0.690848 9.55806 0.808058C9.44085 0.925268 9.375 1.08424 9.375 1.25V1.875H5.625V1.25C5.625 1.08424 5.55915 0.925268 5.44194 0.808058C5.32473 0.690848 5.16576 0.625 5 0.625C4.83424 0.625 4.67527 0.690848 4.55806 0.808058C4.44085 0.925268 4.375 1.08424 4.375 1.25V1.875H3.62125C2.99266 1.87599 2.3901 2.12614 1.94562 2.57062C1.50114 3.0151 1.25099 3.61766 1.25 4.24625V17.0037C1.25099 17.6323 1.50114 18.2349 1.94562 18.6794C2.3901 19.1239 2.99266 19.374 3.62125 19.375H16.3787C17.0073 19.374 17.6099 19.1239 18.0544 18.6794C18.4989 18.2349 18.749 17.6323 18.75 17.0037V4.24625C18.749 3.61766 18.4989 3.0151 18.0544 2.57062C17.6099 2.12614 17.0073 1.87599 16.3787 1.875ZM17.5 17.0037C17.499 17.3008 17.3806 17.5854 17.1705 17.7955C16.9604 18.0056 16.6758 18.124 16.3787 18.125H3.62125C3.32418 18.124 3.03956 18.0056 2.8295 17.7955C2.61944 17.5854 2.50099 17.3008 2.5 17.0037V4.24625C2.50099 3.94918 2.61944 3.66456 2.8295 3.4545C3.03956 3.24444 3.32418 3.12599 3.62125 3.125H4.375V3.75C4.375 3.91576 4.44085 4.07473 4.55806 4.19194C4.67527 4.30915 4.83424 4.375 5 4.375C5.16576 4.375 5.32473 4.30915 5.44194 4.19194C5.55915 4.07473 5.625 3.91576 5.625 3.75V3.125H9.375V3.75C9.375 3.91576 9.44085 4.07473 9.55806 4.19194C9.67527 4.30915 9.83424 4.375 10 4.375C10.1658 4.375 10.3247 4.30915 10.4419 4.19194C10.5592 4.07473 10.625 3.91576 10.625 3.75V3.125H14.375V3.75C14.375 3.91576 14.4408 4.07473 14.5581 4.19194C14.6753 4.30915 14.8342 4.375 15 4.375C15.1658 4.375 15.3247 4.30915 15.4419 4.19194C15.5592 4.07473 15.625 3.91576 15.625 3.75V3.125H16.3787C16.6758 3.12599 16.9604 3.24444 17.1705 3.4545C17.3806 3.66456 17.499 3.94918 17.5 4.24625V17.0037Z" fill="var(--primary)"></path>
												<path d="M7.68311 7.05812L6.24999 8.49125L5.44186 7.68312C5.38421 7.62343 5.31524 7.57581 5.23899 7.54306C5.16274 7.5103 5.08073 7.49306 4.99774 7.49234C4.91475 7.49162 4.83245 7.50743 4.75564 7.53886C4.67883 7.57028 4.60905 7.61669 4.55037 7.67537C4.49168 7.73406 4.44528 7.80384 4.41385 7.88065C4.38243 7.95746 4.36661 8.03976 4.36733 8.12275C4.36805 8.20573 4.3853 8.28775 4.41805 8.364C4.45081 8.44025 4.49842 8.50922 4.55811 8.56687L5.80811 9.81687C5.92532 9.93404 6.08426 9.99986 6.24999 9.99986C6.41572 9.99986 6.57466 9.93404 6.69186 9.81687L8.56686 7.94187C8.68071 7.82399 8.74371 7.66612 8.74229 7.50224C8.74086 7.33837 8.67513 7.18161 8.55925 7.06573C8.44337 6.94985 8.28661 6.88412 8.12274 6.8827C7.95887 6.88127 7.80099 6.94427 7.68311 7.05812Z" fill="var(--primary)"></path>
												<path d="M15 8.125H10.625C10.4592 8.125 10.3003 8.19085 10.1831 8.30806C10.0658 8.42527 10 8.58424 10 8.75C10 8.91576 10.0658 9.07473 10.1831 9.19194C10.3003 9.30915 10.4592 9.375 10.625 9.375H15C15.1658 9.375 15.3247 9.30915 15.4419 9.19194C15.5592 9.07473 15.625 8.91576 15.625 8.75C15.625 8.58424 15.5592 8.42527 15.4419 8.30806C15.3247 8.19085 15.1658 8.125 15 8.125Z" fill="var(--primary)"></path>
												<path d="M7.68311 12.6831L6.24999 14.1162L5.44186 13.3081C5.38421 13.2484 5.31524 13.2008 5.23899 13.1681C5.16274 13.1353 5.08073 13.1181 4.99774 13.1173C4.91475 13.1166 4.83245 13.1324 4.75564 13.1639C4.67883 13.1953 4.60905 13.2417 4.55037 13.3004C4.49168 13.3591 4.44528 13.4288 4.41385 13.5056C4.38243 13.5825 4.36661 13.6648 4.36733 13.7477C4.36805 13.8307 4.3853 13.9127 4.41805 13.989C4.45081 14.0653 4.49842 14.1342 4.55811 14.1919L5.80811 15.4419C5.92532 15.559 6.08426 15.6249 6.24999 15.6249C6.41572 15.6249 6.57466 15.559 6.69186 15.4419L8.56686 13.5669C8.68071 13.449 8.74371 13.2911 8.74229 13.1272C8.74086 12.9634 8.67513 12.8066 8.55925 12.6907C8.44337 12.5749 8.28661 12.5091 8.12274 12.5077C7.95887 12.5063 7.80099 12.5693 7.68311 12.6831Z" fill="var(--primary)"></path>
												<path d="M15 13.75H10.625C10.4592 13.75 10.3003 13.8158 10.1831 13.9331C10.0658 14.0503 10 14.2092 10 14.375C10 14.5408 10.0658 14.6997 10.1831 14.8169C10.3003 14.9342 10.4592 15 10.625 15H15C15.1658 15 15.3247 14.9342 15.4419 14.8169C15.5592 14.6997 15.625 14.5408 15.625 14.375C15.625 14.2092 15.5592 14.0503 15.4419 13.9331C15.3247 13.8158 15.1658 13.75 15 13.75Z" fill="var(--primary)"></path>
											</svg>

										</div>
									</div>
									<div class="progress-box mt-0">
										<div class="d-flex justify-content-between">
											<p class="mb-0">Tasks Not Finished</p>
											<p class="mb-0">1/11</p>
										</div>
										<div class="progress">
											<div class="progress-bar bg-primary" style="width:9.0909090909091%; height:5px; border-radius:4px;" role="progressbar"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						-->

						<div class="col-xl-12 active-p">
							<div class="card">
								<div class="card-body p-0">
									<div class="table-responsive active-projects shorting">
										<div class="tbl-caption">
											<h4 class="heading mb-0">Recent Exam</h4> <a style="position: relative;float: right;margin-top: -19px;margin-right: 4px;cursor: pointer;" href="my_scorecard.php">View All</a>
										</div>

										<?php

										$qry_user = "SELECT section  FROM `tbl_user` WHERE `user_id` = '" . $_SESSION['user_id'] . "'";
										$qry_result_user = $common_helper->runQry($qry_user);
										$user_data  = $common_helper->get_assoc($qry_result_user);

										$query = "SELECT p.*,(SELECT count(pas.id)  FROM `tbl_paper_score_card` as pas WHERE pas.`user_id` = '" . $_SESSION['user_id'] . "' AND pas.paper_id = p.id ) as attendance FROM " . PAPER . " as p where 1 AND p.paper_status = '1' AND p.paper_type = '" . $user_data['section'] . "' AND p.date_of_exam < NOW() order by p.id DESC limit 0,10";
										$result = $common_helper->runQry($query);

										$qry = "select count(id) as count_rows from " . PAPER . " where 1  AND paper_status = '1' AND paper_type = '" . $user_data['section'] . "' AND date_of_exam < NOW()";

										$qry_result = $common_helper->runQry($qry);
										$num_arr = $common_helper->get_assoc($qry_result);
										$num = $num_arr['count_rows'];
										$row = "";
										$table = '';
 
										if ($num > 0) {

											$table .= '
												<div class="card-body">
												<table data-sort="true" class="table header-border tablesorter">
												<thead>
												<tr>';
											$table .= '<th class="sort"><nobr>#</nobr></th>';

											// if ($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '2' || $_SESSION['role_id'] == '6') {
											// 	$table .= '<th>Action</th>';
											// }

											$table .= '<th>Exam Title</th>';
											$table .= '<th>Course</th>';
											$table .= '<th>Total Question</th>';
											$table .= '<th>Attendance</th>';
											$table .= '<th>Status</th>';
											$table .= '<th>Schedule Date</th>';
											$table .= '<th>Schedule Time</th>';
											$table .= '<th>Duration</th>';

											//$table .= '<th>Question Review</th>';
											//$table .= '<th>Out of syllabus</th>';
											//$table .= '<th>Review By</th>';
											//$table .= '<th>Created By</th>';
											//$table .= '<th>Created Date</th>';
											//$table .= '<th>Modified Date</th>';

											$table .= '</thead>	
												<tbody id="tbody">';
											if ($num > 0) {
												$n = 1;

												while ($rows = $common_helper->get_assoc($result)) {
													$row_data = "";
													$country_code = '';
													$i = ($n);
													foreach ($rows as $key => $value) {
														if ($key == 'id') {
															continue;
														} else if ($key == 'paper_distribution') {
															if ($value != '') {
																$value = json_encode(unserialize($value));
																//$value_dis = unserialize($value);	
																//print_r($value);
															}
														}
														$row_data .= "data-" . $key . "='" . $value . "' ";
													}
													$row_data .= 'data-id="' . $common_helper->encrypt($rows['id']) . '" ';
													$row_data .= 'data-action="' . $common_helper->encrypt("update_new_request") . '" ';
													extract($rows);

													$table .= "<tr>";
													$table .= "<td><nobr>" . $i . "</nobr></td>";

													//  if ($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '2' || $_SESSION['role_id'] == '6') {
													// 		$table .= '<td><nobr>';
													// 		$table .= '<a href="javascript:void(0);" ' . $row_data . '  id="edit' . $i . '" class="paper-edit-form"><i style="font-size: 1.3em; color:#0d99ff;" class="fas fa-edit" aria-hidden="true"></i></a>&nbsp;';
													// 		$table .= '<a href="javascript:void(0);" data-paper_id="' . $common_helper->encrypt($rows['id']) . '" data-action="' . $common_helper->encrypt("delete_paper") . '" class="paper-delete-form"><i class="fas fa-trash" style="font-size: 1.3em; color:#ff5e5e;" aria-hidden="true"></i></a>&nbsp;';
													// 		$table .= '<a href="add_question.php?paper_id=' . $common_helper->encrypt($rows['id']) . '" ><i class="fas fa-plus-circle" style="font-size: 1.3em; color:#ff5e5e;" aria-hidden="true"></i></a>';
													// 		$table .= '</nobr></td>';
													//  }

													$table .= "<td><nobr>" . $name . "</nobr></td>";
													$exam_name = $common_helper->GetLabel(EXAM, 'id', 'name', $exam_id);
													$table .= "<td><nobr>" . $exam_name . "</nobr></td>";

													$qry = "SELECT * FROM tbl_paper_questions WHERE paper_id = '" . $id . "' ";
													$qry_result = $common_helper->runQry($qry);
													$total_question = $common_helper->getRowsCount($qry_result);

													$table .= "<td><nobr>" . $total_question . "</nobr></td>";

													$attendance_text = 'A'; 
													if($attendance > 0){
														$attendance_text = 'P';
													}
													$table .= "<td><nobr>" . $attendance_text . "</nobr></td>";

													$status_class = '';

													if ($paper_status == '1') {
														$status_class = 'success';
														$pstatus = 'Scheduled';
													} else if ($paper_status == '3') {
														$status_class = 'success';
														$pstatus = 'Completed';
													} else if ($paper_status == '2') {
														$pstatus =  'Canceled';
														$status_class = 'danger';
													} else {
														$pstatus = 'Drafted';
														$status_class = 'warning';
													}

													$table .= "<td><nobr><span class=\"badge badge-" . $status_class . " light border-0 \">" . $pstatus . "</span></nobr></td>";


													if ($date_of_exam != '') {
														$table .= "<td><nobr>" . $common_helper->dateformat($date_of_exam, 'j-M-Y') . "</nobr></td>";
													} else {
														$table .= "<td><nobr>-</nobr></td>";
													}

													$table .= "<td><nobr>-</nobr></td>";
													$table .= "<td><nobr>" . $paper_duration . "</nobr></td>";

													$user_name = $common_helper->GetLabel(USER, 'user_id', 'user_name', $created_by);

													//$table .= "<td><nobr>" . $user_name . "</nobr></td>";
													//$table .= "<td><nobr>" . $common_helper->dateformat($created_on, 'j-M-Y') . "</nobr></td>";
													//$table .= "<td><nobr>" . $common_helper->dateformat($modified_on, 'j-M-Y') . "</nobr></td>";
													$table .= "<tr>";
													$n++;
													$data = "";
												}
											}
											$table .= '</tbody></table></div>';
										} else {
											$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
										}

										echo $table;
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 up-shd1">
					<div class="card">
						<div class="card-header border-0 pb-1">
							<h4 class="heading mb-0">Upcoming Exam</h4>
						</div>
						<div class="card-body schedules-cal p-2">
							<!--<input type="text" class="form-control d-none" id="datetimepicker1"> -->

							<?php

							$query = "SELECT * FROM " . PAPER . " where 1 AND paper_status = '1' AND date_of_exam > NOW() order by id DESC limit 0,5";
							$result = $common_helper->runQry($query);

							$qry = "select count(id) as count_rows from " . PAPER . " where 1  AND paper_status = '1'  AND date_of_exam > NOW()";

							$qry_result = $common_helper->runQry($qry);
							$num_arr = $common_helper->get_assoc($qry_result);
							$num = $num_arr['count_rows'];
							$row = "";
							$table = '';

							if ($num > 0) {

								$table .= '';
								if ($num > 0) {
									$n = 1;

									while ($rows = $common_helper->get_assoc($result)) {
										$row_data = "";
										$country_code = '';
										$i = ($n);
										foreach ($rows as $key => $value) {
											if ($key == 'id') {
												continue;
											} else if ($key == 'paper_distribution') {
												if ($value != '') {
													$value = json_encode(unserialize($value));
													//$value_dis = unserialize($value);	
													//print_r($value);
												}
											}
											$row_data .= "data-" . $key . "='" . $value . "' ";
										}
										$row_data .= 'data-id="' . $common_helper->encrypt($rows['id']) . '" ';
										$row_data .= 'data-action="' . $common_helper->encrypt("update_new_request") . '" ';
										extract($rows);

										$exam_name = $common_helper->GetLabel(EXAM, 'id', 'name', $exam_id);
										$qry = "SELECT * FROM tbl_paper_questions WHERE paper_id = '" . $id . "' ";
										$qry_result = $common_helper->runQry($qry);
										$total_question = $common_helper->getRowsCount($qry_result);

										//$table .= "<td><nobr>" . $total_question . "</nobr></td>";

										$table .= '<div class="events">
														<h6 style="padding:0px !important;"></h6>
														<div style="height:auto !important; padding: 0 0.5rem !important;" class="dz-scroll event-scroll">
															<div class="event-media">
																<div class="d-flex align-items-center">
																	<div class="event-box">
																		<h5 class="mb-0">22</h5><span>Wed</span>
																	</div>
																	<div class="event-data ms-2">
																		<h5 class="mb-0"><a href="javascript:void(0)">assas</a></h5>
																		<span>' . $name . '</span>
																	</div>
																</div>
																<span class="text-secondary">' . $common_helper->dateformat($date_of_exam, 'j-M-Y') . '</span>
															</div>
														</div>
													</div>';

										// $table .= "<tr>";
										// $table .= "<td><nobr>" . $i . "</nobr></td>";
										// if ($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '2' || $_SESSION['role_id'] == '6') {
										// 	$table .= '<td><nobr>';
										// 	$table .= '<a href="javascript:void(0);" ' . $row_data . '  id="edit' . $i . '" class="paper-edit-form"><i style="font-size: 1.3em; color:#0d99ff;" class="fas fa-edit" aria-hidden="true"></i></a>&nbsp;';
										// 	$table .= '<a href="javascript:void(0);" data-paper_id="' . $common_helper->encrypt($rows['id']) . '" data-action="' . $common_helper->encrypt("delete_paper") . '" class="paper-delete-form"><i class="fas fa-trash" style="font-size: 1.3em; color:#ff5e5e;" aria-hidden="true"></i></a>&nbsp;';
										// 	$table .= '<a href="add_question.php?paper_id=' . $common_helper->encrypt($rows['id']) . '" ><i class="fas fa-plus-circle" style="font-size: 1.3em; color:#ff5e5e;" aria-hidden="true"></i></a>';
										// 	$table .= '</nobr></td>';
										// }

										// $table .= "<td><nobr>" . $name . "</nobr></td>";
										// $table .= "<td><nobr>" . $exam_name . "</nobr></td>";
										// $status_class = '';

										// if ($paper_status == '1') {
										// 		$status_class = 'success';
										// 		$pstatus = 'Scheduled';
										// } else if ($paper_status == '2') {
										// 		$pstatus =  'Canceled';
										// 		$status_class = 'danger';
										// } else {
										// 		$pstatus = 'Drafted';
										// 		$status_class = 'warning';
										// }

										// $table .= "<td><nobr><span class=\"badge badge-" . $status_class . " light border-0 \">" . $pstatus . "</span></nobr></td>";

										// if ($date_of_exam != '') {
										// 	$table .= "<td><nobr>" .  . "</nobr></td>";
										// } else {
										// 	$table .= "<td><nobr>-</nobr></td>";
										// }

										// $table .= "<td><nobr>-</nobr></td>";
										// $table .= "<td><nobr>" . $paper_duration . "</nobr></td>";
										// $user_name = $common_helper->GetLabel(USER, 'user_id', 'user_name', $created_by);
										//$table .= "<td><nobr>" . $user_name . "</nobr></td>";
										//$table .= "<td><nobr>" . $common_helper->dateformat($created_on, 'j-M-Y') . "</nobr></td>";
										//$table .= "<td><nobr>" . $common_helper->dateformat($modified_on, 'j-M-Y') . "</nobr></td>";
										// $table .= "<tr>";

										$n++;
										$data = "";
									}
								}
								//$table .= '</tbody></table></div>';
							} else {
								$table = '<div class="card-body"><div class="alert alert-danger"><strong>!!</strong> No record found.</div></div>';
							}
							echo $table;
							?>

							<!--
							<div class="events">
								<h6 style="padding:0px !important;"></h6>
								<div style="height:auto !important; padding: 0 0.5rem !important;" class="dz-scroll event-scroll">
									<div class="event-media">
										<div class="d-flex align-items-center">
											<div class="event-box">
												<h5 class="mb-0">22</h5><span>Wed</span>
											</div>
											<div class="event-data ms-2">
												<h5 class="mb-0"><a href="javascript:void(0)">assas</a></h5>
												<span>Test Company</span>
											</div>
										</div>
										<span class="text-secondary">12:12 AM</span>
									</div>
								</div>
							</div>
							<div class="events">
								<h6 style="padding:0px !important;"></h6>
								<div style="height:auto !important; padding: 0 0.5rem !important;" class="dz-scroll event-scroll">
									<div class="event-media">
										<div class="d-flex align-items-center">
											<div class="event-box">
												<h5 class="mb-0">22</h5><span>Wed</span>
											</div>
											<div class="event-data ms-2">
												<h5 class="mb-0"><a href="javascript:void(0)">assas</a></h5>
												<span>Test Company</span>
											</div>
										</div>
										<span class="text-secondary">12:12 AM</span>
									</div>
								</div>
							</div>
							-->
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Student Performance</h4>
								</div>
								<div id="bar-chart2"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Exam Status</h4>
								</div>
								<div id="pie-chart"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Exam-wise Performance</h4>
								</div>
								<div id="bar-chart"></div>
							</div>
						</div>
					</div>
				</div>
				<!--
				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Subject-wise Performance</h4>
								</div>
								<div id="line-chart"></div>
							</div>
						</div>
					</div>
				</div>
						-->


				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Physics Performance</h4>
								</div>
								<div id="line-chart1"></div>
							</div>
						</div>
					</div>
				</div>


				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Maths Performance</h4>
								</div>
								<div id="line-chart2"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-6">
					<div class="row">
						<div class="col-xl-12">
							<div class="card same-card">
								<div class="tbl-caption">
									<h4 class="heading m-3">Chemistry Performance</h4>
								</div>
								<div id="line-chart3"></div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

		<!-- <h5>Page Hits per Country</h5>
		<div id="pie-chart"></div> -->
		<?php

		$con = "";

		if ($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '2' || $_SESSION['role_id'] == '6') {
		} else {
			$session_user_id = $_SESSION['user_id'];
			$con .= " and pq.user_id = '" . $session_user_id . "'";
		}

		$result_data = array();
		$query = "SELECT 
					pq.id,
					pq.paper_id,
					p.name,
					count(ans.id) as total_question,
					SUM(CASE WHEN ans.ans_status = 'Correct' THEN 1 ELSE 0 END) TotalCorrect,
					SUM(CASE WHEN ans.ans_status = 'Correct' THEN 4 ELSE 0 END) TotalScore,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '4' THEN 1 ELSE 0 END) TotalCorrectP,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '5' THEN 1 ELSE 0 END) TotalCorrectC,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '6' THEN 1 ELSE 0 END) TotalCorrectM,
					SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '4' THEN 1 ELSE 0 END) TotalCorrectPW,
					SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '5' THEN 1 ELSE 0 END) TotalCorrectCW,
					SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '6' THEN 1 ELSE 0 END) TotalCorrectMW,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '4' THEN 4 ELSE 0 END) TotalScoreP,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '5' THEN 4 ELSE 0 END) TotalScoreC,
					SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '6' THEN 4 ELSE 0 END) TotalScoreM,
					SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') THEN 1 ELSE 0 END) TotalAttend,
					SUM(CASE WHEN ans.ans_status = 'Wrong' AND (selected_ans !='')  THEN 1 ELSE 0 END) TotalWrong
					FROM tbl_paper_score_card as pq 
					left join tbl_user as u on u.user_id = pq.user_id 
					left join tbl_papers as p on p.id = pq.paper_id 
					left join tbl_paper_answer_sheet as ans on ans.sheet_id = pq.id 
					where 1 " . $con . " group by pq.paper_id order by pq.id DESC limit 10";


		$result = $common_helper->runQry($query);
		$rows_count1 = $common_helper->getRowsCount($result);
		$dataRow = array();
		$dataRow[] = 'Exam';
		$dataRow[] = 'Physics';
		$dataRow[] = 'Maths';
		$dataRow[] = 'Chemistry';


		$dataRowLineChart11 = array();
		$dataRowLineChart11[] = 'Exam';
		$dataRowLineChart11[] = 'Physics';

		$dataRowLineChart21 = array();
		$dataRowLineChart21[] = 'Exam';
		$dataRowLineChart21[] = 'Maths';

		$dataRowLineChart31 = array();
		$dataRowLineChart31[] = 'Exam';
		$dataRowLineChart31[] = 'Chemistry';

		$result_data[] = $dataRow;

		$dataRowLineChart1[] = $dataRowLineChart11;
		$dataRowLineChart2[] = $dataRowLineChart21;
		$dataRowLineChart3[] = $dataRowLineChart31;

		if ($rows_count1 > 0) {
			while ($rows = $common_helper->get_assoc($result)) {
				
				$dataRow = array();
				$dataRow[] = $rows['name'];
				$dataRow[] = ($rows['TotalScoreP'] - $rows['TotalCorrectPW']);
				$dataRow[] = ($rows['TotalScoreM'] - $rows['TotalCorrectMW']);
				$dataRow[] = ($rows['TotalScoreC'] - $rows['TotalCorrectCW']);

				$dataRow1 = array();
				$dataRow1[] = $rows['name'];
				$dataRow1[] = ($rows['TotalScoreP'] - $rows['TotalCorrectPW']);

				$dataRow2 = array();
				$dataRow2[] = $rows['name'];
				$dataRow2[] = ($rows['TotalScoreM'] - $rows['TotalCorrectMW']);

				$dataRow3 = array();
				$dataRow3[] = $rows['name'];
				$dataRow3[] = ($rows['TotalScoreC'] - $rows['TotalCorrectCW']);

				//$dataRow[] = (int)(($rows['TotalScoreP'] - $rows['TotalCorrectPW']) / $rows['total_user']);
				//$dataRow[] = (int)(($rows['TotalScoreM'] - $rows['TotalCorrectMW']) / $rows['total_user']);
				//$dataRow[] = (int)(($rows['TotalScoreC'] - $rows['TotalCorrectCW']) / $rows['total_user']);

				$result_data[] = $dataRow;

				$dataRowLineChart1[] = $dataRow1;
				$dataRowLineChart2[] = $dataRow2;
				$dataRowLineChart3[] = $dataRow3;
			}
		} else {
			$result_data[] = array('', 0, 0, 0);
			$dataRowLineChart1[] = array('', 0);
			$dataRowLineChart2[] = array('', 0);
			$dataRowLineChart3[] = array('', 0);
		}

		$con2 = "";

		if ($_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '2' || $_SESSION['role_id'] == '6') {
		} else {
			$session_user_id = $_SESSION['user_id'];
			$con2 .= " and pq.user_id = '" . $session_user_id . "'";
		}

		$result_data2 = array();

		$query2 = "SELECT 
				pq.id,
				pq.paper_id,
				p.name,
				count(ans.id) as total_question,
				SUM(CASE WHEN ans.ans_status = 'Correct' THEN 1 ELSE 0 END) TotalCorrect,
				SUM(CASE WHEN ans.ans_status = 'Correct' THEN 4 ELSE 0 END) TotalScore,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '4' THEN 1 ELSE 0 END) TotalCorrectP,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '5' THEN 1 ELSE 0 END) TotalCorrectC,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '6' THEN 1 ELSE 0 END) TotalCorrectM,
				SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '4' THEN 1 ELSE 0 END) TotalCorrectPW,
				SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '5' THEN 1 ELSE 0 END) TotalCorrectCW,
				SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') AND ans.ans_status = 'Wrong' AND ans.subject_id = '6' THEN 1 ELSE 0 END) TotalCorrectMW,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '4' THEN 4 ELSE 0 END) TotalScoreP,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '5' THEN 4 ELSE 0 END) TotalScoreC,
				SUM(CASE WHEN ans.ans_status = 'Correct' AND ans.subject_id = '6' THEN 4 ELSE 0 END) TotalScoreM,
				SUM(CASE WHEN (selected_ans !='') AND (ans.ans_status != 'Not-Evaluate') THEN 1 ELSE 0 END) TotalAttend,
				SUM(CASE WHEN ans.ans_status = 'Wrong' AND (selected_ans !='')  THEN 1 ELSE 0 END) TotalWrong
				FROM tbl_paper_score_card as pq 
				left join tbl_user as u on u.user_id = pq.user_id 
				left join tbl_papers as p on p.id = pq.paper_id 
				left join tbl_paper_answer_sheet as ans on ans.sheet_id = pq.id 
				where 1 " . $con . " group by pq.paper_id order by pq.id DESC limit 10";

		$result2 = $common_helper->runQry($query2);
		$rows_count = $common_helper->getRowsCount($result2);
		$dataRow2 = array();
		$dataRow2[] = 'Name';
		$dataRow2[] = 'Total Score';
		//$dataRow2[] = 'Math';
		//$dataRow2[] = 'Chemistry';
		$result_data2[] = $dataRow2;

		if ($rows_count > 0) {
			while ($rows2 = $common_helper->get_assoc($result2)) {
				$toatl_score = $rows2['TotalScore'] - $rows2['TotalWrong'];
				$dataRow = array();
				$dataRow[] = $rows2['name'];
				$dataRow[] = $toatl_score;
				//$dataRow[] = (int)(($rows['TotalScoreM'] - $rows['TotalCorrectMW']) / $rows['total_user']);
				//$dataRow[] = (int)(($rows['TotalScoreC'] - $rows['TotalCorrectCW']) / $rows['total_user']);
				$result_data2[] = $dataRow;
			}
		} else {
			$result_data2[] = array('', 0);
		}

		$session_user_id = $_SESSION['user_id'];

		//$con2 .= " and pq.user_id = '" . $session_user_id . "'";

		$exam_name = $common_helper->GetLabel(USER, 'user_id', 'course', $session_user_id);

		$query3 = "SELECT count(DISTINCT p.id) TotalExam, SUM(CASE WHEN p.date_of_exam > NOW() AND p.paper_status = '1' THEN 1 ELSE 0 END) Totalshedule, SUM(CASE WHEN p.id IN (select pq.paper_id from tbl_paper_score_card as pq where pq.user_id = '" . $session_user_id . "' ) THEN 1 ELSE 0 END) TotalAttend FROM tbl_papers as p where 1 AND exam_id = '" . $exam_name . "';";

		$result3 = $common_helper->runQry($query3);
		$rows3 = $common_helper->get_assoc($result3);

		if (!empty($rows3)) {
			$Totalabsend = $rows3['TotalExam'] - $rows3['Totalshedule'] - $rows3['TotalAttend'];

			$Totalshedule = $rows3['Totalshedule'];
			$TotalAttend = $rows3['TotalAttend'];

			if ($Totalabsend > 0) {
			} else {
				$Totalabsend = 0;
			}

			if ($TotalAttend > 0) {
			} else {
				$TotalAttend = 0;
			}

			if ($Totalshedule > 0) {
			} else {
				$Totalshedule = 0;
			}

			$exam_wise_data3 = "[['Status','Exam count'],['Upcoming'," . $Totalshedule . "],['Attended'," . $TotalAttend . "],['Absent'," . $Totalabsend . "]]";
		} else {
			$exam_wise_data3 = "[['Status','Exam count'],['Upcoming',0],['Attended',0],['Absent',0]]";
		}

		$result_data3 = $dataRow;


		$exam_wise_data = json_encode($result_data);
		$exam_wise_data2 = json_encode($result_data2);

		$result_data41 = array('', 0, 0, 0);
		$result_data4 = array_merge($result_data41, $result_data);
		$exam_wise_data4 = json_encode($result_data4);

		$json_line_data1 = json_encode($dataRowLineChart1);
		$json_line_data2 = json_encode($dataRowLineChart2);
		$json_line_data3 = json_encode($dataRowLineChart3);

		// echo '<pre>';
		// print_r($result_data);
		// echo '</pre>';

		echo '<script>';
		echo 'var exam_wise_data = ' . $exam_wise_data . '; ';
		echo 'var exam_wise_data2 = ' . $exam_wise_data2 . '; ';
		echo 'var exam_wise_data3 = ' . $exam_wise_data3 . '; ';

		echo 'var dataRowLineChart1 = ' . $json_line_data1 . '; ';
		echo 'var dataRowLineChart2 = ' . $json_line_data2 . '; ';
		echo 'var dataRowLineChart3 = ' . $json_line_data3 . '; ';
		echo 'console.log(exam_wise_data);';
		echo 'console.log(dataRowLineChart1);';
		echo '</script>';

		?>
	</div>
	<!--**********************************  Content body end  ***********************************-->

	<!--**********************************  Footer Start  ***********************************-->
	<?php include_once("includes/footer.php"); ?>
	<!--**********************************  Footer End  ***********************************-->
	<script src="./vendor/apexchart/apexchart.js"></script>
	<!-- Dashboard 1 -->
	<script src="./js/dashboard/dashboard-2.js"></script>
	<script src="./vendor/draggable/draggable.js"></script>
	</body>

</html>