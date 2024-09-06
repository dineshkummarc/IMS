<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Course;
use App\Student;
use App\StudentCourse;
use App\AppSetting;
use App\Assignment;
use DB;
use Validator;
use Redirect;
class AppSettingController extends Controller {
	
	/*
	@imgRemove
	*/	
	public function imgRemove()
	{
		if (Auth()->check() && Perm::check("Manage Mobile App"))
		{
			$res = AppSetting::first();
			
			unlink("upload/AppData/".$res->img);
			
			AppSetting::where('center_id',Auth::user()->id)->update(['img' => null]);
			
			//capture user activity
			$this->activity("Remove Image From App Setting");
			
			return Redirect::to('center/Appsetting')->with('message', 'Saved Successfully');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("Manage Mobile App"))
		{
			$res = AppSetting::first();
			
			return View('center.appSetting.index')->with('res',$res);			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Save Data
	*/	
	public function add(Request $Request)
	{
		if (Auth()->check() && Perm::check("Manage Mobile App"))
		{
			$chk = AppSetting::first();
			$src = "src='".Asset("kcfinder/upload/images/")."'";
			
			$desc = str_replace('src="/kcfinder/upload/images/',$src,$Request->get('description'));
			
			if(count($chk) > 0)
			{
				$data =  AppSetting::first();
				$data->updated_by = Auth::user()->id;
				
				//update img
				if($Request->hasFile('img'))
				{	
					//Validation
					 $validator = Validator::make($Request->all(), [
						'img' 	=> 'mimes:jpeg,jpg,png,gif| max:1000',
					]);
			
					if($validator->fails())
					{
						return redirect('center/Appsetting')->withErrors($validator)->withInput();
						exit;
					}
					
					$id = Auth::user()->id;
					$filename  = $id. time() . rand(777,999). rand(444,699).'.' .$Request->file('img')->getClientOriginalExtension();	
					$path = "upload/AppData/";
					$Request->file('img')->move($path, $filename);		
					
					$data->img = $filename;			
				}
				
				$data->description = $desc;
				$data->save();
				
				//capture user activity
				$this->activity("Update App Setting");
				
				return Redirect::to('center/Appsetting')->with('message', 'Saved Successfully');
			}
			else
			{
				$data =  new AppSetting;
				$data->updated_by = Auth::user()->id;
				
				//update img
				if($Request->hasFile('img'))
				{					
					//Validation
					 $validator = Validator::make($Request->all(), [
						'img' 	=> 'mimes:jpeg,jpg,png,gif| max:1000',
					]);
			
					if($validator->fails())
					{
						return redirect('center/Appsetting')->withErrors($validator)->withInput();
						exit;
					}
					
					$id = Auth::user()->id;
					$filename  = $id. time() . rand(777,999). rand(444,699).'.' .$Request->file('img')->getClientOriginalExtension();	
					$path = "upload/AppData/";
					$Request->file('img')->move($path, $filename);		
					
					$data->img = $filename;			
				}
				
				$data->description = $Request->get('description');
				$data->save();
				
				//capture user activity
				$this->activity("Update App Setting");
				
				return Redirect::to('center/Appsetting')->with('message', 'Saved Successfully');
			}
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Upload assignment for app
	*/	
	public function assignment()
	{
		if (Auth()->check() && Perm::check("Upload Assignment"))
		{
			$res = Assignment::orderBy('id','DESC')->paginate(100);
			
			return View('center.assignment.index')->with('res',$res);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Upload assignment for app,Add New
	*/	
	public function assignmentAdd()
	{
		if (Auth()->check() && Perm::check("Upload Assignment"))
		{
			$courses = Course::get();
			
			return View('center.assignment.add')->with('courses',$courses);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Upload assignment for app,Add New
	*/	
	public function _assignmentAdd(Request $Request)
	{
		if (Auth()->check() && Perm::check("Upload Assignment"))
		{
				//Validation
				 $validator = Validator::make($Request->all(), [
					
					'title' 	=> 'required| max:300',					
					'date_added' => 'required| max:100',
					'file' 		 => 'mimes:jpeg,jpg,png,gif,txt,docs,pdf,xls,xlsx | max:1000',
				]);
			
				if($validator->fails())
				{
					return redirect('center/app/assignment/add')->withErrors($validator)->withInput();
				}
				else
				{
					$data = new Assignment;
					$data->added_by 		= Auth::user()->id;
					$data->course_id 		= $Request->get('course_id');
					$data->batch 			= $Request->get('batch');
					$data->title 			= $Request->get('title');
					$data->date_added 		= $Request->get('date_added');
					
					if($Request->hasFile('file'))
					{					
						$id = Auth::user()->id;
						$filename  = $id. time() . rand(777,999). rand(444,699).'.' .$Request->file('file')->getClientOriginalExtension();	
						$path = "upload/assignment/";
						$Request->file('file')->move($path, $filename);
						$data->file = $filename;
					}
					
					$data->save();
					
					//capture user activity
					$this->activity("Add New Assignment");
					
					return Redirect::to('center/app/assignment')->with('message', 'Saved Successfully');
				}
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Delete
	*/	
	public function delete($id)
	{
		if (Auth()->check() && Perm::check("Delete Assignment"))
		{
			$res = Assignment::find($id);
			
			unlink("upload/assignment/".$res->file);
				
			Assignment::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete Assignment");
			
			return Redirect::to('center/app/assignment')->with('message', 'Deleted Successfully');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@send push notification
	*/
	public function notification()
	{
		if (Auth()->check() && Perm::check("Send Notification in App"))
		{
			$courses = Course::get();
			
			return View('center.appSetting.noti')->with('courses',$courses);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@API function
	*/	function sendMessage($title,$description,$course,$batch)
		{
			$content = array(
		  "en" => $description
		  );
		  
		  $head = array(
		  "en" => $title
		  );
		
		 //For all center
		 if($course == "")
		 {
			 $daTags = array([ 'key' => 'centerID', 'relation' => '=', 'value' => Auth::user()->id ]);
		 }
		 
		 //For single course
		 if($course != "" && $batch == "" )
		 {
			 $daTags = array([ 'key' => 'centerID', 'relation' => '=', 'value' => Auth::user()->id ],[ 'key' => 'course_id', 'relation' => '=', 'value' => $course ]);
		 }
		 
		 //For single course & Batch
		 if($course != "" && $batch != "" )
		 {
			 $daTags = array([ 'key' => 'centerID', 'relation' => '=', 'value' => Auth::user()->id ],[ 'key' => 'course_id', 'relation' => '=', 'value' => $course ],[ 'key' => 'batch', 'relation' => '=', 'value' => $batch ]);
		 }
		 
				
		$fields = array(
		  'app_id' => "aa9e20c6-cd99-40a2-bfff-d75e97a2dda5",
		  //'included_segments' => array('All'),	
		  'tags' => $daTags,	  
		  'data' => array("foo" => "bar"),
		  'contents' => $content,
		  'headings' => $head      
		);
		
		$fields = json_encode($fields);
		print("\nJSON sent:\n");
		print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
							   'Authorization: Basic Y2EwNzgwNGMtNWMxYi00ZTJkLTk2YWYtODNlNzdlMTk3YWQw'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	  }
	
	/*
	@send push notification
	*/
	public function _notification(Request $Request)
	{
		if (Auth()->check() && Perm::check("Send Notification in App"))
		{
			if($Request->get('batch'))
			{
				$batch = $Request->get('batch');
			}
			else
			{
				$batch = "";
			}
			
			$course 		= $Request->get('course_id');			
			$title  		= $Request->get('title');
			$description  	= $Request->get('message');
			
			//for all course
			if($course == "")
			{
				$response = $this->sendMessage($title,$description,$course,$batch);
			}
			
			//for selected course
			if($course != "" && $batch == "")
			{
				$response = $this->sendMessage($title,$description,$course,$batch);
			}
			
			//for selected course && batch
			if($course != "" && $batch != "")
			{
				$response = $this->sendMessage($title,$description,$course,$batch);
			}
			
			//capture user activity
			$this->activity("Send Notification in App");
			
			return Redirect::to('center/app/notification')->with('message', 'Send Successfully');
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
}
