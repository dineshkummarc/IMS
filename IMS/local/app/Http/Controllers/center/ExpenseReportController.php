<?php namespace App\Http\Controllers\center;

use App\Http\Requests;
use App\Helpers\Perm;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Expense;
use App\StudentCourse;
use App\Course;
use App\Fee;
use DB;
use Validator;
use Redirect;
use Excel;
class ExpenseReportController extends Controller {
	
	/*
	@Fee Report
	*/	
	public function expense()
	{
		if (Auth()->check() && Perm::check("View Expense Reporting"))
		{						
			return View('center.report.expense');			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
	/*
	@Fee Report
	@get all data from DB with Date range & Course wise
	*/	
	public function _expense(Request $Request)
	{
		if (Auth()->check() && Perm::check("View Expense Reporting"))
		{
			//get fee detail from db with from,to and courseId
			$res = Expense::where(function($query) use ($Request) {
				
			$from 				= $Request->has('from') ? $Request->get('from') : null;	
			$to   				= $Request->has('to') ? $Request->get('to') : null;	

			if(isset($from))
			{
				$query->where('date_added','>=',$from);
			}
			
			if(isset($to))
			{
				$query->where('date_added','<=',$to);
			}					
				
			})->orderBy('date_added','DESC')->get();
			
			//if no record found
			if(count($res) == 0)
			{
				return Redirect::to('center/expenseReport')->with('message', 'Sorry ! No records found');
				exit;
			}
			
			//check for report type,its for excel
			if($Request->get('type') == 0)
			{
				//capture user activity
				$this->activity("Download Expense Report");
				
				//create excel file
				Excel::create('Expense Report Between '.$Request->get('from').' To '.$Request->get('to'), function($excel) use($res) {

				$excel->sheet('First', function($sheet) use($res)
				{		
						//define Top Header Row
						$sheet->row(1, array(
							 
							 'SNO','Narration','Amount','Date Added'
						));
						
						
						$i = 1;			
						$sno = 0;			
						
						//print row data
						foreach($res as $row)
						{
							$i++;
							$sno++;							
														
							//date added formate change					
							$d 	= strtotime($row->date_added);
							$od = date("d-M-y", $d);
							
							//count for total
							$totalArray[] = $row->amount;
							
							$sheet->row($i, array(						 
							
							$sno,
							$row->narration,							
							'Rs.'.$row->amount,
							$od					
							 
							));
						}
						
						//total amount
						$sheet->row(count($res) + 2, array(
							 
							 '','Total','Rs.'.array_sum($totalArray),''
						));
						
						$sheet->setOrientation('landscape');

				});
				})->export('xlsx');
			}
			//check for report type,its for plan view
			else
			{
							
				$f 		= strtotime($Request->get('from'));
				$from 	= date("d-M-y", $f);
				
				$t 		= strtotime($Request->get('to'));
				$to 	= date("d-M-y", $t);
				
				//capture user activity
				$this->activity("View Expense Report");
				
				return View('center.report.expenseReport')->with(compact('res','from','to'));
			}	
			
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Please Login ! For Access This Page');
		}
	}
	
		
}
