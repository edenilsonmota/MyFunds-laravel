<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TransactionReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionReversalController extends Controller
{
    public function __construct(private TransactionReversalService $reversalService) {}

    public function reverse(Request $request, $transactionId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($transactionId);

        $reversal = $this->reversalService->reverse(
            $transaction,
            Auth::user(),
            $request->reason
        );

        return response()->json([
            'message' => 'Transação revertida com sucesso.',
            'reversal' => $reversal,
        ]);
    }
}
