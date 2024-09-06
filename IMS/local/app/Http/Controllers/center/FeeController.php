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
use Image;
class FeeController extends Controller {
	
	/*
	@Index Page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Fee") || Perm::check("Add Fee") || Perm::check("Edit Fee") || Perm::check("Delete Fee"))
		{
			if(Perm::check("View Fee"))
			{
				$res = Student::where('status',0)->orderBy("first_name",'ASC')->get();
			}
			else
			{
				$res = [];
			}			
			
			return View('center.fee.index')->with('res',$res);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@fee reminder interval 7 days
	*/	
	public function reminder()
	{
		if (Auth()->check() && Perm::check("View Fee Reminders"))
		{
			//Date calculate
			$toDate 	= date("Y-m-d");			
			$dateStop  	= date('Y-m-d', strtotime($toDate . ' +7 day'));
			
			$fees = Fee::where('due_date','>=',$toDate)->where('due_date','<=',$dateStop)->select('student_id')->distinct()->orderBy('due_date','ASC')->lists('student_id');
			
			$getStudent = Student::whereIn('id',$fees)->paginate(100);
			
			return View('center.fee.reminder')->with(compact('getStudent'));
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@View
	*/	
	public function view($id)
	{
		if (Auth()->check() && Perm::check("View Fee"))
		{
			$chk = Student::find($id);
			
			//get Student Course
			$getCourse = StudentCourse::where('student_id',$id)->where('status',0)->first();
			
			//get deposit fee details
			$details   = Fee::where('student_id',$id)->where('course_id',$getCourse->course_id)->where('type',1)->where('status',0)->orderBy('id','DESC')->get();
			
			//total fees
			$fees   = Fee::where('student_id',$id)->where('course_id',$getCourse->course_id)->where('type',0)->sum('amount');
			
			//get student details
			$res 	 = Student::where('status',0)->orderBy("first_name",'ASC')->get();
					
			return View('center.fee.index')->with(compact('res','details','chk','fees'));
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@add
	*/	
	public function add()
	{
		if (Auth()->check() && Perm::check("Add Fee"))
		{
			$res = Student::where('status',0)->orderBy("first_name",'ASC')->get();
			
			return View('center.fee.add')->with('res',$res);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add
	*/	
	public function _add(Request $Request)
	{
		if (Auth()->check() && Perm::check("Add Fee"))
		{
			//get course & student details
			$student = $Request->get('student_id');
			$chk     = Student::find($student);
			$course  = StudentCourse::where('student_id',$student)->where('status',0)->first();
			
			//check for total fee
			$totalFee = Fee::where('student_id',$student)->where('course_id',$course->course_id)->where('type',1)->where('status',0)->sum('amount');
			
			//total fee
			$feeTotal = $totalFee + $Request->get('amount');				
			$feeCourse = $course->course_fee - $course->discount - $course->old_course_fee;
			
			if($feeTotal > $feeCourse)
			{
				return Redirect::to('center/fee/add')->with('message', 'Sorry ! Your Fee Amount is greater then total course fee');
			}
			
			Fee::where('student_id',$student)->update(['due_date' => null]);
			//add new fee
			$fee = new Fee;
			$fee->added_by 		= Auth::user()->id;
			$fee->student_id 	= $student;
			$fee->course_id 	= $course->course_id;
			$fee->amount    	= $Request->get('amount');
			$fee->naration     	= $Request->get('naration');
			$fee->date_added    = $Request->get('date_added');
			$fee->due_date    	= $Request->get('due_date');
			$fee->type    		= 1;
			$fee->save();
			
			//capture user activity
			$this->activity("Add Student Fee - ".$chk->first_name." ".$chk->last_name);
			
			return Redirect::to('center/fee/view/'.$student);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Edit
	*/	
	public function edit($id,$sid)
	{
		if (Auth()->check() && Perm::check("Edit Fee"))
		{
			$chk = Student::find($sid);
			
			$res = Fee::find($id);
				
			return View('center.fee.edit')->with(compact('res','chk'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Edit
	*/	
	public function _edit(Request $Request,$id,$sid)
	{
		if (Auth()->check() && Perm::check("Edit Fee"))
		{			
			//get student & course details
			$chk = Student::find($sid);
			$course  = StudentCourse::where('student_id',$sid)->where('status',0)->first();
			
			//check for total fee
			$totalFee = Fee::where('student_id',$sid)->where('id','!=',$id)->where('course_id',$course->course_id)->where('type',1)->where('status',0)->sum('amount');
			
			//total fee
			$feeTotal = $totalFee + $Request->get('amount');				
			$feeCourse = $course->course_fee - $course->discount - $course->old_course_fee;
			
			if($feeTotal > $feeCourse)
			{
				return Redirect::to('center/fee/edit/'.$id.'/'.$sid)->with('message', 'Sorry ! Your Fee Amount is greater then total course fee');
			}
			
			//add new fee
			$fee = Fee::find($id);				
			$fee->amount    	= $Request->get('amount');
			$fee->naration     	= $Request->get('naration');
			$fee->date_added    = $Request->get('date_added');
			$fee->due_date    	= $Request->get('due_date');
			$fee->updated_by   	= Auth::user()->id;
			$fee->save();
			
			//capture user activity
			$this->activity("Update Student Fee - ".$chk->first_name." ".$chk->last_name);
			
			return Redirect::to('center/fee/view/'.$sid)->with('message','Updated Successfully');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

}
