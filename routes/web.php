<?php
// File: routes/web.php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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

// Default Laravel welcome route, can be removed or redirected
Route::get('/', function () {
    return view('welcome');
});

// Laravel Breeze dashboard route, also behind auth middleware
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Contact Management Routes
// Index and Show are public
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');

// Other CRUD operations require authentication
Route::middleware('auth')->group(function () {
    // Estas são as rotas que precisam de autenticação
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // As rotas de perfil do Breeze também estão aqui
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Include authentication routes provided by Breeze (VERY IMPORTANT!)
require __DIR__.'/auth.php';