<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Todo;
use DB;
use Validator;
use Redirect;
class TodoController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Todo") || Perm::check("Add Todo") || Perm::check("Edit Todo") || Perm::check("Delete Todo"))
		{
			if(Perm::check("View Todo"))
			{
				$res = Todo::orderBy("id",'DESC')->get();
			}
			else
			{
				$res = [];
			}			
			
			return View('center.Todo.index')->with('res',$res);
			
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
		if (Auth()->check() && Perm::check("Add Todo"))
		{						
			return View('center.Todo.add');			
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
		if (Auth()->check() && Perm::check("Add Todo"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'description' 	=> 'required',				
				'date_added'    => 'required|max:50'								
			]);
			
			if($validator->fails())
			{
				return redirect('center/Todo/add')->withErrors($validator)->withInput();
			}
			else
			{
				$data = new Todo;
				$data->added_by 		= Auth::user()->id;
				$data->description 		= $Request->get('description');
				$data->date_added 		= $Request->get('date_added');				
				$data->save();				
				
				//capture user activity
				$this->activity("Add New Todo List");
				
				return Redirect::to('center/Todo')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Todo"))
		{						
			$res = Todo::find($id);
			
			return View('center.Todo.edit')->with("res",$res);						
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
		if (Auth()->check() && Perm::check("Edit Todo"))
		{						
			$res = Todo::find($id);			
					
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'description' => 'required',				
				'date_added' => 'required|max:50'				
			]);
			
			if($validator->fails())
			{
				return redirect('center/Todo/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				$data = Todo::find($id);
				$data->description 		= $Request->get('description');
				$data->date_added 		= $Request->get('date_added');
				$data->status 			= $Request->get('status');
				$data->updated_by		= Auth::user()->id;
				$data->save();	
				
				//capture user activity
				$this->activity("Edit Todo List");
				
				return Redirect::to('center/Todo')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Todo"))
		{						
			$res = Todo::find($id);
			
			Todo::where('id',$id)->delete();
				
			//capture user activity
			$this->activity("Delete Todo List");
				
			return Redirect::to('center/Todo')->with('message', 'Deleted Successfully');				
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Delete Page
	*/	
	public function done($id)
	{
		if (Auth()->check())
		{						
			$res = Todo::find($id);
			
			Todo::where('id',$id)->update(['status' => 1]);
			
			//capture user activity
			$this->activity("Complete Todo List Task");
			
			return Redirect::to('center/Todo')->with('message', 'Deleted Successfully');					
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
