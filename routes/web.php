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

Route::get('/', 'TalkController@index')->name('home');

Route::prefix('/talk')->group(function () {

    Route::get('/add', 'TalkController@addTalkView')->name('talk-add');
    Route::post('/add', 'TalkController@addTalk')->name('talk-add-api');
    Route::post('/remove', 'TalkController@removeTalk')->name('talk-remove-api');

    Route::get('/attendees', 'TalkAttendeeController@index')->name('talk-attendees');
    Route::get('/attendee/add', 'TalkAttendeeController@addTalkAttendeeView')->name('talk-attendee-add');
    Route::post('/attendee/add', 'TalkAttendeeController@addTalkAttendee')->name('talk-attendee-add-api');

});

Route::get('/attendees', 'AttendeeController@index')->name('attendees');
Route::get('/attendee/add', 'AttendeeController@addAttendeeView')->name('attendee-add');
Route::post('/attendee/add', 'AttendeeController@addAttendee')->name('attendee-add-api');
