<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Batch;
use DB;
use Validator;
use Redirect;
class BatchController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if(Auth()->check() && Perm::check("View Batch") || Perm::check("Add Batch") || Perm::check("Edit Batch") || Perm::check("Delete Batch"))
		{
			if(Perm::check("View Batch"))
			{
				$res = Batch::orderBy("id",'DESC')->get();
			}
			else
			{
				$res = [];
			}	
			
			return View('center.batch.index')->with('res',$res);			
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
		if (Auth()->check() && Perm::check("Add Batch"))
		{						
			return View('center.batch.add');			
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
		if (Auth()->check() && Perm::check("Add Batch"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' => 'required|max:50'				
			]);
			
			if($validator->fails())
			{
				return redirect('center/batch/add')->withErrors($validator)->withInput();
			}
			else
			{
				$data = new Batch;
				$data->added_by		= Auth::user()->id;
				$data->name 		= $Request->get('name');
				$data->status 		= $Request->get('status');
				$data->save();

				//capture user activity
				$this->activity("Add New Batch - ".$Request->get('name'));
				
				return Redirect::to('center/batch')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Batch"))
		{						
			$res = Batch::find($id);
			
			return View('center.batch.edit')->with("res",$res);						
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
		if (Auth()->check() && Perm::check("Edit Batch"))
		{						
			$res = Batch::find($id);		
			
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'name' => 'required|max:50'				
			]);
			
			if($validator->fails())
			{
				return redirect('center/batch/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				$data = Batch::find($id);
				$data->name 		= $Request->get('name');
				$data->status 		= $Request->get('status');
				$data->updated_by 	= Auth::user()->id;
				$data->save();
				
				//capture user activity
				$this->activity("Update ".$data->name." Batch");
				
				return Redirect::to('center/batch')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Batch"))
		{						
			$res = Batch::find($id);
			Batch::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete ".$res->name." Batch");
			
			return Redirect::to('center/batch')->with('message', 'Deleted Successfully');							
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
