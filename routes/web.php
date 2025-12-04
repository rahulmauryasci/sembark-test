<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // invite user management routes can be added here
    Route::get('/invite-member', [UserController::class, 'invite'])->name('users.invite');
    Route::post('/invite', [UserController::class, 'store'])->name('users.store');

    // Generate Short URL routes can be added here
    Route::get('/shorten-url', [UserController::class, 'shortenUrl'])->name('url.shorten');
    Route::post('/shorten-url', [UserController::class, 'storeShortUrl'])->name('url.store');

    // Redirect Short URL
    Route::get('/s/{code}', [UserController::class, 'redirectShortUrl'])->name('short.url');

});

require __DIR__.'/auth.php';
