<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'UserAuthController@register')->name('register');
Route::post('login', 'UserAuthController@login')->name('login');

Route::resource('posts', 'PostController');
