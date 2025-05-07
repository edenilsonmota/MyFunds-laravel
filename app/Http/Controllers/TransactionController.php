<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;

 /**
 * Class Realiza a transferência de valores entre contas.
*/
class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    public function transfer(Request $request)
    {
        // Valida o ID do destinatário (receiver_id) recebido no formulário
        $receiver = User::find($request->receiver_id);

        if (!$receiver) {
            return back()->withErrors(['receiver_id' => 'Número de usuário não existe.']);
        }

        // Obtém o usuário autenticado
        $sender = auth()->user();
        $amount = $request->amount;

        // Chama o serviço para realizar a transferência
        try {
            $this->transactionService->transfer($sender, $receiver, $amount, $request->description);
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }

        return back()->with('success', 'Transferência realizada com sucesso.');
    }
}
