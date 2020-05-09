<?php

use App\Events\TaskEvent;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/event', function () {
        event(new TaskEvent('hey how are you?'));
    });
    
    Route::get('/listen', function () {
        return view('listenBroadcast');
    });
});

Route::get('/home', 'HomeController@index')->name('home');
