<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-300">
            
            <h3 class="text-4xl font-bold text-green-400 mb-8 text-glow text-center tracking-wider">
                Resumo de Despesas 📊
            </h3>

            {{-- Grade de Estatísticas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                {{-- Card Total de Despesas --}}
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg p-6 card-glow">
                    <span class="text-lg font-medium text-green-400 truncate">
                        Total de Despesas
                    </span>
                    <div class="mt-1 text-5xl font-semibold tracking-tight text-white text-glow">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </div>
                </div>

                {{-- Card Quantidade de Registros --}}
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg p-6 card-glow">
                    <span class="text-lg font-medium text-green-400 truncate">
                        Quantidade de Registros
                    </span>
                    <div class="mt-1 text-5xl font-semibold tracking-tight text-white text-glow">
                        {{ $count }}
                    </div>
                </div>
            </div>

            {{-- Lista de Despesas Recentes --}}
            <div class="mt-12">
                <h4 class="text-2xl font-medium text-green-400 mb-4 text-glow">
                    Últimas despesas adicionadas
                </h4>
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg overflow-hidden card-glow text-lg">
                    <dl class="divide-y divide-green-800 divide-opacity-50">
                        @forelse($recent as $despesa)
                            <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="font-medium text-gray-300">{{ $despesa->nome_despesa }}</dt>
                                <dd class="mt-1 text-green-400 sm:col-span-1 sm:mt-0">R$ {{ number_format($despesa->valor_despesa, 2, ',', '.') }}</dd>
                                <dd class="mt-1 text-gray-500 sm:col-span-1 sm:mt-0 sm:text-right">{{ \Carbon\Carbon::parse($despesa->data_despesa)->format('d/m/Y') }}</dd>
                            </div>
                        @empty
                            <div class="px-4 py-5 sm:px-6 text-center">
                                <p class="text-gray-500">Nenhuma despesa recente para mostrar.</p>
                            </div>
                        @endforelse
                    </dl>
                </div>
            </div>

            {{-- Gráfico de barras das últimas despesas --}}
            <div class="mt-12">
                <h4 class="text-2xl font-medium text-green-400 mb-4 text-glow">
                    Gráfico das últimas despesas
                </h4>
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg p-6 card-glow">
                    <canvas id="despesasChart"></canvas>
                </div>
            </div>

            {{-- Chat Financeiro com IA --}}
            <div class="mt-12">
                <h4 class="text-2xl font-medium text-green-400 mb-4 text-glow">
                    Chat Financeiro com IA 🤖
                </h4>
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg p-6 card-glow">
                    <div id="chat-messages" class="mb-4 h-64 overflow-y-auto bg-black bg-opacity-30 p-4 rounded"></div>
                    <form id="chat-form" class="flex">
                        <input type="text" id="chat-input" class="flex-1 px-4 py-2 rounded bg-gray-800 text-white" placeholder="Pergunte sobre suas despesas..." required>
                        <button type="submit" class="ml-2 px-4 py-2 bg-green-600 text-white rounded">Enviar</button>
                    </form>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            const ctx = document.getElementById('despesasChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($recent->pluck('nome_despesa')) !!},
                    datasets: [{
                        label: 'Valor (R$)',
                        data: {!! json_encode($recent->pluck('valor_despesa')) !!},
                        backgroundColor: 'rgba(34,197,94,0.7)',
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            document.getElementById('chat-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('chat-input');
                const message = input.value;
                input.value = '';
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML += `<div class="mb-2"><span class="font-bold text-green-400">Você:</span> ${message}</div>`;
                fetch('/gpt/chat', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ message })
                })
                .then(res => res.json())
                .then(data => {
                    chatMessages.innerHTML += `<div class="mb-2"><span class="font-bold text-blue-400">IA:</span> ${data.reply}</div>`;
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
            });
            </script>

        </div>
    </div>
</x-app-layout>