<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function deposit(User $user, float $amount): Transaction
    {
        return DB::transaction(function () use ($user, $amount) {
            $wallet = $user->wallet;

            // Cria a carteira se não existir
            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
            }

            // Atualiza saldo
            $wallet->balance += $amount;
            $wallet->save();

            // Registra transação
            return Transaction::create([
                'sender_id'   => null,
                'receiver_id' => $user->id,
                'type'        => 'deposit',
                'amount'      => $amount,
                'status'      => 'completed',
                'description' => 'Depósito',
            ]);
        });
    }

}
