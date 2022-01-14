<?php

use App\Http\Controllers\PointsController;
use App\Http\Controllers\TracksController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::prefix('track')->name('frontend.track.')->group(function () {
    Route::get('{id}', 'FrontController@index')->name('view');

    Route::get('{track_id}/log', 'ClaimController@logForFrontend')->name('log');
    Route::get('{track_id}/log/data', 'ClaimController@claims')->name('log.data');

    Route::get('{track_id}/waypoint/{waypoint_id}/claim', 'ClaimController@claim')->name('save');
    Route::post('{track_id}/waypoint/{waypoint_id}/claim', 'ClaimController@saveClaim')->name('saveClaim');
});

Route::prefix('log/track')->name('frontend.log.')->group(function () {
    Route::get('{track_id}', 'ClaimController@logForFrontend')->name('log');
});

Route::get('/admin/photo/{folder}/{track_id}/{filename}', 'ClaimController@showPhoto');

// Route::get('/track/{id}', function () {
//     return view('frontpage');
// });
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', 'TracksController@index')->name('dashboard');
    Route::get('/admin/track/{id}', 'TracksController@view')->name('admin.track.point');
    Route::get('/admin/tracks', 'TracksController@allTracks');
    Route::get('/admin/getpoints/{id}',  'PointsController@getpoints');
    Route::post('/admin/savepoints/{id}',  'PointsController@savepoints');
    Route::post('/admin/save-claim-setting/{point_id}',  'PointsController@saveClaimSetting');
    Route::get('/admin/get-claim-setting/{point_id}',  'PointsController@getClaimSetting');

    Route::post('/admin/newtrack', 'TracksController@store')->name('newtrack');
    Route::post('/admin/edittrack', 'TracksController@update')->name('edittrack');


    Route::get('/admin/addtrack', function () {
        return view('addtrack');
    });
    Route::get('/admin/edittrack/{id}', 'FrontController@edit')->name('admin.track.edit');
    Route::get('/admin/removetrack/{id}', 'TracksController@removetrack');


    Route::get('/admin/help', 'FrontController@help');

    Route::get('/admin/changepassword', 'HomeController@showChangePasswordForm');
    Route::post('/admin/changepassword', 'HomeController@changePassword')->name('changePassword');

    Route::get('/admin/log', 'ClaimController@logs')->name('showlogs');
    Route::get('/admin/log/track/{track_id}', 'ClaimController@singleLog')->name('showlogssingle');
    Route::get('/admin/log/photo/download/{track_id}', 'ClaimController@downloadPhotoClaim')->name('downloadPhotoClaim');
    Route::get('/admin/log/photo/delete/{track_id}', 'ClaimController@deletePhotoClaim')->name('deletePhotoClaim');
    Route::get('/admin/claims', 'ClaimController@claims')->name('showclaims');

    Route::prefix('admin')->group(function () {
        Route::prefix('track')->name('track.')->group(function () {
            Route::post('duplicate', [TracksController::class, 'duplicate'])->name('duplicate');
            Route::post('move', [TracksController::class, 'move'])->name('move');
            Route::post('hide-menu-bar', [TracksController::class, 'hideMenuBar'])->name('hide_menu_bar');
            Route::post('show_log_public', [TracksController::class, 'showLogPublic'])->name('show_log_public');
        });

        Route::prefix('point')->name('point.')->group(function () {
            Route::post('save_color', [PointsController::class, 'saveColor'])->name('save_color');
        });
    });
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/adduser', function () {
        return view('adduser');
    });
    Route::post('/admin/registeruser', 'Auth\UsersController@create')->name('registeruser');
    Route::get('/admin/removeu/{id}', 'Auth\UsersController@removeuser');
});
Route::get('/getpoint/{id}',  'PointsController@getpoint');
// Route::middleware('auth')->get('/track/{id}', 'FrontController@index');

Route::get('/', function () {
    return view('homepage');
})->name('home');




// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/initsetup', function () {
    if (!Schema::hasTable('tracks')) {
        $output = [];
        \Artisan::call('key:generate', $output);
        \Artisan::call('config:clear', $output);
        \Artisan::call('migrate', $output);
        \Artisan::call('db:seed', $output);
        dd($output);
    }
});
Route::get('/clear-cache', function () {
    $output = [];
    \Artisan::call('cache:clear', $output);
    dd($output);
});
