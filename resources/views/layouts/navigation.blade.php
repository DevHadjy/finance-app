<nav x-data="{ open: false }" class="bg-black border-b border-green-800 border-opacity-25">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-green-400 text-2xl font-bold text-glow flex items-center tracking-widest">
                        <span class="mr-2 text-3xl">🤖</span> Finanças & AI
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-lg leading-4 font-medium rounded-md text-gray-400 bg-black hover:text-green-400 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')"> {{ __('Dashboard') }} </x-dropdown-link>
                        <x-dropdown-link :href="route('despesas.index')"> {{ __('Minhas Despesas') }} </x-dropdown-link>
                        <x-dropdown-link :href="route('despesas.importForm')"> {{ __('Importar CSV') }} </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')"> {{ __('Perfil') }} </x-dropdown-link>
                        <div class="border-t border-gray-700"></div>
                        <form method="POST" action="{{ route('logout') }}"> @csrf <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"> {{ __('Sair') }} </x-dropdown-link> </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <div class="-me-2 flex items-center sm:hidden"></div>
        </div>
    </div>
</nav>