<?php
use Illuminate\Support\Facades\Route;


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
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/fetchFile/{fileID}', '\App\Http\Controllers\FileController@fetchFile')->name('fetchFile');
Route::get('/download/{fileId}', '\App\Http\Controllers\FileController@displayFile')->name('download');
Route::post('/upload', '\App\Http\Controllers\FileController@upload');
Route::view('/upload', 'uploadFile')->name('uploadfile');
Route::get('/files','\App\Http\Controllers\FileController@listFiles')->name('listFiles');                              //
Route::get('/files/user/{userId}', '\App\Http\Controllers\FileController@fetchFilesForUser');       //
Route::delete('/{fileId}','App\Http\Controllers\FileController@deleteFile');                        //
Route::get('/profile/{userId}', 'App\Http\Controllers\UserController@getProfile')->name('profile');

Route::get('/blacklistedfiles','\App\Http\Controllers\BlackListedFileController@getBlackListedFiles')->name('listBlackListedFiles');
Route::get('/blacklistedfiles/delete/{fileId}','\App\Http\Controllers\BlackListedFileController@deleteBlacklistedFile')->name('deleteBlacklist');

Route::post('/blacklist', '\App\Http\Controllers\BlackListedFileController@blacklist')->name('addBlacklist');
Route::view('/blacklist', 'blacklist')->name('addBlackListForm');


Route::get('/users', '\App\Http\Controllers\UserController@getUsers');