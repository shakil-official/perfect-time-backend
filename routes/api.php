<?php

use App\Http\Controllers\Auth\AdminAuthController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/admin/login', 'Auth\AdminAuthController@login');
Route::post('/user/login', 'Auth\UserAuthController@login');
Route::post('/user/signup', 'Auth\UserAuthController@signup');
Route::post('user/slug/info', 'Auth\UserAuthController@userSlugInfo');
Route::post('slot/booking', 'Auth\UserAuthController@slotBooking');
Route::post('slot/used', 'Auth\UserAuthController@getUsedSlot');

Route::post('/user/account/update', 'Auth\UserAuthController@accountUpdate')->middleware('jwt.user.verify');
Route::post('/user/account/info', 'Auth\UserAuthController@accountInfo')->middleware('jwt.user.verify');
Route::post('/create/event', 'Event\EventController@createEvent')->middleware('jwt.user.verify');
Route::post('/get/event/list', 'Event\EventController@getEventList')->middleware('jwt.user.verify');
Route::post('/get/event/schedule/list', 'Event\EventController@getEventScheduleList')->middleware('jwt.user.verify');
