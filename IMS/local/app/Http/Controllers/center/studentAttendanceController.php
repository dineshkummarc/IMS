<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Student;
use App\StudentCourse;
use App\StudentAttendance;
use App\Course;
use DB;
use Validator;
use Redirect;
class studentAttendanceController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Student Attendance") || Perm::check("Add Student Attendance") || Perm::check("Delete Student Attendance"))
		{						
			$courses = Course::orderBy('name','ASC')->get();
						
			return View('center.student.viewAtnd')->with('courses',$courses);		
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new
	*/	
	public function add()
	{
		if (Auth()->check() && Perm::check("Add Student Attendance"))
		{						
			$courses = Course::orderBy('name','ASC')->get();
			$staffs     = array();
			
			return View('center.student.addAtnd')->with(compact('courses','staffs'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}	
	
	/*
	@get student
	*/	
	public function get(Request $Request)
	{
		if (Auth()->check())
		{						
			$get 	  = StudentCourse::where('course_id',$Request->get('course'))->where('batch',$Request->get('batch'))->where('status',0)->lists('student_id');
			$students = Student::whereIn('id',$get)->where('status',0)->get();
			
			$courseName  = Course::find($Request->get('course'));
			$batchName	 = $Request->get('batch');
			
			return View('center.student.getStudent')->with(compact('students','courseName','batchName'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

	/*
	@add in db
	*/	
	public function _add(Request $Request)
	{
		if (Auth()->check() && Perm::check("Add Student Attendance"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'date_added' => 'required|max:50'		
								
			]);
			
			if($validator->fails())
			{
				return redirect('center/studentAttendance/add')->withErrors($validator)->withInput();
			}
			else
			{
				$student  = $Request->get('student_id');
				
				for($i=0;$i<count($student);$i++)
				{
					$chk = $Request->get('chk_'.$student[$i]);
					
					if($chk)
					{
						$type = 0;
					}
					else
					{
						$type = 1;
					}
					//Checking for already exists
					$chk = StudentAttendance::where('student_id',$student[$i])->where('date_added',$Request->get('date_added'))->count();
					
					if($chk == 0)
					{
						$data = new StudentAttendance;
						$data->added_by 	= Auth::user()->id;
						$data->student_id	= $student[$i];
						$data->type			= $type;
						$data->date_added   = $Request->get('date_added');
						$data->added_by   	= Auth::user()->id;
						$data->save();
					}	
					
 				}	
				
				//capture user activity
				$this->activity("Add ".count($student)." Student Attendance");
				
				return Redirect::to('center/studentAttendance')->with('message', 'Saved Successfully');
			}
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}	

	/*
	@view
	*/	
	public function view(Request $Request)
	{
		if (Auth()->check() && Perm::check("View Student Attendance"))
		{						
			//get detail
			$res = StudentAttendance::where(function($query) use ($Request) {
				
			$from 				= $Request->has('from') ? $Request->get('from') : null;	
			$to   				= $Request->has('to') ? $Request->get('to') : null;	
			$student   			= $Request->has('student_id') ? $Request->get('student_id') : null;	

			if(isset($from))
			{
				$query->where('date_added','>=',$from);
			}
			
			if(isset($to))
			{
				$query->where('date_added','<=',$to);
			}
			
			if(isset($student))
			{
				$query->where('student_id',$student);
			}			
				
			})->orderBy('date_added','ASC')->paginate(100);
									
			$from 		= $Request->get('from');
			$to 		= $Request->get('to');
			$batch		= $Request->get('batch');
			$course		= Course::find($Request->get('course'));
			
			//capture user activity
			$this->activity("View Student Attendance");
			
			return View('center.student.getAtnd')->with(compact('res','from','to','batch','course'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@delete
	*/	
	public function delete($id)
	{
		if (Auth()->check() && Perm::check("Delete Student Attendance"))
		{						
			$chk = StudentAttendance::find($id);
			
			StudentAttendance::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete Student Attendance");
			
			return Redirect::to('center/studentAttendance')->with('message', 'Deleted Successfully');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
}
