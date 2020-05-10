<?php

use App\Events\TaskEvent;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskCompleted1;
use App\User;




Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/event', function () {
        event(new TaskEvent('hey how are you?'));
    });
    
    Route::get('/listen', function () {
        return view('listenBroadcast');
    });

    Route::get('/', function () {
        /* Using The Notifiable Trait */
    
        User::find(Auth::id())->notify(new TaskCompleted());
        // User::find(1)->notify((new TaskCompleted())->delay($when));
    
        /* Using The Notification Facade */
        // $users = User::find(1);
    
        // $when = now()->addSecond(10);
    
        // $users->notify((new TaskCompleted())->delay($when));
    
    
        // Notification::route('mail', 'taylor@example.com')
        //         ->notify(new TaskCompleted());
    
        return view('welcome');
    });

    Route::get('maskAsRead', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    });
});

Route::get('/home', 'HomeController@index')->name('home');
