<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\User;
use App\Activity;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	
	//capture user activity
	public function activity($notes)
	{
		//main admin activity will not be save,remove this condition if you want to save
		if(Auth::user()->id != env("admin_id"))
		{
			date_default_timezone_set("Asia/Kolkata");
		
			$data = new Activity;
			$data->user_id 		= Auth::user()->id;
			$data->notes   		= $notes;
			$data->date_added	= date("Y-m-d");
			$data->time_added	= date("H:i:s");
			$data->save();
		}		
	}
}
