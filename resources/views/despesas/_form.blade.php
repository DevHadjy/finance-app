{{-- Exibe um bloco com todos os erros de validação no topo do formulário --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded" role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">Houve alguns problemas com os dados informados.</span>
        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- O action e method do form são dinâmicos para servir tanto para criar quanto para editar --}}
<form method="POST" action="{{ isset($despesa) ? route('despesas.update', $despesa->id) : route('despesas.store') }}">
    @csrf
    {{-- O método HTTP PUT/PATCH é necessário para a atualização --}}
    @if(isset($despesa))
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="nome_despesa" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome da despesa</label>
        <input type="text" name="nome_despesa" id="nome_despesa"
                class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                value="{{ old('nome_despesa', $despesa->nome_despesa ?? '') }}" required autofocus>
        @error('nome_despesa')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="valor_despesa" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Valor (R$)</label>
        <input type="number" step="0.01" name="valor_despesa" id="valor_despesa"
                class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                value="{{ old('valor_despesa', $despesa->valor_despesa ?? '') }}" required>
            @error('valor_despesa')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="data_despesa" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data</label>
        <input type="date" name="data_despesa" id="data_despesa"
                class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                value="{{ old('data_despesa', isset($despesa) ? \Carbon\Carbon::parse($despesa->data_despesa)->format('Y-m-d') : date('Y-m-d')) }}" required>
            @error('data_despesa')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6">
        <a href="{{ route('despesas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Cancelar
        </a>
        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ isset($despesa) ? 'Atualizar' : 'Salvar' }}
        </button>
    </div>
</form>