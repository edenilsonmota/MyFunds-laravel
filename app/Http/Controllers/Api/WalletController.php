<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();

        $transaction = $this->walletService->deposit($user, $request->amount);

        return response()->json([
            'message' => 'Depósito realizado com sucesso.',
            'transaction' => $transaction,
        ]);
    }
}
