<?php namespace App\Http\Controllers\center;

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
use Image;
class CourseChnageController extends Controller {
	
	/*
	@Index page
	*/	
	public function index($id)
	{
		if (Auth()->check())
		{
			$chk = Student::find($id);
			
			$res     = StudentCourse::where('student_id',$id)->where('status',0)->first();;
				
			$courses = Course::get();			
								
			return View('center.student.courseEdit')->with(compact('res','courses'));			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add
	*/	
	public function add(Request $Request,$id)
	{
		if (Auth()->check())
		{
			$res 		= Student::find($id);
			$getCourse  = StudentCourse::find($Request->get('old_course'));
			
			//get sum of deposit fee
			$oldFee = Fee::where('student_id',$id)->where('course_id',$getCourse->course_id)->where('type',1)->where('status',0)->sum('amount');
			
			//update status of old course fee
		    Fee::where('student_id',$id)->where('student_id',$id)->update(['status' => 1]);
			
			//update old course & reason
			$up = StudentCourse::find($Request->get('old_course'));
			$up->status = 1;
			$up->reason = $Request->get('reason');
			$up->save();
			
			//update new course
			$data = new StudentCourse;
			$data->added_by 		= Auth::user()->id;
			$data->student_id 		= $id;
			$data->course_id  		= $Request->get('course_id');
			$data->course_fee  		= $Request->get('course_fee');
			$data->batch  			= $Request->get('batch');
			$data->discount  		= $Request->get('discount');
			
			if($Request->get('old_course_fee') && $Request->get('old_course_fee') == "Yes")
			{
				$data->old_course_fee = $oldFee;
			}
			
			$data->joining_date 	= $Request->get('joining_date');				
			$data->save();
			
			//update Fee				
			$fees = new Fee;
			$fees->added_by 	= Auth::user()->id;
			$fees->student_id  = $id;
			$fees->course_id   = $Request->get('course_id');
			$fees->amount 	   = $Request->get('course_fee');
			$fees->naration    = "Course Fee";
			$fees->type        = 0;
			$fees->date_added  = $Request->get('joining_date');
			$fees->save();
			
			//capture user activity
			$courseName = Course::find($Request->get("course_id"));
			$this->activity("Change Student Course - ".$res->first_name." ".$res->last_name." (".$courseName->name.")");
			
			return Redirect::to('center/student')->with('message', 'Course Changed Successfully');		
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

}
