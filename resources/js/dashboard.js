// Controle de modais
document.addEventListener('DOMContentLoaded', () => {
    const depositBtn = document.getElementById('depositButton');
    const transferBtn = document.getElementById('transferButton');
    const closeDeposit = document.getElementById('closeDepositModal');
    const closeTransfer = document.getElementById('closeTransferModal');
    const depositModal = document.getElementById('depositModal');
    const transferModal = document.getElementById('transferModal');

    if (depositBtn) depositBtn.addEventListener('click', () => depositModal?.classList.remove('hidden'));
    if (transferBtn) transferBtn.addEventListener('click', () => transferModal?.classList.remove('hidden'));
    if (closeDeposit) closeDeposit.addEventListener('click', () => depositModal?.classList.add('hidden'));
    if (closeTransfer) closeTransfer.addEventListener('click', () => transferModal?.classList.add('hidden'));

    // Validação de usuário na transferência
    const receiverIdInput = document.getElementById('receiver_id');
    const receiverInfo = document.getElementById('receiver-info');

    if (receiverIdInput) {
        receiverIdInput.addEventListener('input', function () {
            const receiverId = this.value;
            if (receiverId.length > 0) {
                console.log(receiverId);
                fetch(`/check-user/${receiverId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        receiverInfo.textContent = data.name
                        ? 'Account: ' + data.name
                        : 'Account number does not exist.';
                        receiverInfo.classList.toggle('text-red-500', !data.exists);
                    })
                    .catch(console.error);
            } else {
                receiverInfo.textContent = '';
            }
        });
    }

    // Alertas
    window.closeAlert = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('opacity-0');
        setTimeout(() => el.remove(), 300);
    };
});
