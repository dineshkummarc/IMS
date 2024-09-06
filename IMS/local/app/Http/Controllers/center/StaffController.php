<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Staff;
use App\User;
use App\Attendance;
use DB;
use Validator;
use Redirect;
class StaffController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Staff") || Perm::check("Add Staff") || Perm::check("Edit Staff") || Perm::check("Delete Staff"))
		{
			$res = Staff::orderBy("id",'DESC')->get();
			
			return View('center.staff.index')->with('res',$res);
			
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
		if (Auth()->check() && Perm::check("Add Staff"))
		{						
			return View('center.staff.add');			
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
		if (Auth()->check() && Perm::check("Add Staff"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' 		=> 'required|max:50',				
				'phone' 	=> 'required|max:50',
				'user_name'	=> 'unique:users'
			]);
			
			if($validator->fails())
			{
				return redirect('center/staff/add')->withErrors($validator)->withInput();
			}
			else
			{
				$data = new Staff;
				$data->added_by 	= Auth::user()->id;
				$data->name 		= $Request->get('name');
				$data->phone 		= $Request->get('phone');
				$data->email 		= $Request->get('email');
				$data->joining_date	= $Request->get('joining_date');
				$data->job			= $Request->get('job');
				$data->status 		= $Request->get('status');
				$data->save();				
				
				/*
				@if user login detail is not empty, create a new user
				*/
				if($Request->get('user_name') && $Request->get('password'))
				{
					$perm 		= implode(",",$Request->get('perm'));
					$updateUser = new User;
					$updateUser->person_name 	= $Request->get("name");
					$updateUser->mobile 		= $Request->get("phone");
					$updateUser->email 			= $Request->get("email");
					$updateUser->user_name		= $Request->get("user_name");
					$updateUser->password		= bcrypt($Request->get("password"));
					$updateUser->shw_password	= $Request->get("password");
					$updateUser->perm			= $perm;
					$updateUser->staff_ref_id	= $data->id;
					$updateUser->status			= $Request->get('status');
					$updateUser->save();
				}
				
				//capture user activity
				$this->activity("Add New Staff Member - ".$Request->get('name'));
				
				return Redirect::to('center/staff')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Staff"))
		{						
			$res 	= Staff::find($id);
			$user	= User::where("staff_ref_id",$id)->first();	
			
			return View('center.staff.edit')->with(compact('res','user'));								
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
		if (Auth()->check() && Perm::check("Edit Staff"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' => 'required|max:50',				
				'phone' => 'required|max:50'				
			]);
			
			if($validator->fails())
			{
				return redirect('center/staff/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				//check for username
				$chkCount = User::where("staff_ref_id","!=",$id)->where("user_name",$Request->get("user_name"))->count();
				
				if($chkCount > 0)
				{
					return redirect('center/staff/edit/'.$id)->with("error","This username is already exists");
					exit;
				}
				
				$data = Staff::find($id);
				$data->name 		= $Request->get('name');
				$data->phone 		= $Request->get('phone');
				$data->email 		= $Request->get('email');
				$data->joining_date	= $Request->get('joining_date');
				$data->job			= $Request->get('job');
				$data->status 		= $Request->get('status');
				$data->updated_by	= Auth::user()->id;
				$data->save();	
				
				/*
				@Update user details
				*/
				$chkUser = User::where("staff_ref_id",$id)->count();
				
				if($Request->get('user_name'))
				{
					if($Request->get('perm'))
					{
						$perm = implode(",",$Request->get('perm'));
					}
					else
					{
						$perm = null;
					}					
					
					if($chkUser > 0)
					{
						$updateUser = User::where("staff_ref_id",$id)->first();
					}
					else
					{
						$updateUser = new User;
					}	
					
					$updateUser->person_name 	= $Request->get("name");
					$updateUser->mobile 		= $Request->get("phone");
					$updateUser->email 			= $Request->get("email");
					$updateUser->user_name		= $Request->get("user_name");
					
					if($Request->get('password'))
					{
						$updateUser->password		= bcrypt($Request->get("password"));
						$updateUser->shw_password	= $Request->get("password");
					}					
					
					$updateUser->perm			= $perm;
					$updateUser->staff_ref_id	= $data->id;
					$updateUser->status			= $Request->get('status');
					$updateUser->save();				
				}
				
				//capture user activity
				$this->activity("Update Staff Member - ".$Request->get('name'));
				
				return Redirect::to('center/staff')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Staff"))
		{						
			$res = Staff::find($id);
			
			Staff::where('id',$id)->delete();			
			User::where("staff_ref_id",$id)->delete();
			Attendance::where("staff_id",$id)->delete();
			
			//capture user activity
			$this->activity("Delete Staff Member - ".$res->name);
			
			return Redirect::to('center/staff')->with('message', 'Deleted Successfully');
							
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
