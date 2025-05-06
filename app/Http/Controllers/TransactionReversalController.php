<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionReversalService;
use Illuminate\Http\Request;

class TransactionReversalController extends Controller
{
    public function __construct(private TransactionReversalService $reversalService) {}

    public function reverse(Request $request, $transactionId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $transaction = Transaction::findOrFail($transactionId);

            $this->reversalService->reverse($transaction, auth()->user(), $request->reason);

            return back()->with('success', 'Transação revertida com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
