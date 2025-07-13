@extends('layout.main')
@push('css-header')
    @vite('resources/css/libs/datatable.css')
@endpush

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Basic Tables` }">
            <include src="./partials/breadcrumb.html" />
        </div>
        <!-- Breadcrumb End -->

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Movimientos
                    </h3>
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <!-- ====== Table Six Start -->

                    <table class="table-auto min-w-full" id="table-movemnt">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 sm:px-6">
                                    Recidente
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Departamento
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Fecha de creacion
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Estado
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Fecha de Entrega
                                </th>
                            </tr>
                        </thead>
                        {{--  <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            User
                                        </p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Project Name
                                        </p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Team
                                        </p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Status
                                        </p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Budget
                                        </p>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 overflow-hidden rounded-full">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>

                                            <div>
                                                <span
                                                    class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                    Lindsey Curtis
                                                </span>
                                                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Web Designer aaaa
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            Agency Website
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p
                                            class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                            Active
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">3.9K</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 overflow-hidden rounded-full">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>

                                            <div>
                                                <span
                                                    class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                    Lindsey Curtis
                                                </span>
                                                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Web Designer
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            Agency Website
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p
                                            class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                            Active
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">3.9K</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 overflow-hidden rounded-full">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>

                                            <div>
                                                <span
                                                    class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                    Lindsey Curtis
                                                </span>
                                                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Web Designer
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            Agency Website
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                            <div
                                                class="w-6 h-6 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                                <img src="{{ asset('/images/user.jpg') }}" alt="User"
                                                    class="overflow-hidden rounded-full" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p
                                            class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                            Active
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">3.9K</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
 --}}
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('java-script')
        <script src="{{ asset('js/jquery.js') }}"></script>
        @vite('resources/js/pages/movement.js')
    @endpush
@endsection
