<?php

use App\Http\Controllers\ConnectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/post', [PostController::class, 'index'])->name('post');

    Route::get('/connect', [ConnectController::class, 'index'])->name('connect');
    Route::get('/connect/disconnect', [ConnectController::class, 'handleDeconnect'])->name('disconnect');
    Route::get('/connect/facebook/redirect', [ConnectController::class, 'handleFacebookRedirect']);
    Route::get('/connect/facebook/callback', [ConnectController::class, 'handleFacebookCallback']);
});





require __DIR__ . '/auth.php';
