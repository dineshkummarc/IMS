<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
@Login in admin panel & admin setting
*/
Route::get('','center\AdminController@index');
Route::get('login','center\AdminController@index');
Route::post('login','center\AdminController@login');
Route::get('home','center\AdminController@home');
Route::get('center/home','center\AdminController@home');
Route::get('center/logout','center\AdminController@logout');
Route::get('center/setting','center\AdminController@setting');
Route::post('center/setting','center\AdminController@update');

/*
@Manage Batch
*/
Route::get('center/batch','center\BatchController@index');
Route::get('center/batch/add','center\BatchController@add');
Route::post('center/batch/add','center\BatchController@_add');
Route::get('center/batch/edit/{id}','center\BatchController@edit');
Route::post('center/batch/edit/{id}','center\BatchController@_edit');
Route::get('center/batch/delete/{id}','center\BatchController@delete');

/*
@Manage Course
*/
Route::get('center/course','center\CourseController@index');
Route::get('center/course/add','center\CourseController@add');
Route::post('center/course/add','center\CourseController@_add');
Route::get('center/course/edit/{id}','center\CourseController@edit');
Route::post('center/course/edit/{id}','center\CourseController@_edit');
Route::get('center/course/delete/{id}','center\CourseController@delete');

/*
@Manage Staff
*/
Route::get('center/staff','center\StaffController@index');
Route::get('center/staff/add','center\StaffController@add');
Route::post('center/staff/add','center\StaffController@_add');
Route::get('center/staff/edit/{id}','center\StaffController@edit');
Route::post('center/staff/edit/{id}','center\StaffController@_edit');
Route::get('center/staff/delete/{id}','center\StaffController@delete');

/*
@Manage Expense
*/
Route::get('center/expense','center\ExpenseController@index');
Route::get('center/expense/add','center\ExpenseController@add');
Route::post('center/expense/add','center\ExpenseController@_add');
Route::get('center/expense/edit/{id}','center\ExpenseController@edit');
Route::post('center/expense/edit/{id}','center\ExpenseController@_edit');
Route::get('center/expense/delete/{id}','center\ExpenseController@delete');

/*
@Manage Staff Attendance
*/
Route::get('center/staff/attendance','center\StaffAttendanceController@index');
Route::get('center/staff/attendance/add','center\StaffAttendanceController@add');
Route::post('center/staff/attendance/add','center\StaffAttendanceController@_add');
Route::get('center/staff/attendance/edit/{id}','center\StaffAttendanceController@edit');
Route::post('center/staff/attendance/edit/{id}','center\StaffAttendanceController@_edit');
Route::get('center/staff/attendance/delete/{id}','center\StaffAttendanceController@delete');
Route::get('center/attendanceSearch','center\StaffAttendanceController@search');

/*
@Manage Student
*/
Route::get('center/student','center\StudentController@index');
Route::get('center/student/search','center\StudentController@search');
Route::get('center/student/add','center\StudentController@add');
Route::post('center/student/add','center\StudentController@_add');
Route::get('center/student/edit/{id}','center\StudentController@edit');
Route::post('center/student/edit/{id}','center\StudentController@_edit');
Route::get('center/student/delete/{id}','center\StudentController@delete');
Route::get('center/student/view/{id}','center\StudentController@view');
Route::post('center/student/img/{id}','center\StudentController@changeImage');

//extra charge
Route::get('center/student/extraCharge/{id}','center\ExtraChargeController@index');
Route::post('center/student/extraCharge/{id}','center\ExtraChargeController@add');
Route::get('center/student/extraCharge/{fid}/{id}','center\ExtraChargeController@edit');
Route::post('center/student/extraCharge/{fid}/{id}','center\ExtraChargeController@_edit');
Route::get('center/student/deleteCharge/{fid}/{id}','center\ExtraChargeController@delete');

//Course chnage
Route::get('center/student/courseEdit/{id}','center\CourseChnageController@index');
Route::post('center/student/courseEdit/{id}','center\CourseChnageController@add');


