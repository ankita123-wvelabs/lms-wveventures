<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
use App\Models\User;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => 'json.response'], function () {
	Route::post('login', 'PassportController@login');
	Route::post('register', 'PassportController@register');
	Route::post('face-register', 'PassportController@faceRegister');
	Route::post('in-outs', 'PassportController@inOuts');

	Route::post('image-clarification', function(Request $request){

		if(isset($request->image_type) && $request->image_type == 'url') {
			$data = User::orderBy('name', 'asc')->get();
		} else {
			
			$data = User::orderBy('name', 'asc')->get();
			foreach ($data as $key => $value) {
				// if(file_exists(public_path($value))) {
					$explode = explode(env('APP_URL'), $value['image']);
					if(sizeof($explode) >= 2) {
						$file = file_get_contents(public_path($explode[1]));

						$value['base64'] = base64_encode($file);
						// User::where('id',$value['id'])->update(['base64' => base64_encode($file)]);
					}
				// }
			}
		}

		return response()->json([ 'data' => $data, 'code' => 200],200);
	});


	Route::middleware('auth:api')->group(function () {
		Route::post('home', 'HomeController@index');

		Route::post('colleague', 'HomeController@colleague');
		Route::post('projects', 'HomeController@projects');

		Route::post('user', 'PassportController@details');
		Route::post('update-profile', 'PassportController@updateProfile');
		Route::post('logout', 'PassportController@logout');

		Route::post('leave-create', 'LeaveController@create');
		Route::post('leave-history', 'LeaveController@index');
		Route::post('attendences', 'LeaveController@attendences');

		Route::post('feedback', 'HomeController@feedback');
		Route::post('holidays', 'HomeController@holidays');

		Route::post('tds', 'TdsController@store');
		Route::post('salary', 'TdsController@salary');
		Route::post('mail-salary-slip', 'TdsController@mailSalarySlip');

		Route::post('notifications', 'NotificationController@index');
	});
});