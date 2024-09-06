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
class StudentController extends Controller {
	
	/*
	@this is index page
	@show all the student
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Students") || Perm::check("Add Students") || Perm::check("Edit Students") || Perm::check("Delete Students"))
		{
			if(Perm::check("View Students"))
			{
				$res = Student::where('status',0)->orderBy("id",'DESC')->paginate(100);
			}
			else
			{
				$res = [];
			}
						
			return View('center.student.index')->with('res',$res);			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@View student Detail with id
	*/	
	public function view($id)
	{
		if (Auth()->check() && Perm::check("View Students"))
		{
			$res = Student::find($id);
			
				//userImage
				if($res->img)
				{
					$imgSrc = Asset('upload/user/'.$res->img);
				}
				else
				{
					if($res->gender == "Male")
					{
						$imgSrc = Asset('assets/images/male.jpg');
					}
					else
					{
						$imgSrc = Asset('assets/images/female.png');
					}
				}
				
				//get student all course && current course
				$get = StudentCourse::where('student_id',$id)->orderBy('status','ASC')->get();				
				$crs = StudentCourse::where('student_id',$id)->where('status',0)->first();				
				
				//get student fees
				$fees   = Fee::where('student_id',$id)
						->where('course_id',$crs->course_id)
						->where('type',0)
						->where('status',0)->get();
						
				//get depoist fees		
				$Payfees = Fee::where('student_id',$id)
				->where('course_id',$crs->course_id)
				->where('type',1)
				->where('status',0)->get();
				
				//capture user activity
				$this->activity("View Student Detail - ".$res->first_name." ".$res->last_name);
				
				return View('center.student.view')->with(compact('res','imgSrc','fees','Payfees','get','crs'));
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Sorry ! You dont have permission to access this page.');
		}
	}
	
	/*
	@upload or change user image
	*/	
	public function changeImage(Request $Request,$id)
	{
		if (Auth()->check() && Perm::check("Edit Students"))
		{
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'img' => 'required | mimes:jpeg,jpg,png,gif,jpg | max:1000',								
			]);
		
			if($validator->fails())
			{
				return redirect('center/student/view/'.$id)->with('message','User imgae must be a Image.Try again.');
			}
			else
			{
				//update image
				if($Request->hasFile('img'))
				{					
					$filename  = $id. time() . rand(777,999). rand(444,699).'.' .$Request->file('img')->getClientOriginalExtension();	
					$path = "upload/user/";
					$Request->file('img')->move($path, $filename);		
					
					Student::where('id',$id)->update(['img' => $filename]);				
				}
			}
			
				//capture user activity
				$res = Student::find($id);
				$this->activity("Change Student Profile Image - ".$res->first_name." ".$res->last_name);
			
