<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

// Redireciona a rota raiz para a listagem de contatos
Route::get('/', function () {
    return redirect()->route('contacts.index');
});

// A rota /dashboard agora redireciona para a listagem de contatos após o login
Route::get('/dashboard', function () {
    return redirect()->route('contacts.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// Contact Management Routes (Rotas de gerenciamento de contatos)
// As rotas com segmentos fixos (como 'create') DEVEM vir antes das rotas com parâmetros {variáveis}
Route::middleware('auth')->group(function () {
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create'); // Rota para exibir o formulário de criação
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store'); // Rota para salvar um novo contato
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit'); // Rota para exibir o formulário de edição
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update'); // Rota para atualizar um contato
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy'); // Rota para deletar um contato

    // Rotas de perfil do Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas públicas (não exigem autenticação)
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index'); // Rota para listar todos os contatos
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show'); // Rota para exibir detalhes de um contato específico


require __DIR__.'/auth.php';

