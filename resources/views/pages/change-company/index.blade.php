<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/css/tailwind-admin.css')
</head>

<body x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{ 'dark bg-gray-900': darkMode === true }">

    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>
    <div class="flex h-screen overflow-hidden">
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
                class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
            @component('layout.parts.header')
            @endcomponent
            <main>
                <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
                    <div
                        class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row">
                        <div class="flex flex-col flex-1 w-full lg:w-1/2">
                            <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
                                <form method="POST" action="{{ route('change-company.change') }}">
                                    {!! csrf_field() !!}
                                    <div class="mb-5 sm:mb-8">
                                        <div
                                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                                    Cambio de empresa
                                                </h3>
                                            </div>
                                            <div
                                                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                                                <label
                                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    Seleciona una empresa
                                                </label>
                                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                                    <select
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                        @change="isOptionSelected = true" 
                                                        name="client_id"
                                                        >
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->client_id }}"
                                                                class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                                {{ $company->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span
                                                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                                        <svg class="stroke-current" width="20" height="20"
                                                            viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                                stroke="" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>

                                                <button
                                                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                                                    type="submit">
                                                    cambiar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
