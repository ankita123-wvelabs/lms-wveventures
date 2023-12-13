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
	\Artisan::call('migrate');
	return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('apps', function () {
	return view('app_download');
});


Route::get('cache-clear', function(){

    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');

    return "Done";
});

// Route::get('download/{filename}', function($filename)
// {
//     // Check if file exists in app/storage/file folder
//     $file_path = storage_path() .'/file/'. $filename;
//     if (file_exists($file_path))
//     {
//         // Send Download
//         return Response::download($file_path, $filename, [
//             'Content-Length: '. filesize($file_path)
//         ]);
//     }
//     else
//     {
//         // Error
//         exit('Requested file does not exist on our server!');
//     }
// })->where('filename', '[A-Za-z0-9\-\_\.]+');
