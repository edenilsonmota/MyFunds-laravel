<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionReversal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionReversalService
{
    public function reverse(Transaction $transaction, User $user, ?string $reason = null): TransactionReversal
    {
        return DB::transaction(function () use ($transaction, $user, $reason) {
            if ($transaction->status !== 'completed') {
                throw new \Exception('Transação já foi revertida ou está pendente.');
            }

            if ($transaction->type === 'transfer') {
                $receiverWallet = $transaction->receiver->wallet;
                $senderWallet = $transaction->sender->wallet;

                if ($receiverWallet->balance < $transaction->amount) {
                    throw new \Exception('Saldo insuficiente no destinatário para reversão.');
                }

                $receiverWallet->balance -= $transaction->amount;
                $senderWallet->balance += $transaction->amount;

                $receiverWallet->save();
                $senderWallet->save();
            }

            if ($transaction->type === 'deposit') {
                $receiverWallet = $transaction->receiver->wallet;

                if ($receiverWallet->balance < $transaction->amount) {
                    throw new \Exception('Saldo insuficiente para reverter o depósito.');
                }

                $receiverWallet->balance -= $transaction->amount;
                $receiverWallet->save();
            }

            $transaction->status = 'reversed';
            $transaction->save();

            return TransactionReversal::create([
                'original_transaction_id' => $transaction->id,
                'reversed_by' => $user->id,
                'reason' => $reason,
                'created_at' => now(),
            ]);
        });
    }
}
