<title>Student Report</title>

<p style="font-size:20px" align="center">Student Report Between {{$from}} <small>To</small> {{$to}}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="1" style="text-align:center">

<tr>
<td><b>SNO</b></td>
<td><b>Student</b></td>
<td><b>Mobile</b></td>
<td><b>Joining Date</b></td>
<td><b>Course</b></td>
<td><b>Batch</b></td>
<td><b>Course Fee</b></td>
<td><b>Deposit Fee</b></td>
<td><b>Balance</b></td>
</tr>

<?php 
$i=0;
foreach($students as $student)
{
	//get student,student course and course name from db
	$studentCourse	= DB::table('student_course')->where('student_id',$student->id)->where('status',0)->first();
	$courseName		= DB::table('course')->where('id',$studentCourse->course_id)->first();
	
	//date added formate change					
	$jdd 	= strtotime($studentCourse->joining_date);
	$jd 	= date("d-M-y", $jdd);
	
	//Get fee detail, total fee,deposit fee and balance	
	$fee = DB::table('fee')->where('student_id',$student->id)->where('course_id',$studentCourse->course_id)->where('type',0)->sum('amount');
	
	//deposit fee
	$deposit = DB::table('fee')->where('student_id',$student->id)->where('course_id',$studentCourse->course_id)->where('type',1)->sum('amount');
	
	//get total
	$courseFee = $fee - $studentCourse->discount - $studentCourse->old_course_fee;
	$balance   = $courseFee - $deposit;
	
	$totalCourseFee[] 	= $courseFee;
	$totalDepositFee[] 	= $deposit;
	
	if($student->status == 0)
	{
		$status = "Active";
	}
	else
	{
		$status = "Completed";
	}
	$i++;
?>
<tr>
<td>{{$i}}</td>
<td>{{$student->first_name." ".$student->last_name}}</td>
<td>{{$student->mobile}}</td>
<td>{{$jd}}</td>
<td>{{$courseName->name}}</td>
<td>{{$studentCourse->batch}}</td>
<td>Rs.{{$courseFee}}</td>
<td>Rs.{{$deposit}}</td>
<td>Rs.{{$balance}}</td>
</tr>
<?php } ?>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><b>Total</b></td>
<td><b>Rs.{{array_sum($totalCourseFee)}}</b></td>
<td><b>Rs.{{array_sum($totalDepositFee)}}</b></td>
<td><b>Rs.{{array_sum($totalCourseFee) - array_sum($totalDepositFee)}}</b></td>

</tr>
</table>