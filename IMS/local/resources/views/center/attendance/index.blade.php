<!DOCTYPE html>

<html lang="en">

<head>
		<title>Attendance</title>
		
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
		<?php include_once("date.php"); ?>
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
						<!-- start: BREADCRUMB --><form action="{{Asset('center/attendanceSearch/')}}" method="get">
						<div class="breadcrumb-wrapper">
							<h4 class="mainTitle no-margin"><i class="fa fa-user"></i> Staff Attendance 
							
							<?php if(Perm::check("Add Staff Attendance")){ ?>
							<a href="{{Asset('center/staff/attendance/add')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
							<?php } ?>
							
						
						<button type="submit" class="btn btn-info" style="width:10%;float:right;margin-left:20px">Search</button>
						
						<select name="staff_id" class="form-control" style="width:20%;float:right;margin-left:10px">
						<option value="">All Staff</option>
						<?php
						foreach($staffs as $staff)
						{
						?>
						<option value="{{$staff->id}}" <?php if($staff->id == $sid){ echo "selected"; } ?>>{{$staff->name}}</option>
						<?php } ?>
						</select>
						<input type="text"  name="to" id="datepicker3" class="form-control" required style="width:15%;float:right;margin-left:10px" placeholder="To" value="{{$to}}">
						<input type="text"  name="from" id="datepicker2" class="form-control" required style="width:15%;float:right;margin-left:10px" placeholder="From" value="{{$from}}">
						</form>
							</h4>
							
							
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
						<th>Staff Name</th>										
						<th>Attendance</th>						
						<th>Date Added</th>										
						<th>Option</th>
						</tr>
						</thead>
						<tbody>
						
						<?php 
						foreach($res as $row){
							
							$staff = DB::table("staff")->where("id",$row->staff_id)->first();
							
							if($row->type == 1)
							{
								$type = "<span style='color:red'> Absent</span>";
							}
							else
							{
								$type = "<span style='color:green'>Present</span>";
							}
							
							$d 		= strtotime($row->date_added);
							$dd 	= date("d-M-y", $d);
						?>
						
						<tr>
						<td width="25%">{{$staff->name}}</td>
						<td width="25%">{!!$type!!}</td>
						<td width="25%">{{$dd}}</td>
						
						
						<td width="25%">
						
						<a href="javascript::void()" class="btn btn-warning" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo Perm::addUpdate($row->added_by,$row->updated_by); ?>"><i class="fa fa-info"></i></a>
						
						<?php if(Perm::check("Edit Staff Attendance")){ ?>
						<a href="{{Asset('center/staff/attendance/edit/'.$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
						<?php } ?>
						</td>
						
						</tr>
						
						<?php } ?>
						
						</tbody>
						</table>
						
						</div>						
						</div>
						
						<?php
						
						if(isset($_GET['from'])){ $from = $_GET['from']; } else { $from = ""; }
						if(isset($_GET['to'])){ $to = $_GET['to']; } else { $to = ""; }
						if(isset($_GET['staff_id'])){ $staff_id = $_GET['staff_id']; } else { $staff_id = ""; }
						
							
						
						?>
						<center><?php $res->setPath(URL::current()); ?>
							<?php echo $res->appends([					
							
							'from' 	=> $from,												
							'to' 	=> $to,												
							'staff_id' => $staff_id											
							
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
