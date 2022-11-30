<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('redirect', [LoginController::class, 'redirectToProvider'])->name('redirect');
Route::get('callback', [LoginController::class, 'handleProviderCallback']);

Route::get('logout', [LoginController::class, 'logout']);

Route::get('login', function(){
    Return view('googleAuth');
})->name('login');
