<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Staff;
use App\Attendance;
use DB;
use Validator;
use Redirect;
class StaffAttendanceController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Staff Attendance") || Perm::check("Add Staff Attendance") || Perm::check("Edit Staff Attendance"))
		{
			$res 	= Attendance::orderBy("id",'DESC')->paginate(100);
			$staffs = Staff::where('status',0)->get();
			$from   = null;
			$to     = null;
			$sid    = null;
			return View('center.attendance.index')->with(compact('res','staffs','from','to','sid'));
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Search attendance with date range
	*/	
	public function search(Request $Request)
	{
		if (Auth()->check())
		{
			//get detail
			$res = Attendance::where(function($query) use ($Request) {
				
			$from 				= $Request->has('from') ? $Request->get('from') : null;	
			$to   				= $Request->has('to') ? $Request->get('to') : null;	
			$staff   			= $Request->has('staff_id') ? $Request->get('staff_id') : null;	
						
			if(isset($from))
			{
				$query->where('date_added','>=',$from);
			}
			
			if(isset($to))
			{
				$query->where('date_added','<=',$to);
			}
			
			if(isset($staff))
			{
				$query->where('staff_id',$staff);
			}			
				
			})->orderBy('date_added','ASC')->paginate(100);
			
			$staffs = Staff::where('status',0)->get();
			
			$from 	= $Request->get('from');
			$to 	= $Request->get('to');
			$sid 	= $Request->get('staff_id');
			
			//capture user activity
			$this->activity("Search Staff Attendance");
			
			return View('center.attendance.index')->with(compact('res','staffs','from','to','sid'));
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
		if (Auth()->check() && Perm::check("Add Staff Attendance"))
		{						
			$staffs	= Staff::where('status',0)->orderBy("id",'DESC')->get();
			
			return View('center.attendance.add')->with('staffs',$staffs);			
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
		if (Auth()->check() && Perm::check("Add Staff Attendance"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'date_added' => 'required|max:50',			
								
			]);
			
			if($validator->fails())
			{
				return redirect('center/staff/attendance/add')->withErrors($validator)->withInput();
			}
			else
			{				
				$staff  = $Request->get('staff_id');
				
				for($i=0;$i<count($staff);$i++)
				{
					$chk = $Request->get('chk_'.$staff[$i]);
					
					if($chk)
					{
						$type = 0;
					}
					else
					{
						$type = 1;
					}
					
					//Checking for already exists
					$chk = Attendance::where('staff_id',$staff[$i])->where('date_added',$Request->get('date_added'))->count();
					
					if($chk == 0)
					{
						$data = new Attendance;
						$data->added_by 	= Auth::user()->id;
						$data->staff_id		= $staff[$i];
						$data->type			= $type;
						$data->date_added   = $Request->get('date_added');
						$data->save();
					}	
					
 				}	
				
				//capture user activity
				$this->activity("Add ".count($staff)." Staff Attendance");
				
				return Redirect::to('center/staff/attendance')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Staff Attendance"))
		{						
			$res = Attendance::find($id);
			$staff = Staff::find($res->staff_id);
			
			return View('center.attendance.edit')->with(compact('res','staff'));								
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
		if (Auth()->check() && Perm::check("Edit Staff Attendance"))
		{						
			$res = Attendance::find($id);			
			
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'date_added' => 'required|max:50'
							
			]);
			
			if($validator->fails())
			{
				return redirect('center/staff/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				$data = Attendance::find($id);
				$data->type 		= $Request->get('type');
				$data->date_added 	= $Request->get('date_added');
				$data->updated_by 	= Auth::user()->id;
				$data->save();	
				
				//capture user activity
				$this->activity("Edit Staff Attendance");
				
				return Redirect::to('center/staff/attendance')->with('message', 'Saved Successfully');
			}				
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

	

}
