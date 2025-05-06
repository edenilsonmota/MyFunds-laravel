<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReversalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer'); // ← essa aqui
    Route::post('/reverse/{id}', [TransactionReversalController::class, 'reverse'])->name('transaction.reverse');
});

require __DIR__.'/auth.php';
