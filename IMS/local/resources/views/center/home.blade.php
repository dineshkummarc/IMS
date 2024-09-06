<!DOCTYPE html>

<html lang="en">

<head>
<title>Dashboard</title>

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
</head>
<!-- end: HEAD -->
<body>
<div id="app">
<!-- sidebar -->
{!! View('center.menu') !!}
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
		<h2 class="mainTitle no-margin">Dashboard</h2>
		
		<ul class="pull-right breadcrumb">
			<li>
				<a href="{{Asset('center/home')}}"><i class="fa fa-home margin-right-5 text-large text-dark"></i>Home</a>
			</li>
			<li>
				Dashboard
			</li>
		</ul>
	</div>					
	
	<!-- end: BREADCRUMB -->
	<!-- start: FEATURED BOX LINKS -->
	<?php
	$d 	= strtotime($res->valid_date);
	$od = date("d-M-Y", $d);
	?>
	
	<div class="container-fluid container-fullw">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
						<h5 class="over-title margin-bottom-15"><span class="text-bold">Over View</span> 
						<?php 
						
						$student 	 = DB::table('student')->where('status',0)->count();
						$Passstudent = DB::table('student')->where('status',1)->count();
						$course 	 = DB::table('course')->where('status',0)->count();
						$staff 	     = DB::table('staff')->where('status',0)->count();
						$enquiry 	 = DB::table('enquiry')->where('status',0)->count();
						$todo 	 	 = DB::table('todo')->where('status',0)->count();
							
						?>
<div class="container-fluid container-fullw padding-bottom-10">
<div class="row">
<div class="col-md-12 col-lg-12">
<div class="panel panel-white no-radius">

<div class="row">

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-users text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Total Students : {{ $student }}</span>
</div>
</div>

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-graduation-cap text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Passout Students : {{ $Passstudent }}</span>
</div>
</div>

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-book text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Total Courses : {{ $course }}</span>
</div>
</div>
</div>
</div>
<div class="panel panel-white no-radius">
<div class="row">

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-male text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Total Staff : {{ $staff }}</span>
</div>
</div>

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-envelope text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Enquirys : {{ $enquiry }}</span>
</div>
</div>

