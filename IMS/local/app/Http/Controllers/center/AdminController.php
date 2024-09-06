<?php
namespace App\Http\Controllers\center;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Student;
use App\StudentCourse;
use App\Course;
use App\Todo;
use App\Fee;
use DB;
use Validator;
use Redirect;
use Mail;
class AdminController extends Controller {
	
	/*
	@This is for login page
	*/
	public function index()
	{
		return View("center.index");
	}
	
	/*
	@Login
	*/	
	public function login(Request $Request)
	{
		$username = $Request->input('user_name');
		$password = $Request->input('password');
		
		if (Auth::attempt(['user_name' => $username, 'password' => $password,'status' => 0]))
		{							
			return Redirect::to('center/home')->with('message', 'You are now logged in!');			
		}
		else
		{
			return Redirect::to('login')->with('message', 'Your username/password combination was incorrect')->withInput();
		}
	}
	
	
	public function home()
	{
		if (Auth()->check())
		{
			$res = Auth()->user();
			
			$todoRes = Todo::where('status',0)->orderBy('date_added','ASC')->take(10)->get();
			
			//Date calculate
			$toDate 	= date("Y-m-d");			
			$dateStop  	= date('Y-m-d', strtotime($toDate . ' +7 day'));
			
			$fees = Fee::where('due_date','>=',$toDate)->where('due_date','<=',$dateStop)->select('student_id')->distinct()->take(20)->lists('student_id');
			
			$getStudent = Student::whereIn('id',$fees)->get();
			
			return View('center.home')->with(compact('res','todoRes','getStudent'));
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function logout()
	{
		auth()->logout();
		
		return Redirect::to('login#Login')->with('message', 'Logout Successfully !');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function setting()
	{
		if (Auth()->check())
		{
			$array = auth()->user();
			
			return View('center.setting')->with('array',$array);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{		
		if (Auth()->check())
		{
			//chk for username
			$chk = User::where("id","!=",Auth::user()->id)->where("user_name",$request->get('user_name'))->count();
			
			if($chk > 0)
			{
				return Redirect::to('center/setting')->with('error', 'This username is already exists.');
				exit;
			}
			
			$array = auth()->user();
			
			$update = User::find($array->id);
			
			$update->user_name 	 = $request->get('user_name');			
			$update->center_name = $request->get('center_name');			
			$update->person_name = $request->get('person_name');			
			$update->email 		 = $request->get('email');			
			$update->address	 = $request->get('address');			
			
			if($request->get('password')!='')
			{
				$update->password 		= bcrypt($request->get('password'));
				$update->shw_password 	= $request->get('password');
			}
			
			$update->save();
			
			//capture user activity
			$this->activity("Update Account Setting");
			
			return Redirect::to('center/setting')->with('message', 'Updated Successfully !');
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
		
	}
	
}
