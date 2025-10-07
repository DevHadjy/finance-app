<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-md p-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('despesas.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="csv_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Arquivo CSV</label>
                            <input type="file" name="csv_file" id="csv_file" class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        </div>

                        <p class="mt-2 mb-4 text-sm text-gray-600 dark:text-gray-400">
                           Formato esperado no CSV: <strong>nome,valor,data</strong> (ex: "Mercado,120.50,2025-10-01")
                        </p>

                        <div class="flex items-center">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Importar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>