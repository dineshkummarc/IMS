<!DOCTYPE html>

<html lang="en">

<head>
		<title>Student</title>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<!-- end: META -->
		<!-- start: GOOGLE FONTS -->
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<!-- end: GOOGLE FONTS -->
		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="{{Asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/themify-icons/themify-icons.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/flag-icon-css/css/flag-icon.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/animate.css/animate.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/switchery/dist/switchery.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/ladda/dist/ladda-themeless.min.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/slick.js/slick/slick.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/slick.js/slick/slick-theme.css')}}">
		<!-- end: MAIN CSS -->
		
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="stylesheet" href="{{Asset('bower_components/sweetalert/dist/sweetalert.css')}}">
		<link rel="stylesheet" href="{{Asset('bower_components/DataTables/media/css/dataTables.bootstrap.min.css')}}">
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: Packet CSS -->
		<link rel="stylesheet" href="{{Asset('assets/css/styles.css')}}">
		<link rel="stylesheet" href="{{Asset('assets/css/plugins.css')}}">
		<link rel="stylesheet" href="{{Asset('assets/css/themes/lyt1-theme-1.css')}}" id="skin_color">
		<!-- end: Packet CSS -->
		<!-- Favicon -->
		<link rel="shortcut icon" href="favicon.ico" />
	</head>
	<!-- end: HEAD -->
	<body>
		<div id="app">
			<!-- sidebar -->
			{!!View('center.menu') !!}
			<!-- / sidebar -->
			<div class="app-content">
				<!-- start: TOP NAVBAR -->
				<header class="navbar navbar-default navbar-static-top">
					<!-- start: NAVBAR HEADER -->
					{!!View('center.nav')!!}
					<!-- end: NAVBAR HEADER -->
					<!-- start: NAVBAR COLLAPSE -->
					{!!View('center.top')!!}
					<!-- end: NAVBAR COLLAPSE -->
				</header>
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: BREADCRUMB -->
						<div class="breadcrumb-wrapper">
							<h4 class="mainTitle no-margin"><i class="fa fa-users"></i> Manage Students 
							<?php if(Perm::check("Add Students")){ ?>
							<a href="{{Asset('center/student/add')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a> 
							<?php } ?>
							
							<?php 
							if(isset($_GET['query']))
							{							
							?>
							Showing Results For : {{$_GET['query']}}
							<?php } ?>
							
							
							</h4>
							
							<ul class="pull-right breadcrumb">
								<li>
									<a href="{{Asset('center/home')}}"><i class="fa fa-home margin-right-5 text-large text-dark"></i>Home</a>
								</li>
								<li>
									Student
								</li>
							</ul>
						</div>					
						
						@if(Session::has('message'))
						<Br><p class="text-center list-group-item list-group-item-success">{{ Session::get('message') }}</p>
						@endif
						
						<!-- end: BREADCRUMB -->
						<!-- start: FEATURED BOX LINKS -->
						
						<div class="container-fluid container-fullw">
						<div class="row">
						<div class="col-md-12">
						<div class="panel panel-white">
						<div class="panel-body">					
						
						<table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
						<thead>
						<tr>
						<th>Name</th>										
						<th>Course</th>						
						<th>Batch</th>						
						<th>Joining Date</th>					
						<th>Mobile</th>					
						<th>Login id</th>					
											
						<th>Option</th>
						</tr>
						</thead>
						<tbody>
						
						<?php 
						foreach($res as $row){
							
						$getCourse 	= DB::table("student_course")->where('student_id',$row->id)->where('status',0)->first();
						$course 	= DB::table("course")->where("id",$getCourse->course_id)->first();
						$d 			= strtotime($getCourse->joining_date);
						$dd 		= date("d-M-y", $d);
						?>
						
						<tr>
						<td width="15%">{{$row->first_name}} {{$row->last_name}}</td>
						<td width="15%">{{$course->name}}</td>
						<td width="10%">{{$getCourse->batch}}</td>
						<td width="12%">{{$dd}}</td>
						<td width="13%">{{$row->mobile}}</td>
						<td width="8%">{{$row->login_id}}</td>
						
						<td width="27%">
						
						<a href="javascript::void()" class="btn btn-warning" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo Perm::addUpdate($row->added_by,$row->updated_by); ?>"><i class="fa fa-info"></i></a>
						
						<a href="{{Asset('center/student/view/'.$row->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-info" title="" data-original-title="View Student Detail"><i class="fa fa-eye"></i></a>
						
						<?php if(Perm::check("Edit Students")){ ?>
						<a href="{{Asset('center/student/edit/'.$row->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-success" data-original-title="Edit Student Detail"><i class="fa fa-edit"></i> </a>
						<?php } ?>
						
						<?php if(Perm::check("Change Student Course")){ ?>
						<a href="{{Asset('center/student/courseEdit/'.$row->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-warning" data-original-title="Change Student Course"><i class="fa fa-arrows"></i> </a>
						<?php } ?>
						
						<?php if(Perm::check("Add Student Extra Charges")){ ?>
						<a href="{{Asset('center/student/extraCharge/'.$row->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-primary" data-original-title="Add Extra Charges"><i class="fa fa-plus"></i> </a>
						<?php } ?>
						
						<?php if(Perm::check("Delete Students")){ ?>
						<a href="{{Asset('center/student/delete/'.$row->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm('Are You Sure ?')" data-original-title="Delete Student"><i class="fa fa-trash"></i> </a>					
						<?php } ?>
						
						</td>
						
						</tr>
						
						<?php } ?>
						
						</tbody>
						</table>
						
						</div>						
						</div>
						<?php
						if(isset($_GET['query']))
						{
							$query = $_GET['query'];
						}
						else
						{
							$query = "";
						}
						?>
						<center><?php $res->setPath(URL::current()); ?>
							<?php echo $res->appends([					
							
							'query' => $query													
							
							])->render(); ?>  </center>
						</div>
						</div>
						</div>
						 	
						
				</div>
				</div>
				</div>
			<!-- start: FOOTER -->
			{!!View('center.footer')!!}
			<!-- end: FOOTER -->
			
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="{{Asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
		<script src="{{Asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
		<script src="{{Asset('bower_components/components-modernizr/modernizr.js')}}"></script>
		<script src="{{Asset('bower_components/js-cookie/src/js.cookie.js')}}"></script>
		<script src="{{Asset('bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js')}}"></script>
		<script src="{{Asset('bower_components/jquery-fullscreen/jquery.fullscreen-min.js')}}"></script>
		<script src="{{Asset('bower_components/switchery/dist/switchery.min.js')}}"></script>
		<script src="{{Asset('bower_components/jquery.knobe/dist/jquery.knob.min.js')}}"></script>
		<script src="{{Asset('bower_components/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js')}}"></script>
		<script src="{{Asset('bower_components/slick.js/slick/slick.min.js')}}"></script>
		<script src="{{Asset('bower_components/jquery-numerator/jquery-numerator.js')}}"></script>
		<script src="{{Asset('bower_components/ladda/dist/spin.min.js')}}"></script>
		<script src="{{Asset('bower_components/ladda/dist/ladda.min.js')}}"></script>
		<script src="{{Asset('bower_components/ladda/dist/ladda.jquery.min.js')}}"></script>
		<script src="{{Asset('bower_components/Chart-js/Chart.min.js')}}"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="{{Asset('bower_components/sweetalert/dist/sweetalert.min.js')}}"></script>
		<script src="{{Asset('bower_components/DataTables/media/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{Asset('bower_components/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		
		<!-- start: Packet JAVASCRIPTS -->
		<script src="{{Asset('assets/js/letter-icons.js')}}"></script>
		<script src="{{Asset('assets/js/main.js')}}"></script>
		<!-- end: Packet JAVASCRIPTS -->
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="{{Asset('assets/js/index.js')}}"></script>
		<script src="{{Asset('assets/js/table-data.js')}}"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				
				$('#sample_1').dataTable({          
				"bSort": false,
				"paging": false,
				
				});
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
	</body>


</html>
