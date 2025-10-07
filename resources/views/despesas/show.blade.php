
<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Nome:</strong> {{ $despesa->nome_despesa }}</p>
                    <p><strong>Valor:</strong> R$ {{ number_format($despesa->valor_despesa,2,',','.') }}</p>
                    <p><strong>Data:</strong> {{ $despesa->data_despesa }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>