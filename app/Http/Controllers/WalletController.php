<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;

/**
 * Class WalletController
 * Controlador para gerenciar a carteira do usuário.
 */
class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        try {
            $this->walletService->deposit(auth()->user(), $request->amount);
            return back()->with('success', 'Depósito realizado com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
