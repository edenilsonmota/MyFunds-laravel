<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:flex-wrap items-stretch justify-between gap-4">
            {{-- Informações User --}}
            <div class="flex-1 bg-gray-800 p-6 rounded-lg shadow-xl flex flex-col items-center justify-center space-y-3">
                <p class="text-2xl font-extrabold text-white">{{ auth()->user()->name }}</p>
                <span class="text-1x1 font-extrabold text-gray-400"> Account number: {{ auth()->id() }}</span>
                <p class="text-1x1 text-gray-500">{{ auth()->user()->email }}</p>
            </div>

            {{-- Saldo --}}
            <div class="flex-1 bg-gray-800 p-6 rounded-lg shadow-xl flex flex-col items-center justify-center space-y-3">
                <p class="text-base text-gray-400">Available Balance:</p>
                <span class="text-3xl font-bold text-green-400">R$ {{ number_format(optional(auth()->user()->wallet)->balance ?? 0, 2, ',', '.') }}</span>
            </div>

            {{-- Botões para Ações --}}
            <div class="flex-1 bg-gray-800 p-6 rounded-lg shadow-xl flex flex-col items-center justify-center">
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button id="depositButton" class="flex-1 bg-green-600 text-white px-4 py-2 rounded text-center">Deposit</button>
                    <button id="transferButton" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-center">Transfer</button>
                </div>
            </div>
        </div>
    </x-slot>

    <div>
        {{-- Modal de Depósito --}}
        <div id="depositModal" class="fixed inset-0 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-gray-900 p-6 rounded shadow-lg w-full max-w-sm mx-4">
                <h3 class="text-lg text-white font-semibold mb-4">Deposit</h3>
                <form action="{{ route('wallet.deposit') }}" method="POST">
                    @csrf
                    <input type="number" step="0.01" name="amount" placeholder="Value"
                        class="border p-2 w-full rounded mb-2 bg-gray-800 text-white" required>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Deposit</button>
                </form>
                <button id="closeDepositModal" class="bg-red-500 text-white mt-4 w-full p-2 rounded">Close</button>
            </div>
        </div>

        {{-- Modal de Transferência --}}
        <div id="transferModal" class="fixed inset-0 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-gray-900 p-6 rounded shadow-lg w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold mb-4 text-white">Transfer to another user (bank account)</h3>
                <form action="{{ route('transaction.transfer') }}" method="POST">
                    @csrf

                    {{-- Campo para digitar o ID do usuário --}}
                    <input type="text" name="receiver_id" id="receiver_id" placeholder="Account number (ID)" class="border p-2 w-full rounded mb-2 bg-gray-800 text-white" required>

                    {{-- Campo para digitar o valor da transferência --}}
                    <input type="number" step="0.01" name="amount" placeholder="Value" class="border p-2 w-full rounded mb-2 bg-gray-800 text-white" required>

                    {{-- Campo para a descrição da transferência (opcional) --}}
                    <input type="text" name="description" placeholder="Description (optional)" class="border p-2 w-full rounded mb-2 bg-gray-800 text-white">

                    {{-- Exibe o nome do usuário ou mensagem de erro --}}
                    <div id="receiver-info" class="mt-2 text-white"></div>

                    {{-- Botão de submissão --}}
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full mt-4">Transfer</button>
                </form>
                <button id="closeTransferModal" class="bg-red-500 text-white mt-4 w-full p-2 rounded">Close</button>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <x-alert />

            {{-- Exibindo as Transações --}}
            <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-white">My Transactions</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 rounded shadow overflow-hidden">
                        <thead class="bg-gray-800 text-left">
                            <tr>
                                <th class="p-3 text-white font-semibold">ID</th>
                                <th class="p-3 text-white">Datetime</th>
                                <th class="p-3 text-white">Type</th>
                                <th class="p-3 text-white">From</th>
                                <th class="p-3 text-white">To</th>
                                <th class="p-3 text-white">Amount</th>
                                <th class="p-3 text-white">Status</th>
                                <th class="p-3 text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr class="border-b">
                                    <td class="p-3 text-white">{{ $tx->id }}</td>
                                    <td class="p-3 text-white">{{ $tx->created_at }}</td>
                                    <td class="p-3 capitalize text-white">{{ $tx->type }}</td>
                                    <td class="p-3 text-white">{{ $tx->sender?->name ?? 'System' }}</td>
                                    <td class="p-3 text-white">{{ $tx->receiver?->name }}</td>
                                    <td class="p-3 {{ $tx->type == 'transfer' ? 'text-red-500' : 'text-green-500' }}">
                                        R$ {{ number_format($tx->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-white">{{ $tx->status }}</td>
                                    <td class="p-3">
                                        @if($tx->status === 'completed' && !$tx->reversal)
                                            <form action="{{ route('transaction.reverse', $tx->id) }}" method="POST">
                                                @csrf
                                                <button class="bg-red-500 text-white px-3 py-1 text-sm rounded">Reverse</button>
                                            </form>
                                        @else
                                            @if($tx->status === 'reversed')
                                                <span class="text-red-600 text-sm">Reversed</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
