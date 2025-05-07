<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Exibe a página do dashboard com o saldo do usuário e transações recentes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtendo o saldo do usuário logado
        $user = auth()->user();
        $balance = optional($user->wallet)->balance ?? 0;

        // Obtendo as transações recentes
        $transactions = $this->transactionService->getUserTransactions($user);

        return view('dashboard', compact('balance', 'transactions'));
    }
}
