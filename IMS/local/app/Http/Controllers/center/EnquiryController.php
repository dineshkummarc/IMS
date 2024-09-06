<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Student;
use App\StudentCourse;
use App\Course;
use App\Enquiry;
use App\Fee;
use DB;
use Validator;
use Redirect;
use Image;
class EnquiryController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Enquiry") || Perm::check("Add Enquiry") || Perm::check("Edit Enquiry") || Perm::check("Delete Enquiry"))
		{
			if(Perm::check("View Enquiry"))
			{
				$res = Enquiry::orderBy("id",'DESC')->paginate(100);
			}
			else
			{
				$res = [];
			}		
			
			return View('center.enquiry.index')->with('res',$res);			
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
		if (Auth()->check() && Perm::check("Add Enquiry"))
		{						
			return View('center.enquiry.add');			
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
		if (Auth()->check() && Perm::check("Add Enquiry"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'first_name' 	=> 'required|min:3|max:50',				
				'last_name' 	=> 'required|max:50',				
				'mobile' 		=> 'required|max:12|min:10',								
				'state' 		=> 'required|min:3|max:50',								
				'city' 			=> 'required|max:50',								
				'address' 		=> 'required|min:5|max:50',								
				'course_id' 	=> 'required|max:50',								
				'batch' 		=> 'required|max:50',			
				'enquiry_date' 	=> 'required|max:50'								
			]);
			
			if($validator->fails())
			{
				return redirect('center/enquiry/add')->withErrors($validator)->withInput();
			}
			else
			{				
				$data = new Enquiry;
				$data->added_by 		= Auth::user()->id;
				$data->first_name   	= $Request->get('first_name');
				$data->last_name    	= $Request->get('last_name');				
				$data->mobile   		= $Request->get('mobile');
				$data->contact_no   	= $Request->get('contact_no');
				$data->email   			= $Request->get('email');				
				$data->state   			= $Request->get('state');
				$data->city   			= $Request->get('city');
				$data->address   		= $Request->get('address');									
				$data->gender   		= $Request->get('gender');								
				$data->course_id   		= $Request->get('course_id');								
				$data->course_fee       = $Request->get('course_fee');								
				$data->batch       		= $Request->get('batch');								
				$data->enquiry_date     = $Request->get('enquiry_date');								
				$data->save();	
				
				//capture user activity
				$this->activity("Add New Enquiry - ".$Request->get('first_name')." ".$Request->get('last_name'));
				
				return Redirect::to('center/enquiry')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Enquiry"))
		{						
			$res = Enquiry::find($id);		
			
			return View('center.enquiry.edit')->with(compact('res'));					
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
		if (Auth()->check() && Perm::check("Edit Enquiry"))
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
				return redirect('center/enquiry/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{				
				$data = Enquiry::find($id);
				$data->center_id 		= Auth::user()->id;
				$data->first_name   	= $Request->get('first_name');
				$data->last_name    	= $Request->get('last_name');				
				$data->mobile   		= $Request->get('mobile');
				$data->contact_no   	= $Request->get('contact_no');
				$data->email   			= $Request->get('email');				
				$data->state   			= $Request->get('state');
				$data->city   			= $Request->get('city');
				$data->address   		= $Request->get('address');									
				$data->gender   		= $Request->get('gender');								
				$data->course_id   		= $Request->get('course_id');								
				$data->batch   			= $Request->get('batch');								
				$data->course_fee   	= $Request->get('course_fee');								
				$data->updated_by   	= Auth::user()->id;								
				$data->save();			
				
				//capture user activity
				$this->activity("Edit Enquiry - ".$Request->get('first_name')." ".$Request->get('last_name'));
				
				return Redirect::to('center/enquiry')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Enquiry"))
		{						
			$res = Enquiry::find($id);			
			Enquiry::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete Enquiry - ".$res->first_name." ".$res->last_name);
			
			return Redirect::to('center/enquiry')->with('message', 'Deleted Successfully');			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	
	/*
	@Make Admission
	*/	
	public function makeAdmission($id)
	{
		if (Auth()->check() && Perm::check("Add Students"))
		{						
			$res = Enquiry::find($id);
			
			return View('center.student.add')->with('res',$res);					
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'You dont have permission to access this page.');
		}
	}
	

}
