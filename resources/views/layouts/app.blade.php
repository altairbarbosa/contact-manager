<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light"> <!-- Define 'light' como padrão, o JS vai ajustar -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts e Estilos compilados pelo Vite (ESSENCIAL para o Dark Mode e Responsividade do Breeze) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Assegura que o fundo principal adere ao tema */
        html.light body {
            background-color: #f3f4f6; /* cor de fundo para tema claro */
            color: #1f2937; /* cor de texto para tema claro */
        }
        html.dark body {
            background-color: #1a202c; /* cor de fundo para tema escuro */
            color: #e2e8f0; /* cor de texto para tema escuro */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Adicionando x-data para Alpine.js no elemento pai para controlar o menu responsivo -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900" x-data="{ open: false }">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">ContactApp</span>
                            </a>
                        </div>

                        <!-- Navigation Links (Desktop) -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.index')">
                                {{ __('Contacts') }}
                            </x-nav-link>
                            {{-- REMOVIDO: Link "Add New Contact" para Desktop --}}
                        </div>
                    </div>

                    <!-- Settings Dropdown (Desktop) e Botão de Tema Desktop -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Botão de alternância de tema para Desktop (SEMPRE VISÍVEL) -->
                        <button id="theme-toggle-desktop" class="mr-4 p-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                {{-- Ícone do Sol (Light Mode) --}}
                                <path id="sun-icon" fill="currentColor" d="M12 3v2.25m6.364.364l-1.591 1.591M21 12h-2.25m-.364 6.364l-1.591-1.591M12 18.75V21m-6.364-.364l1.591-1.591M3 12H5.25m.364-6.364L7.207 7.207M6 12a6 6 0 1112 0 6 6 0 01-12 0z" />
                                {{-- Ícone da Lua (Dark Mode) --}}
                                <path id="moon-icon" class="hidden" fill="currentColor" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                        </button>
                        <!-- Fim do botão de alternância de tema para Desktop -->

                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else {{-- Conteúdo para usuários não autenticados (desktop) --}}
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                            @endif
                        @endauth
                    </div>

                    <!-- Botão de alternância de tema para Mobile e Hamburger -->
                    <div class="flex items-center sm:hidden">
                        <!-- Botão de alternância de tema para Mobile (visível apenas em mobile) -->
                        <button id="theme-toggle-mobile" class="mr-2 p-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                {{-- Ícone do Sol (Light Mode) --}}
                                <path id="sun-icon-mobile" fill="currentColor" d="M12 3v2.25m6.364.364l-1.591 1.591M21 12h-2.25m-.364 6.364l-1.591-1.591M12 18.75V21m-6.364-.364l1.591-1.591M3 12H5.25m.364-6.364L7.207 7.207M6 12a6 6 0 1112 0 6 6 0 01-12 0z" />
                                {{-- Ícone da Lua (Dark Mode) --}}
                                <path id="moon-icon-mobile" class="hidden" fill="currentColor" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                        </button>
                        <!-- Fim do botão de alternância de tema para Mobile -->

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center"> {{-- Removido sm:hidden daqui, pois o botão de tema já lida com isso --}}
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.index')">
                        {{ __('Contacts') }}
                    </x-responsive-nav-link>
                    {{-- REMOVIDO: Link "Add New Contact" para Mobile --}}
                </div>

                <!-- Responsive Settings Options -->
                @auth
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
                @endauth
                @guest {{-- Adicionado para usuários não autenticados (mobile responsive menu) --}}
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
                @endguest
            </div>
        </nav>

        <!-- Page Heading (Mantido para compatibilidade, mas as views de contato não o usam mais) -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-700 dark:text-green-100 dark:border-green-800" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-700 dark:text-red-100 dark:border-red-800" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Script para alternar tema -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const htmlElement = document.documentElement;
            const themeToggleDesktop = document.getElementById('theme-toggle-desktop');
            const themeToggleMobile = document.getElementById('theme-toggle-mobile');
            const moonIconDesktop = document.getElementById('moon-icon');
            const sunIconDesktop = document.getElementById('sun-icon');
            const moonIconMobile = document.getElementById('moon-icon-mobile');
            const sunIconMobile = document.getElementById('sun-icon-mobile');


            // Função para aplicar o tema e atualizar os ícones
            const applyTheme = (theme) => {
                if (theme === 'dark') {
                    htmlElement.classList.add('dark');
                    htmlElement.classList.remove('light'); // Garante que a classe 'light' é removida
                    localStorage.setItem('theme', 'dark');
                    // Atualiza ícones do desktop
                    if (moonIconDesktop && sunIconDesktop) {
                        moonIconDesktop.classList.remove('hidden'); // Lua visível
                        sunIconDesktop.classList.add('hidden');    // Sol escondido
                    }
                    // Atualiza ícones do mobile
                    if (moonIconMobile && sunIconMobile) {
                        moonIconMobile.classList.remove('hidden');
                        sunIconMobile.classList.add('hidden');
                    }
                } else { // theme === 'light'
                    htmlElement.classList.remove('dark');
                    htmlElement.classList.add('light'); // Garante que a classe 'light' é adicionada
                    localStorage.setItem('theme', 'light');
                    // Atualiza ícones do desktop
                    if (moonIconDesktop && sunIconDesktop) {
                        moonIconDesktop.classList.add('hidden');    // Lua escondida
                        sunIconDesktop.classList.remove('hidden'); // Sol visível
                    }
                    // Atualiza ícones do mobile
                    if (moonIconMobile && sunIconMobile) {
                        moonIconMobile.classList.add('hidden');
                        sunIconMobile.classList.remove('hidden');
                    }
                }
            };

            // Inicializa o tema com base na preferência salva ou do sistema
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme) {
                applyTheme(savedTheme);
            } else if (prefersDark) {
                applyTheme('dark');
            } else {
                applyTheme('light');
            }

            // Adiciona event listeners aos botões de alternância
            const toggleThemeHandler = () => {
                const currentTheme = htmlElement.classList.contains('dark') ? 'dark' : 'light';
                applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
            };

            if (themeToggleDesktop) {
                themeToggleDesktop.addEventListener('click', toggleThemeHandler);
            }

            if (themeToggleMobile) {
                themeToggleMobile.addEventListener('click', toggleThemeHandler);
            }
        });
    </script>
</body>
</html>
