<?php namespace App\Http\Controllers\center;

use App\Helpers\Perm;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Expense;
use DB;
use Validator;
use Redirect;
class ExpenseController extends Controller {
	
	/*
	@Index page
	*/	
	public function index()
	{
		if (Auth()->check() && Perm::check("View Expense") || Perm::check("Add Expense") || Perm::check("Edit Expense") || Perm::check("Delete Expense"))
		{
			if(Perm::check("View Expense"))
			{
				$res = Expense::orderBy("date_added",'DESC')->paginate(100);
			}
			else
			{
				$res = [];
			}		
			
			return View('center.expense.index')->with('res',$res);
			
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
		if (Auth()->check() && Perm::check("Add Expense"))
		{						
			return View('center.expense.add');			
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
		if (Auth()->check() && Perm::check("Add Expense"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'narration' 	=> 'required|max:50',				
				'amount'		 => 'required|max:50',								
				'date_added'	 => 'required|max:50'								
			]);
			
			if($validator->fails())
			{
				return redirect('center/expense/add')->withErrors($validator)->withInput();
			}
			else
			{
				$data = new Expense;
				$data->added_by 	= Auth::user()->id;
				$data->narration 	= $Request->get('narration');
				$data->amount 		= $Request->get('amount');
				$data->date_added 	= $Request->get('date_added');				
				$data->save();				
				
				//capture user activity
				$this->activity("Add New Expense - ".$data->narration);
				
				return Redirect::to('center/expense')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Edit Expense"))
		{						
			$res = Expense::find($id);
			
			return View('center.expense.edit')->with("res",$res);					
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
		if (Auth()->check() && Perm::check("Edit Expense"))
		{						
			//Validation
			 $validator = Validator::make($Request->all(), [
				
				'narration' 	=> 'required|max:50',				
				'amount' 		=> 'required|max:50',				
				'date_added' 	=> 'required|max:50'				
			]);
			
			if($validator->fails())
			{
				return redirect('center/expense/edit/'.$id)->withErrors($validator)->withInput();
			}
			else
			{
				$data = Expense::find($id);
				$data->narration 	= $Request->get('narration');
				$data->amount 		= $Request->get('amount');
				$data->date_added 	= $Request->get('date_added');				
				$data->updated_by 	= Auth::user()->id;				
				$data->save();	
				
				//capture user activity
				$this->activity("Update Expense - ".$data->narration);
				
				return Redirect::to('center/expense')->with('message', 'Saved Successfully');
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
		if (Auth()->check() && Perm::check("Delete Expense"))
		{						
			$res = Expense::find($id);
			
			Expense::where('id',$id)->delete();
			
			//capture user activity
			$this->activity("Delete Expense - ".$res->narration);
			
			return Redirect::to('center/expense')->with('message', 'Deleted Successfully');					
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	

}
