<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/2fa/setup',   [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});


Route::middleware(['auth', '2fa'])->group(function () {
    Route::post('/2fa', function () {
        return redirect()->intended(route('dashboard'));
    })->name('2fa');
});


Route::middleware(['auth', 'verified', '2fa.required', '2fa'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
 
    // Add all your other protected routes here
});

require __DIR__.'/auth.php';