/*
@Manage Fee
*/
Route::get('center/fee','center\FeeController@index');
Route::get('center/fee/view/{id}','center\FeeController@view');
Route::get('center/fee/add','center\FeeController@add');
Route::post('center/fee/add','center\FeeController@_add');
Route::get('center/fee/edit/{id}/{sid}','center\FeeController@edit');
Route::post('center/fee/edit/{id}/{sid}','center\FeeController@_edit');
Route::get('center/fee/delete/{id}','center\FeeController@delete');
Route::get('center/fee/reminder','center\FeeController@reminder');

/*
@Manage Enquiry
*/
Route::get('center/enquiry','center\EnquiryController@index');
Route::get('center/enquiry/view/{id}','center\EnquiryController@view');
Route::get('center/enquiry/add','center\EnquiryController@add');
Route::post('center/enquiry/add','center\EnquiryController@_add');
Route::get('center/enquiry/edit/{id}','center\EnquiryController@edit');
Route::post('center/enquiry/edit/{id}','center\EnquiryController@_edit');
Route::get('center/enquiry/delete/{id}','center\EnquiryController@delete');
Route::get('center/enquiry/makeAdmission/{id}','center\EnquiryController@makeAdmission');

/*
@Manage Results
*/
Route::get('center/result','center\ResultController@index');
Route::post('center/result/get','center\ResultController@get');
Route::post('center/result/update','center\ResultController@update');

/*
@Student Passout
*/
Route::get('center/result/passOut','center\ResultController@passOut');
Route::get('center/result/edit/{id}','center\ResultController@edit');
Route::post('center/result/edit/{id}','center\ResultController@_edit');
Route::get('center/result/RemoveDocs/{id}/{sid}','center\ResultController@remove');

/*
@Manage Results
*/
Route::get('center/Todo','center\TodoController@index');
Route::get('center/Todo/view/{id}','center\TodoController@view');
Route::get('center/Todo/add','center\TodoController@add');
Route::post('center/Todo/add','center\TodoController@_add');
Route::get('center/Todo/edit/{id}','center\TodoController@edit');
Route::post('center/Todo/edit/{id}','center\TodoController@_edit');
Route::get('center/Todo/delete/{id}','center\TodoController@delete');
Route::get('center/Todo/done/{id}','center\TodoController@done');

/*
@Need help
*/
Route::get('center/help','center\AdminController@help');
Route::post('center/help','center\AdminController@_help');

/*
@Reporting
*/
Route::get('center/feeReport','center\ReportController@fee');
Route::post('center/feeReport','center\ReportController@_fee');

//student report
Route::get('center/studentReport','center\ReportController@student');
Route::post('center/studentReport','center\ReportController@_student');

//staff report
Route::get('center/expenseReport','center\ExpenseReportController@expense');
Route::post('center/expenseReport','center\ExpenseReportController@_expense');

//App setting
Route::get('center/Appsetting','center\AppSettingController@index');
Route::post('center/Appsetting','center\AppSettingController@add');
Route::get('center/Appsetting/imgRemove','center\AppSettingController@imgRemove');

//push notification
Route::get('center/app/notification','center\AppSettingController@notification');
Route::post('center/app/notification','center\AppSettingController@_notification');

//upload assignment
Route::get('center/app/assignment','center\AppSettingController@assignment');
Route::get('center/app/assignment/add','center\AppSettingController@assignmentAdd');
Route::post('center/app/assignment/add','center\AppSettingController@_assignmentAdd');
Route::get('center/app/assignment/delete/{id}','center\AppSettingController@delete');

/*
@studentAttendance
*/
Route::get('center/studentAttendance','center\studentAttendanceController@index');
Route::get('center/studentAttendance/add','center\studentAttendanceController@add');
Route::post('center/studentAttendance/get','center\studentAttendanceController@get');
Route::post('center/studentAttendance/add','center\studentAttendanceController@_add');
Route::get('center/studentAttendance/view','center\studentAttendanceController@view');
Route::get('center/studentAttendance/delete/{id}','center\studentAttendanceController@delete');

/*
@User activity
*/
Route::get('center/activity',function(){
		
		if(Auth::user()->id == env("admin_id"))
		{
			$res = DB::table("user_activity")->orderBy("id","DESC")->paginate(100);
		
			return View("center.activity")->with("res",$res);
		}
		else
		{
			return Redirect::to('login#Login')->with('loginError', 'Sorry ! you dont have permission to access this page.');
		}
		
});