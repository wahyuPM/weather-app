<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\FetchWeatherDataAction; // Import action Anda
use App\Actions\Profile\EditProfileAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Auth\ShowLoginAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Menggunakan Action sebagai controller
Route::get('/weather', FetchWeatherDataAction::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', EditProfileAction::class)->name('profile.edit');
    Route::patch('/profile', UpdateProfileAction::class)->name('profile.update');
    Route::delete('/profile', DeleteProfileAction::class)->name('profile.destroy');
});

Route::get('/login', ShowLoginAction::class)->name('login');
Route::post('/login', LoginAction::class);
Route::post('/logout', LogoutAction::class);
