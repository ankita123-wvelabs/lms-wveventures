<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', function () {
	return redirect()->route('admin.login');
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {

	Auth::routes();

	Route::get('logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('locked', 'Auth\LoginController@locked')->name('locked');

	Route::group(['middleware' => ['admin', 'revalidate']], function () {
		Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
		Route::post('/leave-balance', 'DashboardController@config')->name('leave.balance');
		Route::post('/user-mispunch', 'DashboardController@userMispunch')->name('user.mispunch');
		Route::post('/get-user-mispunch', 'DashboardController@getUserMispunch')->name('get.user.mispunch');

		Route::get('change-password', 'DashboardController@getChangePassword')->name('change.password');
		Route::post('change-password', 'DashboardController@updatePassword')->name('update.password');

		Route::post('employee-delete', 'EmployeeController@delete')->name('employees.delete');
		Route::resource('employees', 'EmployeeController');

		Route::post('leaves-delete', 'LeaveController@delete')->name('leaves.delete');
		Route::post('leaves-status', 'LeaveController@changeStatus')->name('leaves.change.status');
		Route::resource('leaves', 'LeaveController');
		

		Route::post('projects-delete', 'ProjectController@delete')->name('projects.delete');
		Route::resource('projects', 'ProjectController');
		
		Route::post('get-report', 'ReportController@getReport')->name('reports.get');
		Route::get('generate-report', 'ReportController@generateReport')->name('reports.generate');
		Route::resource('reports', 'ReportController');
		Route::get('attendence-report-create', 'ReportController@attendence')->name('attendence');
		Route::post('attendence-report-post', 'ReportController@attendenceReport')->name('attendence.post');
		
		Route::get('feedbacks', 'ReportController@feedBack')->name('feedbacks.get');

		Route::post('holidays-delete', 'HolidayController@delete')->name('holidays.delete');
		Route::resource('holidays', 'HolidayController');

		Route::post('tds-delete', 'TdsController@delete')->name('tds.delete');
		Route::post('get-salary', 'TdsController@getSalary')->name('tds.salary');
		Route::get('salary-processing', 'TdsController@salaryProcessing')->name('tds.salary.processing');
		Route::post('get-user-salary', 'TdsController@getUserSalary')->name('tds.salary.user');
		Route::get('user-salary-slip/{user}/{month}', 'TdsController@viewSalarySlip')->name('tds.salary.slip.view');
		Route::resource('tds', 'TdsController');

		Route::post('auth-devices-delete', 'AuthDeviceController@delete')->name('auth-devices.delete');
		Route::post('auth-devices-status', 'AuthDeviceController@changeStatus')->name('auth-devices.change.status');
		Route::resource('auth-devices', 'AuthDeviceController');

	});
});