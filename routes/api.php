<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', 'UserAuthController@register');
Route::post('login', 'UserAuthController@login');