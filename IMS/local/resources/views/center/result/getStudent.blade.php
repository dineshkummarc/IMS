<!DOCTYPE html>

<html lang="en">

<head>
		<title>Results</title>
		
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
						<!-- start: BREADCRUMB -->
						<div class="breadcrumb-wrapper">
							<h4 class="mainTitle no-margin"><i class="fa fa-plus"></i> Add New</h4>
							
							<ul class="pull-right breadcrumb">
								<li>
									<a href="{{Asset('center/home')}}"><i class="fa fa-home margin-right-5 text-large text-dark"></i>Home</a>
								</li>
								<li>
									<i class="fa fa-graduation-cap"></i> Attendance
								</li>
							</ul>
						</div>					
						
						@if (count($errors) > 0)
						<div class="alert alert-danger">
						<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
						</ul>
						</div><br>
						@endif
						
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
						
						<form action="{!! Asset('center/result/update') !!}" method="post" class="form-login">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="row">
						<div class="col-md-10">
						
						<fieldset>
						<legend>
						Declare Student Result ,After declare you can update any document with student result.
						</legend>
						<h1 style="font-size:25px">{{$course->name}} - {{$batch}}</h1><br>
						
						<div class="form-group">						
						<div class="form-group">					
						
						<span style="width:25%;float:left;color:#2c2f3b;font-size:18px;">Student Name</span>
						<span style="width:25%;float:left;margin-left:10%color:#2c2f3b;font-size:18px;">Select Result</span>
						<span style="width:25%;float:left;margin-left:10%color:#2c2f3b;font-size:18px;">Grade Or Number</span>
						<span style="width:25%;float:left;margin-left:10%color:#2c2f3b;font-size:18px;">Fee Details</span>
											
						</div>
						</div><br><br>
						
						<?php
						$i=0;
						foreach($res as $student)
						{
							$i++;
							
							$getTotalFee = DB::table("fee")->where('student_id',$student->id)->where('course_id',$course->id)->where('type',0)->sum('amount');
							
							$studentCourse = DB::table("student_course")->where('student_id',$student->id)->where('course_id',$course->id)->where('status',0)->first();
							
							$totalFee = $getTotalFee - $studentCourse->discount - $studentCourse->old_course_fee;
							
							$totalPay = DB::table("fee")->where('student_id',$student->id)->where('course_id',$course->id)->where('type',1)->sum('amount');	

							$balance = $totalFee - $totalPay;
							
														
						?>
						
						<div class="form-group">						
						<div class="form-group">						
						<input type="hidden" name="student_id[]" value="{{$student->id}}">
						
						<input type="text"  class="form-control" value="{{$student->first_name}} {{$student->last_name}}" style="width:20%;float:left" disabled>
						
						<select name="result_{{$student->id}}" class="form-control" style="width:20%;float:left;margin-left:5%">
						<option value="0">Pass</option>
						<option value="1">Fail</option>
						<option value="2" <?php if($balance != 0){ echo "selected"; } ?>>Not Declared</option>
						
						</select>					
						
						<input type="text"  name="grade_{{$student->id}}" id="gsm" class="form-control"  style="width:20%;float:left;margin-left:5%" placeholder="Grade or Number">
						
						<span style="width:25%;float:right">
						
						<?php if($balance != 0){ ?>
						<span style="color:red"><i class="fa fa-times"></i> Fee Not Clear </span> <a href="{{Asset('center/student/view/'.$student->id.'?action=ViewFee')}}" style="text-decoration:none;" target="_blank">View Detail</a>
						<?php } else { ?>
						<span style="color:green"><i class="fa fa-check"></i> Fee Clear </span> <a href="{{Asset('center/student/view/'.$student->id.'?action=ViewFee')}}" style="text-decoration:none;" target="_blank">View Detail</a>
						
						<?php } ?>
						</span>
						
						</div>
						</div>
						<br><br>
						<?php } ?>
						<br><br>
						<div class="form-group">						
						<div class="form-group">
						<button type="submit" class="btn btn-wide btn-success" onclick="return confirm('Are you sure ?')">Update</button>
						</div>
						</div>
						
						</fieldset>												
						</form>
						</div>
						</div>
						</div>
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
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="{{Asset('bower_components/Chart-js/Chart.min.js')}}"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: Packet JAVASCRIPTS -->
		<script src="{{Asset('assets/js/letter-icons.js')}}"></script>
		<script src="{{Asset('assets/js/main.js')}}"></script>
		<!-- end: Packet JAVASCRIPTS -->
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="{{Asset('assets/js/index.js')}}"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Index.init();
			});
		</script>
		
		<!-- end: JavaScript Event Handlers for this page -->
	</body>


</html>
