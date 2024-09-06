<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Student;
use App\StudentCourse;
use App\Course;
use App\Fee;
use DB;
use Validator;
use Redirect;
use Excel;
class ReportController extends Controller {
	
	/*
	@Fee Report
	*/	
	public function fee()
	{
		if (Auth()->check() && Perm::check("View Fee Report"))
		{
			$courses = Course::where('status',0)->orderBy("name",'ASC')->get();
			
			return View('center.report.fee')->with('courses',$courses);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Fee Report
	@get all data from DB with Date range & Course wise
	*/	
	public function _fee(Request $Request)
	{
		if (Auth()->check() && Perm::check("View Fee Report"))
		{
			//get fee detail from db with from,to and courseId
			$res = Fee::where(function($query) use ($Request) {
				
			$from 				= $Request->has('from') ? $Request->get('from') : null;	
			$to   				= $Request->has('to') ? $Request->get('to') : null;	
			$courseId   		= $Request->has('course_id') ? $Request->get('course_id') : null;	

			if(isset($from))
			{
				$query->where('date_added','>=',$from);
			}
			
			if(isset($to))
			{
				$query->where('date_added','<=',$to);
			}
			
			if(isset($courseId))
			{
				$query->where('course_id',$courseId);
			}			
				
			})->where('type',1)->orderBy('date_added','ASC')->get();
			
			//if no record found
			if(count($res) == 0)
			{
				return Redirect::to('center/feeReport')->with('message', 'Sorry ! No records found');
				exit;
			}
			
			//check for report type,its for excel
			if($Request->get('type') == 0)
			{
				//capture user activity
				$this->activity("Download Fee Report");
				
				//create excel file
				Excel::create('Deposit Fee Between '.$Request->get('from').' To '.$Request->get('to'), function($excel) use($res) {

				$excel->sheet('First', function($sheet) use($res)
				{		
						//define Top Header Row
						$sheet->row(1, array(
							 
							 'SNO','Student Name','Mobile','City','Course','Batch','Fee Deposit','Date Added'
						));
						
						
						$i = 1;			
						$sno = 0;			
						
						//print row data
						foreach($res as $row)
						{
							$i++;
							$sno++;
							
							//get student,student course and course name from db
							$student 		= Student::find($row->student_id);
							$studentCourse	= StudentCourse::where('student_id',$student->id)->where('status',0)->first();
							$courseName		= Course::find($row->course_id);
							
							//date added formate change					
							$d 	= strtotime($row->date_added);
							$od = date("d-M-y", $d);
							
							//count for total
							$totalArray[] = $row->amount;
							
							$sheet->row($i, array(						 
							
							$sno,
							$student->first_name." ".$student->last_name,
							$student->mobile,
							$student->city,
							$courseName->name,
							$studentCourse->batch,
							'Rs.'.$row->amount,
							$od					
							 
							));
						}
						
						//total amount
						$sheet->row(count($res) + 2, array(
							 
							 '','','','','','Total','Rs.'.array_sum($totalArray),''
						));
						
						$sheet->setOrientation('landscape');

				});
				})->export('xlsx');
			}
			//check for report type,its for plan view
			else
			{
							
				$f 		= strtotime($Request->get('from'));
				$from 	= date("d-M-y", $f);
				
				$t 		= strtotime($Request->get('to'));
				$to 	= date("d-M-y", $t);
				
				//capture user activity
				$this->activity("View Fee Report");
				
				return View('center.report.feeReport')->with(compact('res','from','to'));
			}	
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@student Report
	*/	
	public function student()
	{
		if (Auth()->check() && Perm::check("View Student Reporting"))
		{
			$courses = Course::where('status',0)->orderBy("name",'ASC')->get();
			
			return View('center.report.student')->with('courses',$courses);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Fee Report
	@get all data from DB with Date range & Course wise
	*/	
	public function _student(Request $Request)
	{
		if (Auth()->check() && Perm::check("View Student Reporting"))
		{
			//get fee detail from db with from,to and courseId
			$res = StudentCourse::where(function($query) use ($Request) {
				
			$from 				= $Request->has('from') ? $Request->get('from') : null;	
			$to   				= $Request->has('to') ? $Request->get('to') : null;	
			$courseId   		= $Request->has('course_id') ? $Request->get('course_id') : null;	
			$batch   			= $Request->has('batch') ? $Request->get('batch') : null;	

			if(isset($from))
			{
				$query->where('joining_date','>=',$from);
			}
			
			if(isset($to))
			{
				$query->where('joining_date','<=',$to);
			}
			
			if(isset($courseId))
			{
				$query->where('course_id',$courseId);
			}
			
			if(isset($batch))
			{
				$query->where('batch',$batch);
			}
				
			})->where('status',0)->orderBy('joining_date','ASC')->lists('student_id');
			
			$students = Student::where('status',$Request->get('status'))->whereIn('id',$res)->get();
			
			//if no record found
			if(count($res) == 0)
			{
				return Redirect::to('center/studentReport')->with('message', 'Sorry ! No records found');
				exit;
			}
			
			//check for report type,its for excel
			if($Request->get('type') == 0)
			{
				//capture user activity
				$this->activity("Download Students Report");
				
				//create excel file
				Excel::create('Student Report Between '.$Request->get('from').' To '.$Request->get('to'), function($excel) use($students) {

				$excel->sheet('First', function($sheet) use($students)
				{		
						//define Top Header Row
						$sheet->row(1, array(
							 
							 'SNO','Student Name','Gender','Mobile','City','Address','Email','DOB','App LoginID','Joining Date','Course','Batch','Course Status','Course Fee','Deposit Fee','Balance'
						));
						
						
						$i = 1;			
						$sno = 0;			
						
						//print row data
						
						foreach($students as $student)
						{
							$i++;
							$sno++;
							
							//get student,student course and course name from db
							$studentCourse	= StudentCourse::where('student_id',$student->id)->where('status',0)->first();
							$courseName		= Course::find($studentCourse->course_id);
							
							//date added formate change					
							$jdd 	= strtotime($studentCourse->joining_date);
							$jd 	= date("d-M-y", $jdd);
							
							//Get fee detail, total fee,deposit fee and balance
							
							$fee = Fee::where('student_id',$student->id)->where('course_id',$studentCourse->course_id)->where('type',0)->sum('amount');
							
							//deposit fee
							$deposit = Fee::where('student_id',$student->id)->where('course_id',$studentCourse->course_id)->where('type',1)->sum('amount');
							
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
							
							$sheet->row($i, array(					 
							
														
							$sno,
							$student->first_name." ".$student->last_name,
							$student->gender,
							$student->mobile,
							$student->city,
							$student->address,
							$student->email,
							$student->dob,
							$student->login_id,
							$jd,
							$courseName->name,
							$studentCourse->batch,
							$status,
							'Rs.'.$courseFee,
							'Rs.'.$deposit,
							'Rs.'.$balance							 
							));
						}
						
						$balanceLeft = array_sum($totalCourseFee) - array_sum($totalDepositFee);
						
						//total amount
						$sheet->row(count($students) + 2, array(
							 
							 '','','','','','','','','','','','','Total','Rs.'.array_sum($totalCourseFee),'Rs.'.array_sum($totalDepositFee),'Rs.'.$balanceLeft
						));
						
						$sheet->setOrientation('landscape');

				});
				})->export('xlsx');
			}
			//check for report type,its for plan view
			else
			{
							
				$f 		= strtotime($Request->get('from'));
				$from 	= date("d-M-y", $f);
				
				$t 		= strtotime($Request->get('to'));
				$to 	= date("d-M-y", $t);
				
				//capture user activity
				$this->activity("View Students Report");
				
				return View('center.report.studentReport')->with(compact('students','from','to'));
			}	
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
}
