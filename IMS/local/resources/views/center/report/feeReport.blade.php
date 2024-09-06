<title>Deposit Fee Report</title>

<p style="font-size:20px" align="center">Deposit Fee Report Between {{$from}} <small>To</small> {{$to}}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="1" style="text-align:center">

<tr>
<td><b>SNO</b></td>
<td><b>Student</b></td>
<td><b>Mobile</b></td>
<td><b>City</b></td>
<td><b>Course</b></td>
<td><b>Batch</b></td>
<td><b>Ammount</b></td>
<td><b>Date</b></td>
</tr>

<?php 
$i=0;
foreach($res as $row)
{
	//get student,student course and course name from db
	$student 		= DB::table('student')->find($row->student_id);
	$studentCourse	= DB::table('student_course')->where('student_id',$student->id)->where('status',0)->first();
	$courseName		= DB::table('course')->find($row->course_id);
	
	//date added formate change					
	$d 	= strtotime($row->date_added);
	$od = date("d-M-y", $d);
	
	//count for total
	$totalArray[] = $row->amount;
	$i++;
?>
<tr>
<td>{{$i}}</td>
<td>{{$student->first_name." ".$student->last_name}}</td>
<td>{{$student->mobile}}</td>
<td>{{$student->city}}</td>
<td>{{$courseName->name}}</td>
<td>{{$studentCourse->batch}}</td>
<td>Rs.{{$row->amount}}</td>
<td>{{$od}}</td>
</tr>
<?php } ?>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><b>Total</b></td>
<td><b>Rs.{{array_sum($totalArray)}}</b></td>
<td>&nbsp;</td>

</tr>
</table>