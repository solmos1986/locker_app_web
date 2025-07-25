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
                    <h3 id="modal-movement" class="text-base font-medium text-gray-800 dark:text-white/90">
                        Residentes
                    </h3>
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="flex flex-row-reverse">
                        <button onclick="createResident()"
                            class="inline-flex items-center gap-2 px-2 py-1 font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 ">
                            Nuevo recidente
                        </button>
                    </div>
                    <!-- ====== Table Six Start -->
                    <table class="table-auto min-w-full" id="table-resident">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 sm:px-6">
                                    Nombre
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Estado
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create_resident">
        <form id="formCreateResidente" onsubmit="">
            <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">
                <div class="modal-header">
                    <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl dark:text-white/90 lg:text-2xl"
                        id="create_resident_title">
                        Nuevo residente
                    </h5>
                    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">
                    Nombre de residente
                </p> --}}
                </div>
                <div class="mt-8 modal-body">
                    <div>
                        <div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nombre completo
                                </label>
                                <input id="name" type="text" name="name" id="name"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                <p class="text-theme-xs text-error-500" id="name_validate">

                                </p>
                            </div>
                        </div>
                        {{-- <div class="mt-6">
                        <div>
                            <label class="block mb-4 text-sm font-medium text-gray-700 dark:text-gray-400">
                                Event Color
                            </label>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                            <div class="n-chk">
                                <div class="form-check form-check-primary form-check-inline">
                                    <label
                                        class="flex items-center text-sm text-gray-700 form-check-label dark:text-gray-400"
                                        for="modalDanger">
                                        <span class="relative">
                                            <input class="sr-only form-check-input" type="radio" name="event-level"
                                                value="Danger" id="modalDanger" />
                                            <span
                                                class="flex items-center justify-center w-5 h-5 mr-2 border border-gray-300 rounded-full box dark:border-gray-700">
                                                <span class="w-2 h-2 bg-white rounded-full dark:bg-transparent">
                                                </span>
                                            </span>
                                        </span>
                                        Danger
                                    </label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-warning form-check-inline">
                                    <label
                                        class="flex items-center text-sm text-gray-700 form-check-label dark:text-gray-400"
                                        for="modalSuccess">
                                        <span class="relative">
                                            <input class="sr-only form-check-input" type="radio" name="event-level"
                                                value="Success" id="modalSuccess" />
                                            <span
                                                class="flex items-center justify-center w-5 h-5 mr-2 border border-gray-300 rounded-full box dark:border-gray-700">
                                                <span class="w-2 h-2 bg-white rounded-full dark:bg-transparent">
                                                </span>
                                            </span>
                                        </span>
                                        Success
                                    </label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-success form-check-inline">
                                    <label
                                        class="flex items-center text-sm text-gray-700 form-check-label dark:text-gray-400"
                                        for="modalPrimary">
                                        <span class="relative">
                                            <input class="sr-only form-check-input" type="radio" name="event-level"
                                                value="Primary" id="modalPrimary" />
                                            <span
                                                class="flex items-center justify-center w-5 h-5 mr-2 border border-gray-300 rounded-full box dark:border-gray-700">
                                                <span class="w-2 h-2 bg-white rounded-full dark:bg-transparent">
                                                </span>
                                            </span>
                                        </span>
                                        Primary
                                    </label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-danger form-check-inline">
                                    <label
                                        class="flex items-center text-sm text-gray-700 form-check-label dark:text-gray-400"
                                        for="modalWarning">
                                        <span class="relative">
                                            <input class="sr-only form-check-input" type="radio" name="event-level"
                                                value="Warning" id="modalWarning" />
                                            <span
                                                class="flex items-center justify-center w-5 h-5 mr-2 border border-gray-300 rounded-full box dark:border-gray-700">
                                                <span class="w-2 h-2 bg-white rounded-full dark:bg-transparent">
                                                </span>
                                            </span>
                                        </span>
                                        Warning
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Enter Start Date
                            </label>
                            <div class="relative">
                                <input id="event-start-date" type="date"
                                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                    onclick="this.showPicker()" />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2">
                                    <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14"
                                        viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                                            fill="" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Enter End Date
                            </label>
                            <div class="relative">
                                <input id="event-end-date" type="date"
                                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                    onclick="this.showPicker()" />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2">
                                    <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14"
                                        viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                                            fill="" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <button type="button" onclick="closeModal('create_resident')"
                        class="btn modal-close-btn bg-danger-subtle text-danger flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Cerrar
                    </button>
                    {{-- <button type="button"
                    class="btn btn-success btn-update-event flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto"
                    data-fc-event-public-id="">
                    Update changes
                </button> --}}
                    <button type="button" onclick="storeResident()"
                        class="btn btn-primary btn-add-event flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-modal name="delete_resident">
        <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">
            <div class="modal-header">
                <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl dark:text-white/90 lg:text-2xl"
                    id="eventModalLabel">
                    Eliminar registro
                </h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    ¿Esta seguro de eliminar este registro?
                </p>
            </div>
            <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                <button type="button" onclick="closeModal('delete_resident')"
                    class="btn modal-close-btn bg-danger-subtle text-danger flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    Cerrar
                </button>
                <button type="button"
                    class="btn btn-primary btn-add-event flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white bg-red-500 shadow-theme-xs hover:bg-red-600 sm:w-auto">
                    Eliminar
                </button>
            </div>
        </div>
    </x-modal>

    @push('java-script')
        <script>
            //window.onEdit = onEdit();
            /* document.querySelector('.btnModal').addEventListener('click', () => {
                console.log('foo');
            }); */
        </script>
        @vite('resources/js/pages/resident/index.ts')
    @endpush
@endsection
