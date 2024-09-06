<?php $p =  explode("/",$_SERVER['REQUEST_URI']);
	   $page = end($p);

 ?>
<div class="sidebar app-aside" id="sidebar">
				<div class="sidebar-container perfect-scrollbar">
					<div>
						<!-- start: SEARCH FORM -->
						<div class="search-form hidden-md hidden-lg">
							<a class="s-open" href="#"> <i class="ti-search"></i> </a>
							<form class="navbar-form" role="search">
								<a class="s-remove" href="#" target=".navbar-form"> <i class="ti-close"></i> </a>
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Enter search text here...">
									<button class="btn search-button" type="submit">
										<i class="ti-search"></i>
									</button>
								</div>
							</form>
						</div>
						<!-- end: SEARCH FORM -->
						<!-- start: USER OPTIONS -->
						<div class="nav-user-wrapper">
							<div class="media">
								<div class="media-left">
									<a class="profile-card-photo" href="#"><img alt="" src="{{Asset('images/icon.png')}}"></a>
								</div>
								<div class="media-body">
									<span class="media-heading text-white">Management Panel</span>
									<div class="text-small text-white-transparent">
									{{ Auth::user()->person_name }}
									</div>
								</div>
								
							</div>
						</div>
						<!-- end: USER OPTIONS -->
						<nav>
							<!-- start: MAIN NAVIGATION MENU -->
							<div class="navbar-title">
								<span>Main Navigation</span>
							</div>
							<ul class="main-navigation-menu">
								
								<li <?php if($page == "home" || $page == "setting" || $page == "batch" || $page == "course"){ ?> class="active open" <?php } ?>>
									<a href="javascript:void(0)">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="Dashboard" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Dashboard </span><i class="icon-arrow"></i>
										</div>
									</div> </a>
									<ul class="sub-menu">
										<li>
											<a href="{{Asset('center/home')}}"><i class="fa fa-home"></i> <span class="title"> Home</span> </a>
										</li>
										
										<li>
											<a href="{{Asset('center/setting')}}"><i class="fa fa-cog"></i> <span class="title"> Account Setting</span> </a>
										</li>
										
										<?php if(Perm::check("View Batch") || Perm::check("Add Batch") || Perm::check("Edit Batch") || Perm::check("Delete Batch")){ ?>									
										<li>
											<a href="{{Asset('center/batch')}}"><i class="fa fa-users"></i> <span class="title"> Manage Batch</span> </a>
										</li>
										<?php } ?>
										
										<?php if(Perm::check("View Courses") || Perm::check("Add Courses") || Perm::check("Edit Courses") || Perm::check("Delete Courses")){ ?>		
										<li>
											<a href="{{Asset('center/course')}}"><i class="fa fa-graduation-cap"></i> <span class="title"> Manage Courses</span> </a>
										</li>
										<?php } ?>										
										
									</ul>
								</li>
								
								<?php if(Perm::check("View Staff Attendance") || Perm::check("Add Staff Attendance") || Perm::check("Edit Staff Attendance") || Perm::check("View Staff") || Perm::check("Add Staff") || Perm::check("Edit Staff") || Perm::check("Delete Staff")){ ?>
								
								<li <?php if($page == "staff" || $page == "attendance" ){ ?> class="active open" <?php } ?>>
									<a href="javascript:void(0)">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="S" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Staff </span><i class="icon-arrow"></i>
										</div>
									</div> </a>
									<ul class="sub-menu">
									<?php if(Perm::check("View Staff") || Perm::check("Add Staff") || Perm::check("Edit Staff") || Perm::check("Delete Staff")){ ?>	
									
										<li>
											<a href="{{Asset('center/staff')}}"><i class="fa fa-user"></i> <span class="title"> Manage Staff</span> </a>
										</li>
										
									<?php } ?>
										
									<?php if(Perm::check("View Staff Attendance") || Perm::check("Add Staff Attendance") || Perm::check("Edit Staff Attendance")){ ?>	
										<li>
											<a href="{{Asset('center/staff/attendance')}}"><i class="fa fa-edit"></i> <span class="title"> Staff Attendance</span> </a>
										</li>
										
									<?php } ?>
										
									</ul>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("View Enquiry") || Perm::check("Add Enquiry") || Perm::check("Edit Enquiry") || Perm::check("Delete Enquiry")){ ?>	
								<li <?php if($page == "enquiry"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/enquiry')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="E" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Manage Enquiry </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								
								<?php if(Perm::check("View Students") || Perm::check("Add Students") || Perm::check("Edit Students") || Perm::check("Delete Students")){ ?>	
								<li <?php if($page == "student"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/student')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="S" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Manage Student </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								
								<?php if(Perm::check("View Student Attendance") || Perm::check("Add Student Attendance") || Perm::check("Delete Student Attendance")){ ?>	
								<li <?php if($page == "studentAttendance"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/studentAttendance')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="S" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title">Student Attendance </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("View Expense") || Perm::check("Add Expense") || Perm::check("Edit Expense") || Perm::check("Delete Expense")){ ?>	
								<li <?php if($page == "expense"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/expense')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="E" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Manage Expense </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("View Fee") || Perm::check("Add Fee") || Perm::check("Edit Fee") || Perm::check("Delete Fee")){ ?>	
								<li <?php if($page == "fee"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/fee')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="F" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Manage Fee </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("View Fee Reminders")){ ?>	
								<li <?php if($page == "reminder"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/fee/reminder')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="R" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Fee Reminder </span>
										</div>
									</div> </a>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("Add Results") || Perm::check("View Passout Students")){ ?>	
								<li <?php if($page == "result" || $page == "passOut" ){ ?> class="active open" <?php } ?>>
									<a href="javascript:void(0)">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="R" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Results </span><i class="icon-arrow"></i>
										</div>
									</div> </a>
									<ul class="sub-menu">
									<?php if(Perm::check("Add Results")){ ?>	
										<li>
											<a href="{{Asset('center/result')}}"><i class="fa fa-file"></i> <span class="title"> Manage Result</span> </a>
										</li>
									<?php } ?>
									
									<?php if(Perm::check("View Passout Students")){ ?>	
										<li>
											<a href="{{Asset('center/result/passOut')}}"><i class="fa fa-user"></i> <span class="title">Passout Student</span> </a>
										</li>
									<?php } ?>									
										
									</ul>
								</li>
								<?php } ?>
								
								<?php if(Perm::check("View Fee Report") || Perm::check("View Student Reporting") || Perm::check("View Expense Reporting")){ ?>	
								
								
								<li <?php if($page == "feeReport" || $page == "studentReport" || $page == "staffReport" ){ ?> class="active open" <?php } ?>>
									<a href="javascript:void(0)">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="R" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Reporting </span><i class="icon-arrow"></i>
										</div>
									</div> </a>
									<ul class="sub-menu">
										<?php if(Perm::check("View Fee Report")){ ?>
										<li>
											<a href="{{Asset('center/feeReport')}}"><i class="fa fa-credit-card-alt"></i> <span class="title"> Fee Report</span> </a>
										</li>
										<?php } ?>
										
										<?php if(Perm::check("View Student Reporting")){ ?>
										<li>
											<a href="{{Asset('center/studentReport')}}"><i class="fa fa-user"></i> <span class="title">Students Report</span> </a>
										</li>	
										<?php } ?>
										
										<?php if(Perm::check("View Expense Reporting")){ ?>
										<li>
											<a href="{{Asset('center/expenseReport')}}"><i class="fa fa-file"></i> <span class="title">Expense Report</span> </a>
										</li>	
										<?php } ?>
										
									</ul>
								</li>
								
								<?php } ?>
								
								
								<?php if(Perm::check("View Todo") || Perm::check("Add Todo") || Perm::check("Edit Todo") || Perm::check("Delete Todo")){ ?>
								<li <?php if($page == "Todo"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/Todo')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="T" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Todo List </span>
										</div>
									</div> </a>
								</li>
								
								<?php } ?>								
								
																
								<li <?php if($page == "Appsetting" || $page == "assignment" || $page == "download" ){ ?> class="active open" <?php } ?>>
									<a href="javascript:void(0)">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="A" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Mobile App </span><i class="icon-arrow"></i>
										</div>
									</div> </a>
									<ul class="sub-menu">
										
										<?php if(Perm::check("Manage Mobile App")){ ?>										
										<li>
											<a href="{{Asset('center/Appsetting')}}"><i class="fa fa-mobile"></i> <span class="title"> Manage App </span> </a>
										</li>
										<?php } ?>
										
										<?php if(Perm::check("Upload Assignment")){ ?>
										<li>
											<a href="{{Asset('center/app/assignment')}}"><i class="fa fa-edit"></i> <span class="title">Upload Assignment</span> </a>
										</li>
										<?php } ?>
										
										
										<li>
											<a href="https://play.google.com/store/apps/details?id=com.ims247" target="_blank"><i class="fa fa-download"></i> <span class="title">Download App</span> </a>
										</li>
										
										<?php if(Perm::check("Send Notification in App")){ ?>
										<li>
											<a href="{{Asset('center/app/notification')}}"><i class="fa fa-bell"></i> <span class="title">Send Notification</span> </a>
										</li>
										<?php } ?>
										
									</ul>
								</li>
								
								<li <?php if($page == "logout"){ ?> class="active open" <?php } ?>>
									<a href="{{Asset('center/logout')}}">
									<div class="item-content">
										<div class="item-media">
											<div class="lettericon" data-text="Logout" data-size="sm" data-char-count="2"></div>
										</div>
										<div class="item-inner">
											<span class="title"> Logout </span>
										</div>
									</div> </a>
								</li>
								
								
								
							</ul>
							<!-- end: CORE FEATURES -->
						</nav>
					</div>
				</div>
			</div>