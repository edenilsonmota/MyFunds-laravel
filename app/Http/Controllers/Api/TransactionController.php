<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    public function index()
    {
        $transactions = auth()->user()->sentTransactions()
            ->orWhere('receiver_id', auth()->id())
            ->with(['sender', 'receiver', 'reversal'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($transactions);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        $transaction = $this->transactionService->transfer(
            $sender,
            $receiver,
            $request->amount,
            $request->description
        );

        return response()->json([
            'message' => 'Transferência realizada com sucesso.',
            'transaction' => $transaction,
        ]);
    }
}