			   return Redirect::to('center/student/view/'.$id)->with('message', 'Updated Successfully');		
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@search student with name,mobile,city
	*/	
	public function search(Request $Request)
	{
		if (Auth()->check())
		{
			$query = $Request->get('query');
			
			$res = Student::where('status',0)
			->where('first_name','LIKE','%'.$query.'%')
			->orWhere('last_name','LIKE','%'.$query.'%')
			->orWhere('mobile','LIKE','%'.$query.'%')			
			->orWhere('city','LIKE','%'.$query.'%')			
			->orderBy("id",'DESC')->paginate(100);
			
			//capture user activity
			$this->activity("Search Student - ".$query);
			
			return View('center.student.index')->with('res',$res);			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new student
	*/	
	public function add()
	{
		if (Auth()->check() && Perm::check("Add Students"))
		{						
			return View('center.student.add');			
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
		if (Auth()->check() && Perm::check("Add Students"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'first_name' 	=> 'required|min:3|max:50',				
				'last_name' 	=> 'required|max:50',				
				'mobile' 		=> 'required|max:12|min:10',								
				'state' 		=> 'required|min:3|max:50',								
				'city' 			=> 'required|max:50',								
				'address' 		=> 'required|min:5|max:250',								
				'course_id' 	=> 'required|max:50',								
				'batch' 		=> 'required|max:50',								
				'course_fee' 	=> 'required|max:50',								
				'joining_date' 	=> 'required|max:50'								
			]);
			
			if($validator->fails())
			{
				return redirect('center/student/add')->withErrors($validator)->withInput();
			}
			else
			{				
				if($Request->get('deposit') > $Request->get('course_fee') - $Request->get('discount'))
				{
					return Redirect::to('center/student/add')->with('message', 'Sorry ! Your deposit fee amount is greater then course fee')->withInput();
					exit;
				}
				
				//add new student
				$data = new Student;
				$data->center_id 		= Auth::user()->id;
				$data->first_name   	= $Request->get('first_name');
				$data->last_name    	= $Request->get('last_name');				
				$data->mobile   		= $Request->get('mobile');
				$data->contact_no   	= $Request->get('contact_no');
				$data->email   			= $Request->get('email');
				$data->dob   			= $Request->get('dob');
				$data->state   			= $Request->get('state');
				$data->city   			= $Request->get('city');
				$data->address   		= $Request->get('address');									
				$data->gender   		= $Request->get('gender');								
				$data->enquiry_id  		= $Request->get('enquiry_id');								
				$data->added_by  		= Auth::user()->id;								
				$data->save();
				
				//update Login id;
				$login_id = $data->id .substr($data->first_name,0,1).rand(222,999);
				Student::where('id',$data->id)->update(['login_id' => $login_id]);
				
				//add student course				
				$course = new StudentCourse;
				$course->added_by 		= Auth::user()->id;				
				$course->student_id 	= $data->id;
				$course->course_id   	= $Request->get('course_id');
				$course->batch   		= $Request->get('batch');				
				$course->course_fee   	= $Request->get('course_fee');				
				$course->discount   	= $Request->get('discount');				
				$course->remark   		= $Request->get('remark');
				$course->joining_date   = $Request->get('joining_date');
				$course->roll_no   		= $Request->get('roll_no');				
				$course->save();
				
				//update Fee				
				$fees = new Fee;
				$fees->added_by 	= Auth::user()->id;
				$fees->student_id  	= $data->id;
				$fees->course_id   	= $Request->get('course_id');
				$fees->amount 	   	= $Request->get('course_fee');
				$fees->naration    	= "Course Fee";
				$fees->type       	= 0;
				$fees->date_added  	= $Request->get('joining_date');
				$fees->save();
				
				
				if($Request->get('deposit'))
				{
					//if Fee Deposit							
					$Dfees = new Fee;
					$Dfees->added_by 	= Auth::user()->id;
					$Dfees->student_id  = $data->id;
					$Dfees->course_id   = $Request->get('course_id');
					$Dfees->amount 	   	= $Request->get('deposit');
					$Dfees->naration    = "Fee Deposit On Joining";
					$Dfees->type     	= 1;
					$Dfees->date_added  = $Request->get('joining_date');
					$Dfees->save();
				}
				
				//capture user activity
				$this->activity("Add New Student - ".$Request->get('first_name')." ".$Request->get('last_name'));
				
				return Redirect::to('center/student')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Students"))
		{						
			$res = Student::find($id);
			
			$courseRes = StudentCourse::where('student_id',$id)->where('status',0)->first();
				
			return View('center.student.edit')->with(compact('res','courseRes'));						
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Add new page.Save data in DB
	*/	
	public function _edit(Request $Request,$id)
	{
		if (Auth()->check() && Perm::check("Edit Students"))
		{						
			
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'first_name' 	=> 'required|max:50',				
				'last_name' 	=> 'required|max:50',				
				'mobile' 		=> 'required|max:50',								
				'state' 		=> 'required|max:50',								
				'city' 			=> 'required|max:50',								
				'address' 		=> 'required|max:50',																		
				'batch' 		=> 'required|max:50'	
											
			]);
			
			if($validator->fails())
			{
				return redirect('center/student/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{				
				$data = Student::find($id);
				$data->updated_by 		= Auth::user()->id;
				$data->first_name   	= $Request->get('first_name');
				$data->last_name    	= $Request->get('last_name');				
				$data->mobile   		= $Request->get('mobile');
				$data->contact_no   	= $Request->get('contact_no');
				$data->email   			= $Request->get('email');
				$data->dob   			= $Request->get('dob');
				$data->state   			= $Request->get('state');
				$data->city   			= $Request->get('city');
				$data->address   		= $Request->get('address');									
				$data->gender   		= $Request->get('gender');								
				$data->save();
				
				//update course
				StudentCourse::where('student_id',$id)->update(['batch' => $Request->get('batch')]);
				
				//capture user activity
				$this->activity("Edit Student Detail - ".$data->first_name." ".$data->last_name);
				
				return Redirect::to('center/student')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Students"))
		{						
			//checking for student data
			$chk = Fee::where("student_id",$id)->where('type',1)->count();
			$res = Student::find($id);
			if($chk > 0)
			{
				return Redirect::to('center/student')->with('message', 'You can delete only that student that have no fee record.Sorry ! this student can not be deleted.');
				exit;
			}
			
			//delete student if no data found
			Student::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete Student - ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/student')->with('message', 'Deleted Successfully');					
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
