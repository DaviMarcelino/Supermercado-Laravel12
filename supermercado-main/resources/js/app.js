import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    function mostrarSpinner() {
        const spinner = document.getElementById('spinner-global');
        if (spinner) {
            spinner.classList.remove('hidden');
        }
    }

    function ocultarSpinner() {
        const spinner = document.getElementById('spinner-global');
        if (spinner) {
            spinner.classList.add('hidden');
        }
    }

    window.adicionarAoCarrinho = function(id) {
        mostrarSpinner();

        fetch(`/carrinho/add/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(res => {
            if (!res.ok) throw new Error(`Erro ${res.status}: ${res.statusText}`);
            return res.json();
        })
        .then(data => {
            ocultarSpinner();

            if (data.message === 'Adicionado com sucesso') {
                const contador = document.getElementById('contador-carrinho');
                if (contador) {
                    contador.textContent = data.total;
                }
                
                alert('Produto adicionado ao carrinho!');
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(err => {
            ocultarSpinner();
            console.error('Erro ao adicionar ao carrinho:', err);
            alert('Não foi possível adicionar ao carrinho: ' + err.message);
        });
    };

    window.abrirCarrinho = function () {
        const modal = document.getElementById('modal-carrinho');
        const conteudo = document.getElementById('conteudo-carrinho');
        const spinner = document.getElementById('loading-spinner');

        if (!modal || !conteudo || !spinner) {
            console.warn('⚠️ Elementos do carrinho não encontrados.');
            return;
        }

        spinner.classList.remove('hidden');
        conteudo.innerHTML = '';
        modal.classList.remove('hidden');

        fetch('/carrinho', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Resposta inválida do servidor');
            return res.text();
        })
        .then(html => {
            conteudo.innerHTML = html;
            spinner.classList.add('hidden');
        })
        .catch(err => {
            conteudo.innerHTML = '<p class="text-red-500 text-center">Erro ao carregar o carrinho</p>';
            spinner.classList.add('hidden');
            console.error('⛔ Erro ao carregar carrinho:', err);
        });
    };

    window.fecharCarrinho = function () {
        const modal = document.getElementById('modal-carrinho');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    window.alterarQuantidade = function (event, id, mudanca) {
        event.preventDefault();
        mostrarSpinner();

        fetch(`/carrinho/alterar/${id}/${mudanca}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Erro ao alterar quantidade');
            return res.json();
        })
        .then(() => {
            ocultarSpinner();
            abrirCarrinho();
        })
        .catch(err => {
            ocultarSpinner();
            console.error('Erro ao alterar quantidade:', err);
            alert('Não foi possível atualizar a quantidade');
        });
    };

    window.removerDoCarrinho = function (id) {
        if (!confirm('Tem certeza que deseja remover este produto do carrinho?')) {
            return;
        }

        mostrarSpinner();

        fetch(`/carrinho/remove/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            ocultarSpinner();
            abrirCarrinho();
            
            const contador = document.getElementById('contador-carrinho');
            if (contador) {
                contador.textContent = data.total || 0;
            }
        })
        .catch(err => {
            ocultarSpinner();
            console.error('Erro ao remover:', err);
            alert('Erro ao remover produto');
        });
    };

    window.confirmarEsvaziarCarrinho = function () {
        const modal = document.getElementById('modal-confirmacao');
        if (modal) {
            modal.classList.remove('hidden');
        }
    };

    window.fecharModalConfirmacao = function () {
        const modal = document.getElementById('modal-confirmacao');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    document.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'confirmar-esvaziar-btn') {
            mostrarSpinner();
            
            fetch('/carrinho/clear', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                ocultarSpinner();
                fecharModalConfirmacao();
                fecharCarrinho();
                
                const contador = document.getElementById('contador-carrinho');
                if (contador) contador.textContent = data.total || '0';
                
                location.reload();
            })
            .catch(err => {
                ocultarSpinner();
                console.error('Erro ao esvaziar:', err);
                alert('Não foi possível esvaziar o carrinho');
            });
        }
    });
});