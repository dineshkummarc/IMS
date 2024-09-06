<?php namespace App\Http\Controllers\center;

use App\Http\Requests;
use App\Helpers\Perm;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Batch;
use App\Course;
use App\CourseBatch;
use DB;
use Validator;
use Redirect;
class CourseController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Courses") || Perm::check("Add Courses") || Perm::check("Edit Courses") || Perm::check("Delete Courses"))
		{
			if(Perm::check("View Courses"))
			{
				$res = Course::orderBy("id",'DESC')->get();
			}
			else
			{
				$res = [];
			}
			
			return View('center.course.index')->with('res',$res);
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new page
	*/	
	public function add()
	{
		if (Auth()->check() && Perm::check("Add Courses"))
		{						
			$batchs = Batch::where('status',0)->orderBy("id",'DESC')->get();
			return View('center.course.add')->with('batchs',$batchs);			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new page.Save data in DB
	*/	
	public function _add(Request $Request)
	{
		if (Auth()->check() && Perm::check("Add Courses"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' 		   => 'required|max:50',				
				'course_time'  => 'required|max:50',				
			]);
			
			if($validator->fails())
			{
				return redirect('center/course/add')->withErrors($validator)->withInput();
			}
			else
			{
				$data = new Course;
				$data->added_by 	= Auth::user()->id;
				$data->name 		= $Request->get('name');
				$data->course_time	= $Request->get('course_time');
				$data->fee 			= $Request->get('fee');
				$data->description	= $Request->get('description');
				$data->status 		= $Request->get('status');
				$data->save();

				$batch = $Request->get('batch');
				
				for($i=0;$i<count($batch);$i++)
				{
					$btch = new CourseBatch;
					$btch->course_id  	= $data->id;
					$btch->batch_name 	= $batch[$i];
					$btch->save();
				}
				
				//capture user activity
				$this->activity("Add New Course - ".$Request->get('name'));
				
				return Redirect::to('center/course')->with('message', 'Saved Successfully');
			}				
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Edit Page
	*/	
	public function edit($id)
	{
		if (Auth()->check() && Perm::check("Edit Courses"))
		{						
			$res = Course::find($id);
			
			$batchs = Batch::where('status',0)->orderBy("id",'DESC')->get();
							
			return View('center.course.edit')->with(compact('res','batchs'));						
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@edit page, Save data in DB
	*/	
	public function _edit(Request $Request,$id)
	{
		if (Auth()->check() && Perm::check("Edit Courses") )
		{						
								
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' 		   => 'required|max:50',				
				'course_time'  => 'required|max:50',				
			]);
			
			if($validator->fails())
			{
				return redirect('center/course/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				$data = Course::find($id);
				$data->updated_by 	= Auth::user()->id;
				$data->name 		= $Request->get('name');
				$data->course_time	= $Request->get('course_time');
				$data->fee 			= $Request->get('fee');
				$data->description	= $Request->get('description');
				$data->status 		= $Request->get('status');
				$data->save();

				$batch = $Request->get('batch');
				CourseBatch::where("course_id",$id)->delete();
				
				for($i=0;$i<count($batch);$i++)
				{
					$btch = new CourseBatch;
					$btch->course_id  	= $data->id;
					$btch->batch_name 	= $batch[$i];
					$btch->save();
				}
				
				//capture user activity
				$this->activity("Update Course - ".$Request->get('name'));
				
				return Redirect::to('center/course')->with('message', 'Saved Successfully');
			}				
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

	/*
	@Delete Page
	*/	
	public function delete($id)
	{
		if (Auth()->check() && Perm::check("Delete Courses"))
		{						
			$res = Course::find($id);
			$chk = DB::table("student_course")->where('status',0)->where('course_id',$id)->count();
			
			if($chk > 0)
			{
				return Redirect::to('center/course')->with('message', 'Sorry ! '.$chk.' students registered with this course.You can not delete this');
				exit;
			}
			
			Course::where('id',$id)->delete();
			CourseBatch::where('course_id',$id)->delete();

			//capture user activity
			$this->activity("Delete Course - ".$res->name);
			
			return Redirect::to('center/course')->with('message', 'Deleted Successfully');
							
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
