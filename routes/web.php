<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
    return view('welcome')->with('asd',"dsa");
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
route::post('/upload',function(Request $request){
//   dd($request);
    $zip_file = 'data/test.zip';
    $zip = new ZipArchive();
    $zip->open($zip_file,ZipArchive::CREATE | ZipArchive::OVERWRITE);
    foreach ( $request->file('files') as $file) {
        $zip->addFile($file->getPathname(), $file->getClientOriginalName());
    }
    $zip->close();

});

