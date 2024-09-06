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
class ExtraChargeController extends Controller {
	
	/*
	@Index page
	*/	
	public function index($id)
	{
		if (Auth()->check())
		{
			$res = Student::find($id);
			
			$fees = Fee::where('student_id',$id)->where('type',0)->orderBy('id','DESC')->get();
				
			return View('center.student.extra')->with('res',$res)->with('fees',$fees);		
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new
	*/	
	public function add(Request $Request,$id)
	{
		if (Auth()->check())
		{
			$res    = Student::find($id);
			$course = StudentCourse::where('student_id',$id)->where('status',0)->first();
			
			$data = new Fee;
			$data->student_id = $id;
			$data->course_id  = $course->course_id;
			$data->naration   = $Request->get('narration');
			$data->amount     = $Request->get('amount');
			$data->date_added = $Request->get('date_added');
			$data->added_by   = Auth::user()->id;
			$data->type       = 0;
			$data->save();
			
			//capture user activity
			$this->activity("Add Extra Charges ".$Request->get('amount')." For ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/student/extraCharge/'.$id)->with('message', 'Added Successfully !');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	
	/*
	@edit
	*/	
	public function edit($fid,$id)
	{
		if (Auth()->check())
		{
			$res    = Student::find($id);
			
			$fee 	= Fee::find($fid);
				
			return View('center.student.editextra')->with('fee',$fee)->with('id',$id);			
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@edit
	*/	
	public function _edit(Request $Request,$fid,$id)
	{
		if (Auth()->check())
		{
			$res    = Student::find($id);			
			
			$data = Fee::find($fid);				
			$data->naration   = $Request->get('narration');
			$data->amount     = $Request->get('amount');
			$data->date_added = $Request->get('date_added');				
			$data->updated_by = Auth::user()->id;				
			$data->save();
			
			//capture user activity
			$this->activity("Update Extra Charges ".$Request->get('amount')." For ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/student/extraCharge/'.$id)->with('message', 'Added Successfully !');			
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@delete
	*/	
	public function delete($fid,$id)
	{
		if (Auth()->check())
		{
			$res    = Student::find($id);			
			
			Fee::where('student_id',$id)->where('id',$fid)->delete();
			
			//capture user activity
			$this->activity("Delete Extra Charges For ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/student/extraCharge/'.$id)->with('message', 'Deleted Successfully !');
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

}
