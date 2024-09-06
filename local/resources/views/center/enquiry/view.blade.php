<!DOCTYPE html>

<html lang="en">

<head>
		<title>{{$res->first_name}} {{$res->last_name}}</title>
		
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
							<h4 class="mainTitle no-margin"><i class="fa fa-user"></i> {{$res->first_name}} {{$res->last_name}} 
							
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
						<?php if(isset($_GET['action']))
						{
								$feeClass = "in active";
								$dtClass  = "";
						}
						else
						{
							$feeClass = "";
							$dtClass  = "in active";
						}
						?>			
						
						
						<div class="tabbable">
							<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
								<li class="active">
									<a data-toggle="tab" href="#panel_overview"> Overview </a>
								</li>
								
								<li>
									<a data-toggle="tab" href="#panel_projects"> Fee Details </a>
								</li>
							</ul>
							<div class="tab-content">
								<div id="panel_overview" class="tab-pane fade <?php echo $dtClass; ?>">
								<div class="row">
								<div class="col-sm-5 col-md-4">
								<div class="user-left">
								<div class="center">											
								<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="user-image">									
								<div class="fileinput-new thumbnail"><img src="{{$imgSrc}}" alt=""><br>
								<p style="color:green">App Login ID : {{$res->login_id}}</p>
								</div>
								</div>
								
								<form action="{{Asset('center/student/img/'.$res->id)}}" enctype="multipart/form-data" method="post">
								<div class="form-group">
								<label>Chnage User Image</label>
								<div class="form-group">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="file" name="img" class="form-control" required style="width:77%;float:left">
								<input type="submit" name="submit" value="Save" class="btn btn-info" style="float:right">
								</div>
								</div>
								</form>
								
								</div>																
								</div>																
								</div>																
								</div>
								<div class="col-sm-7 col-md-8">
								<div class="user-right">
								<h2><i class="fa fa-user"></i> {{$res->first_name}} {{$res->last_name}}</h2>
								<hr><p style="font-size:15px">Personal Detail</p>
								<table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
								
								<tr>
								<td><b>First Name</b></td>
								<td>{{$res->first_name}}</td><td>
								<b>Last Name</b></td>
								<td>{{$res->last_name}}</td>
								</tr>
								
								<tr>
								<td><b>Mobile</b></td>
								<td>{{$res->mobile}}</td><td>
								<b>Other Contact</b></td>
								<td>{{$res->contact_no}}</td>
								</tr>
								
								<tr>
								<td><b>Email</b></td>
								<td>{{$res->email}}</td><td>
								<b>DOB</b></td>
								<td>{{$res->dob}}</td>
								</tr>
								
								<tr>
								<td><b>State</b></td>
								<td>{{$res->state}}</td><td>
								<b>City</b></td>
								<td>{{$res->city}}</td>
								</tr>
								
								<tr>
								<td><b>Address</b></td>
								<td colspan="3">{{$res->state}}</td>											
								</tr>
								
								</table><br>
								<p style="font-size:15px">Course Detail</p>
								<?php 
								foreach($get as $getCourse)
								{
									$course = DB::table('course')->where('id',$getCourse->course_id)->first();
									
								?>
								<table class="table table-striped table-bordered table-hover table-full-width" id="sample_2">
								
								<tr>
								<td><b>Course</b></td>
								<td>{{$course->name}}</td><td>
								<b>Batch</b></td>
								<td>{{$getCourse->batch}}</td>
								</tr>
								
								<tr>
								<td><b>Course Fee</b></td>
								<td>Rs.{{$getCourse->course_fee}}</td><td>
								<b>Discount</b></td>
								<td>{{$getCourse->discount}}</td>
								</tr>
								
								<tr>
								<td><b>Roll No</b></td>
								<td>{{$getCourse->roll_no}}</td><td>
								<b>Remark</b></td>
								<td>{{$getCourse->reason}}</td>
								</tr>
								
								<?php
								//Join date
								$d 		= strtotime($getCourse->joining_date);
								$dd 	= date("d-M-y", $d);
								?>
								<tr>
								<td><b>Joining Date</b></td>
								<td>{{$dd}}</td><td>
								<b>Status</b></td>
								<td>
								<?php
								if($getCourse->status == 0){ echo "<span style='color:green'>Active</span>"; }
								if($getCourse->status == 1){ echo "<span style='color:red'>DeActiveted</span>"; }
								?>										
								</td>
								</tr>
								
								</table>
								<?php } ?>
								</div>																
								</div>		
								
								</div>																
								</div>																
									
								<div id="panel_projects" class="tab-pane fade <?php echo $feeClass; ?>">
								<p style="font-size:16px">Course Fee</p>	
								<table class="table table-striped table-bordered table-hover table-full-width" id="sample_3">
								
								<tr>
								<td><b>Course Name</b></td>
								<td><b>{{$course->name}}</b></td>
								</tr>
								
								<?php
								if(count($fees) > 0){
								foreach($fees as $fee)
								{
									$totalFeeArray[] = $fee->amount;
								?>
								
								<tr>
								<td><b>{{$fee->naration}}</b></td>
								<td>Rs.{{$fee->amount}}</td>
								</tr>
								
								<?php 
								}
								}
								$totalFee = array_sum($totalFeeArray) - $res->discount;
								
								unset($totalFeeArray);
								?>
								
								<?php
								if($crs->discount){
								?>
								<tr>
								<td><b>Discount</b></td>
								<td>Rs.{{$crs->discount}}</td>
								</tr>
								<?php } ?>
								
								<?php
								if($crs->old_course_fee){
								?>
								<tr>
								<td><b>Old Course Fee</b></td>
								<td>Rs.{{$crs->old_course_fee}}</td>
								</tr>
								<?php } ?>
								
								<?php $feeAmount = $totalFee - $crs->discount - $crs->old_course_fee; ?>
								<tr>
								<td><b>Total</b></td>
								<td><b>Rs.{{ $feeAmount }}</b></td>
								</tr>
								
								</table>
								<?php if(count($Payfees) > 0){ ?>
								<br>
								<p style="font-size:16px">Deposited Fee</p>	
								<table class="table table-striped table-bordered table-hover table-full-width" id="sample_4">
								<tr>
								<td><b>Narration</b></td>
								<td>Amount</td>
								<td>Date Added</td>
								</tr>
								
								<?php											
								foreach($Payfees as $Pfee)
								{
									$totalPayArray[] = $Pfee->amount;
									
									$d 		= strtotime($Pfee->date_added);
									$dd 	= date("d-M-y", $d);
								?>
								
								<tr>
								<td width="50%"><b>{{$Pfee->naration}}</b></td>
								<td width="30%">Rs.{{$Pfee->amount}}</td>
								<td width="20%">{{$dd}} </td>
								</tr>
								
								<?php 											
								}
								$totalpay = array_sum($totalPayArray);
								unset($totalPayArray);
								?>
								
								
								
								<tr>
								<td><b>Total </b></td>
								<td><b>Rs.{{ $totalpay}}</b></td>
								<td><b></b></td>
								</tr>
								
								<tr>
								<td><b></b></td>
								<td><b style="color:red;">Total Fee Ammount</b></td>
								<td><b style="color:red;">Rs.{{$feeAmount}}</b></td>
								</tr>
								
								<tr>
								<td><b></b></td>
								<td><b style="color:red;">Total Deposit Ammount</b></td>
								<td><b style="color:red;">Rs.{{$totalpay}}</b></td>
								</tr>
								
								<tr>
								<td><b></b></td>
								<td><b style="color:red;">Total Balance</b></td>
								<td><b style="color:red;">Rs.{{$feeAmount - $totalpay}}</b></td>
								</tr>
								
								</table>
								<?php } ?>	
								</div>
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
