<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Client;
use DB;
use Validator;
use Redirect;
use Mail;
class WelcomeController extends Controller {

	/*
	@Index page
	*/
	public function index()
	{
		return View('index');
	}
	
	/*
	@Subscribe user
	*/	
	public function subscribe(Request $Request)
	{
		$chk = DB::table("subscribe")->where('email',$Request->get('email'))->count();
		
		if($chk == 0)
		{
			DB::table("subscribe")->insert(['email' => $Request->get('email')]);
			
			return Redirect::to($Request->get('url').'#subScribe')->with('subMsg', 'Thank You ! Subscribe Successfully.');
		}
		else
		{
			return Redirect::to($Request->get('url').'#subScribe')->with('subError', 'Sorry ! Already Subscribed');
		}		
		
	}
	
	/*
	@features
	*/	
	public function fet()
	{		
		return View('features');
	}
	
	/*
	@price
	*/	
	public function price()
	{		
		return View('price');
	}
	
	/*
	@contact
	*/	
	public function contact()
	{		
		return View('contact');
	}
	
	/*
	@contact
	*/	
	public function _contact(Request $Request)
	{		
		//send email
		$name  	= $Request->get('name');
		$email 	= $Request->get('email');
		$mobile = $Request->get('mobile');
		$msg 	= $Request->get('message');
		
		$data  = array('name' => $name, 'email' => $email, 'mobile' => $mobile, 'msg' => $msg);
		
		Mail::send('contactMail', $data, function($message)
		{			
			$message->to("p.saharan@hispirits.biz")->subject("Contact Us");				
		});
		
		return Redirect::to('contact.html')->with('message', 'Thank You ! Message sent successfully.We will contact you soon.');
	}
	
	/*
	@term & condition
	*/	
	public function term()
	{		
		return View('term');
	}

}
