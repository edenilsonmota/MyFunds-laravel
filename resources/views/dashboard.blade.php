<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            Bem-vindo, {{ auth()->user()->name }} (Conta nº {{ auth()->id() }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <x-alert />

            {{-- Saldo --}}
            <div class="mb-6 bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Saldo atual</h3>
                <p class="text-2xl mt-2">
                    R$ {{ number_format(optional(auth()->user()->wallet)->balance ?? 0, 2, ',', '.') }}
                </p>
            </div>

            {{-- Formulário de Depósito --}}
            <div class="bg-white p-4 rounded shadow mb-6">
                <h3 class="text-lg font-semibold mb-2">Depósito</h3>
                <form action="{{ route('wallet.deposit') }}" method="POST">
                    @csrf
                    <input type="number" step="0.01" name="amount" placeholder="Valor"
                        class="border p-2 w-full rounded mb-2" required>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Depositar</button>
                </form>
            </div>

            {{-- Formulário de Transferência --}}
            <div class="bg-white p-4 rounded shadow mb-6">
                <h3 class="text-lg font-semibold mb-2">Transferir para outro usuário</h3>
                <form action="{{ route('transaction.transfer') }}" method="POST">
                    @csrf

                    {{-- Campo para digitar o ID do usuário --}}
                    <label for="receiver_id">Número do usuário (ID):</label>
                    <input type="text" name="receiver_id" id="receiver_id" class="border p-2 w-full rounded mb-2" required>

                    {{-- Campo para digitar o valor da transferência --}}
                    <input type="number" step="0.01" name="amount" placeholder="Valor" class="border p-2 w-full rounded mb-2" required>

                    {{-- Campo para a descrição da transferência (opcional) --}}
                    <input type="text" name="description" placeholder="Descrição (opcional)" class="border p-2 w-full rounded mb-2">

                    {{-- Exibe o nome do usuário ou mensagem de erro --}}
                    <div id="receiver-info" class="mt-2 text-gray-600"></div>

                    {{-- Botão de submissão --}}
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded mt-4">Transferir</button>
                </form>
            </div>


            <div class="text-right">
                <a href="{{ route('transactions.index') }}" class="text-blue-500 underline">Ver transações</a>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- Script JS para validação do ID e exibição do nome --}}
@push('scripts')
<script>
    document.getElementById('receiver_id').addEventListener('input', function() {
        var receiverId = this.value;
        var receiverInfo = document.getElementById('receiver-info');

        if (receiverId.length > 0) {
            // Requisição para verificar o usuário
            fetch('/check-user/' + receiverId)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        receiverInfo.textContent = 'Usuário: ' + data.name;
                    } else {
                        receiverInfo.textContent = 'Número de usuário não existe.';
                        receiverInfo.classList.add('text-red-500');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
        } else {
            receiverInfo.textContent = '';
        }
    });
</script>
@endpush
