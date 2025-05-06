<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Minhas Transações</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto">
            <x-alert />

            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3">De</th>
                        <th class="p-3">Para</th>
                        <th class="p-3">Valor</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                        <tr class="border-b">
                            <td class="p-3">{{ $tx->id }}</td>
                            <td class="p-3 capitalize">{{ $tx->type }}</td>
                            <td class="p-3">{{ $tx->sender?->name ?? 'Sistema' }}</td>
                            <td class="p-3">{{ $tx->receiver?->name }}</td>
                            <td class="p-3">R$ {{ number_format($tx->amount, 2, ',', '.') }}</td>
                            <td class="p-3">{{ $tx->status }}</td>
                            <td class="p-3">
                                @if($tx->status === 'completed' && !$tx->reversal)
                                    <form action="{{ route('transaction.reverse', $tx->id) }}" method="POST">
                                        @csrf
                                        <input type="text" name="reason" placeholder="Motivo (opcional)"
                                            class="border p-1 rounded w-full mb-1 text-sm">
                                        <button class="bg-red-500 text-white px-3 py-1 text-sm rounded">Reverter</button>
                                    </form>
                                @else
                                    @if($tx->status === 'reversed')
                                        <span class="text-red-600 text-sm">Revertida</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
