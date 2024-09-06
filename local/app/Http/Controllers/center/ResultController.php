<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Student;
use App\StudentCourse;
use App\Course;
use App\Batch;
use App\StudentDocs;
use App\Fee;
use DB;
use Validator;
use Redirect;
use Image;
class ResultController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("Add Results"))
		{
			$res = Course::where('status',0)->orderBy("id",'DESC')->get();
			
			return View('center.result.index')->with('res',$res);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
		
		
	/*
	@Get All student
	*/	
	public function get(Request $Request)
	{
		if (Auth()->check())
		{						
			$course_id = $Request->get('course_id');
			$batch     = $Request->get('batch');
			$centerId  = Auth::user()->id;
			
			//get student
			$get = StudentCourse::where('course_id',$course_id)->where('batch',$batch)->where('status',0)->lists('student_id');
			
			$res = Student::where('status',0)->whereIn('id',$get)->get();
			
			//get Course & batch			
			$course 	= Course::find($course_id);			
			
			return View('center.result.getStudent')->with(compact('get','res','course','batch'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@update result in db
	*/	
	public function update(Request $Request)
	{
		if (Auth()->check() && Perm::check("Add Results"))
		{
			$sid = $Request->get('student_id');
			
			//count for student
			for($i=0;$i<count($sid);$i++)
			{
				$result = $Request->get('result_'.$sid[$i]);
				$grade  = $Request->get('grade_'.$sid[$i]);
				
				$chk = Student::find($sid[$i]);
				
				//update data in DB
				$data = Student::find($sid[$i]);
				$data->status 	= 1;
				$data->result 	= $result;
				
				if($grade)
				{
					$data->grade 	= $grade;
				}
				
				$data->result_date 		= date('Y-m-d');
				$data->result_added_by 	= Auth::user()->id;
				$data->save();
			}
			
			//capture user activity
			$res = Student::find($sid);
			$this->activity("Add Student Result - ".$data->first_name." ".$data->last_name);
			
			return Redirect::to('center/result/passOut')->with('message', 'Saved Successfully');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@For passout student
	*/	
	public function passOut()
	{
		if (Auth()->check() && Perm::check("View Passout Students"))
		{						
			$res = Student::where('status',1)->orderBy('id','DESC')->paginate(100);	
			
			return View('center.result.pass')->with('res',$res);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	
	/*
	@This is for edit result & student document
	@ $id = student_id
	*/	
	public function edit($id)
	{
		if (Auth()->check() && Perm::check("Add Documents"))
		{						
			$res = Student::find($id);
			
			//get course & document
			$courseRes = StudentCourse::where('student_id',$id)->where('status',0)->first();
			
			$docs      = StudentDocs::where('student_id',$id)->get();
			
			return View('center.result.edit')->with(compact('courseRes','docs','res'));
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@This is for edit result & student document
	@ $id = student_id
	*/	
	public function _edit(Request $Request,$id)
	{
		if (Auth()->check() && Perm::check("Add Documents"))
		{						
			$res = Student::find($id);
			
			//save data in DB if NO Result is selected
			if($Request->get('result') == 2)
			{
				$data = Student::find($id);
				$data->result 	= null;
				$data->grade 	= null;
				$data->status 	= 0;
				$data->save();
			}
			else
			{	//save data in DB & upload document, if NO Result is not selected
				$data = Student::find($id);
				$data->result 				= $Request->get('result');
				$data->grade 				= $Request->get('grade');
				$data->result_updated_by 	= Auth::user()->id;
				$data->save();
				
								
				if($Request->hasFile('file'))
				{	
					//Validation
					 $validator = Validator::make($Request->all(), [
						'file' 		 => 'mimes:jpeg,jpg,png,gif,txt,docs,pdf,xls,xlsx | max:1000',
					]);
			
					if($validator->fails())
					{
						return redirect('center/result/edit/'.$id)->withErrors($validator)->withInput();
						exit;
					}
					
					$fl = $Request->file('file');
					
					for($i=0;$i<count($fl);$i++)
					{
						$filename  = $id. time() . rand(777,999). rand(444,699).'.' .$Request->file('file')[$i]->getClientOriginalExtension();	
						$path = "upload/student/";
						$Request->file('file')[$i]->move($path, $filename);	
						
						$docs = new StudentDocs;
						$docs->student_id = $id;
						$docs->file = $filename;
						$docs->save();
					}	
								
				}
				
			}			
			
			//capture user activity
			$this->activity("Edit Student Result - ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/result/passOut')->with('message', 'Saved Successfully');
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@remove document
	@$id = document id
	@$sid = student id
	*/	
	public function remove($id,$sid)
	{
		if (Auth()->check() && Perm::check("Delete Documents"))
		{						
			$res = Student::find($id);
			
			$get = StudentDocs::find($id);
			
			unlink("upload/student/".$get->file);
				
			StudentDocs::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Remove Student Docs - ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/result/edit/'.$sid.'#docs');			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

}
