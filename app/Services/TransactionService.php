<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionService
{
    public function transfer(User $sender, User $receiver, float $amount, ?string $description = null): Transaction
    {
        return DB::transaction(function () use ($sender, $receiver, $amount, $description) {
            // Valida o saldo do remetente
            $senderWallet = $sender->wallet;
            if ($senderWallet->balance < $amount) {
                throw new Exception('Saldo insuficiente.');
            }

            // Verifica se o destinatário é o remetente
            if ($sender->id === $receiver->id) {
                throw new Exception('Você não pode transferir para si mesmo.');
            }

            // Deduz o valor do saldo do remetente
            $senderWallet->balance -= $amount;
            $senderWallet->save();

            // Adiciona o valor ao saldo do destinatário
            $receiver->wallet->balance += $amount;
            $receiver->wallet->save();

            // Cria a transação
            return Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'type' => 'transfer',
                'amount' => $amount,
                'status' => 'completed',
                'description' => $description,
            ]);
        });
    }
}