<div class="col-xs-4 no-padding border-right">
<div class="padding-10 text-center">
<i class="fa fa-edit text-azure large-letters"></i>
<span class="text-extra-large block margin-top-15">Todo : {{ $todo }}</span>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
<?php if(Perm::check("View Todo")){ ?>
	<div class="row">
	<div class="col-md-12">
	<div class="panel panel-white">
	<div class="panel-body"><h5><i class="fa fa-list"></i> To Do List</h5></div>
	<table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
	<thead>
	<tr>
	<th>Todo</th>
	<th>Date Added</th>					
				
	<th>Option</th>
	</tr>
	</thead>
	<tbody>
	
	<?php 						
	foreach($todoRes as $row){											
							
	?>
	
	
	<tr>
	<td width="50%">{{$row->description}}</td>
	<td width="20%">{{$row->date_added}}</td>
	
	<td width="30%">
	<?php 
	if($row->status == 0){
	?>
	<a href="{{Asset('center/Todo/done/'.$row->id)}}" class="btn btn-info" onclick="return confirm('Are You Sure ? Want to complete this?')"><i class="fa fa-check"></i> Active</a>
	<?php } else { ?>
	
	<a href="#" class="btn btn-warning"><i class="fa fa-check"></i> Completed</a>
	
	<?php } ?>
	
	<a href="{{Asset('center/Todo/edit/'.$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
	
	<a href="{{Asset('center/Todo/delete/'.$row->id)}}" class="btn btn-danger" onclick="return confirm('Are You Sure ?')"><i class="fa fa-trash"></i> Delete</a>					
	
	</td>
	
	</tr>
	
	<?php } ?>
	
	</tbody>
	</table>
	
	</div><a href="{{Asset('center/todo')}}" class="btn btn-info" style="float:right;margin-right:6
	%"><i class="fa fa-eye"></i> View All</a></div></div></div>					
						
	</div>
<?php } ?>
<?php if(Perm::check("View Fee Reminders")){ ?>				
	<div class="row">
	<div class="col-md-12">
	<div class="panel panel-white">
	<div class="panel-body"><h5><i class="fa fa-credit-card-alt"></i> Fee Reminder From This Weak</h5></div>
	<table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
	<thead>
	<tr>
	<th>Name</th>
	<th>Course</th>					
	<th>Batch</th>					
	<th>Mobile</th>					
	<th>Due Date</th>								
	<th>Balance</th>								
	<th>Option</th>
	</tr>
	</thead>
	<tbody>
	
	<?php				
	foreach($getStudent as $stu)
	{
	  $courseName = DB::table("student_course")->where('student_id',$stu->id)->where('status',0)->first();
	  $crs        = DB::table("course")->where('id',$courseName->course_id)->first();
	  
	  //get deposit fee detail
	  $feeDeposit = DB::table("fee")->where('student_id',$stu->id)->where('course_id',$crs->id)->where('status',0)->where('type',1)->sum('amount');
	  
	  //get course fee detail
	  $fee  = DB::table("fee")->where('student_id',$stu->id)->where('course_id',$crs->id)->where('status',0)->where('type',0)->sum('amount');
	  
	  //calculate balance
	  $totalFee = $fee - $courseName->discount - $courseName->old_course_fee;
	  $balance  = $totalFee - $feeDeposit;
	  
	  //get due date
	  $getFee = DB::table("fee")->where('student_id',$stu->id)->where('course_id',$crs->id)->where('status',0)->where('type',1)->orderBy('id','DESC')->take(1)->first();
	  
	  $last 	= strtotime($getFee->due_date);
	  $lastDate = date("d-M-Y", $last);
	?>
	
	
	<tr>
	<td width="15%">{{$stu->first_name}} {{$stu->last_name}}</td>
	<td width="15%">{{$crs->name}}</td>
	<td width="15%">{{$courseName->batch}}</td>
	<td width="12%">{{$stu->mobile}}</td>
	<td width="15%">{{$lastDate}}</td>
	<td width="15%">Rs.{{$balance}}</td>
	
	<td width="18%">			
	
	
	<a href="{{Asset('center/student/view/'.$stu->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-info" title="" data-original-title="View Student Detail" target="_blank"><i class="fa fa-eye"></i></a>
	
	<a href="{{Asset('center/fee/view/'.$stu->id)}}" data-placement="top" data-toggle="tooltip" class="btn btn-success" title="" data-original-title="Add Student Fee" ><i class="fa fa-plus"></i></a>			
	
	</td>
	
	</tr>
	
	<?php } ?>
	
	</tbody>
	</table>
	
	</div><a href="{{Asset('center/fee/reminder')}}" class="btn btn-info" style="float:right;margin-right:6
	%"><i class="fa fa-eye"></i> View All</a></div></div></div>					
						
</div>
<?php } ?>

<?php if(Auth::user()->id == env("admin_id")){ ?>
<!--User Activity & Fee Section -->
<div class="container-fluid container-fullw padding-bottom-10">
<div class="row">
<div class="col-md-12 col-lg-12">
<div class="panel panel-white no-radius">
<div class="panel-heading border-bottom">
<h4 class="panel-title"><i class='fa fa-star'></i> Today User Activity

<a href="{{ Asset('center/activity') }}" class="btn btn-info" style="color:white;float:right;margin-bottom:20px"><i class='fa fa-eye'></i> View Previous Activity</a><br><br>

</h4>
</div>

<div class="panel-body">
<ul class="timeline-xs margin-top-20 margin-bottom-20">

<?php
$activities = DB::table("user_activity")->where("date_added",date("Y-m-d"))->orderBy("id","DESC")->get();
foreach($activities as $activity)
{
	$user = DB::table("users")->where('id',$activity->user_id)->first();
	$time = date('h:i:s:A', strtotime($activity->time_added));
?>

<li class="timeline-item success">
<div class="margin-left-15">
<div class="text-muted text-small">
{{ $time }}
</div>
<p>
<a class="text-info"> {{ $user->person_name }} </a>
{{ $activity->notes }}
</p>
</div>
</li>

<?php } ?>
</ul>
</div>

</div>
</div>

</div>
</div>
</div>
</div>

<?php } ?>


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

