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
                    <dt class="text-lg font-medium text-green-400 truncate">
                        Total de Despesas
                    </dt>
                    <dd class="mt-1 text-5xl font-semibold tracking-tight text-white text-glow">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </dd>
                </div>

                {{-- Card Quantidade de Registros --}}
                <div class="bg-gray-900 bg-opacity-50 border border-green-700 rounded-lg p-6 card-glow">
                    <dt class="text-lg font-medium text-green-400 truncate">
                        Quantidade de Registros
                    </dt>
                    <dd class="mt-1 text-5xl font-semibold tracking-tight text-white text-glow">
                        {{ $count }}
                    </dd>
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

        </div>
    </div>
</x-app-layout>