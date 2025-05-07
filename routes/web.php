<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionReversalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota para o Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/check-user/{user_id}', function ($user_id) {
    // Verifica se o user_id existe
    $user = User::find($user_id);

    if ($user) {
        // Se o usuário existir, retorna o nome do usuário
        return response()->json(['exists' => true, 'user' => $user]);
    }

    // Se o usuário não existir, retorna que não existe
    return response()->json(['exists' => false]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');

    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer'); // ← essa aqui
    Route::post('/reverse/{id}', [TransactionReversalController::class, 'reverse'])->name('transaction.reverse');
});

require __DIR__.'/auth.php';
