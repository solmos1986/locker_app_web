@extends('layout.main')
@push('css-header')
    @vite('resources/css/libs/datatable.css')
@endpush

@section('content')
    <style>
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.child,
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dataTables_empty {
            cursor: default !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dataTables_empty:before {
            display: none !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control {
            cursor: pointer;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            margin-right: 0.5em;
            display: inline-block;
            box-sizing: border-box;
            content: "";
            border-top: 5px solid transparent;
            border-left: 10px solid rgba(0, 0, 0, 0.5);
            border-bottom: 5px solid transparent;
            border-right: 0px solid transparent;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control.arrow-right::before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control.arrow-right::before {
            border-top: 5px solid transparent;
            border-left: 0px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 10px solid rgba(0, 0, 0, 0.5);
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>th.dtr-control:before {
            border-top: 10px solid rgba(0, 0, 0, 0.5);
            border-left: 5px solid transparent;
            border-bottom: 0px solid transparent;
            border-right: 5px solid transparent;
        }

        table.dataTable.dtr-inline.collapsed.compact>tbody>tr>td.dtr-control,
        table.dataTable.dtr-inline.collapsed.compact>tbody>tr>th.dtr-control {
            padding-left: 0.333em;
        }

        table.dataTable.dtr-column>tbody>tr>td.dtr-control,
        table.dataTable.dtr-column>tbody>tr>th.dtr-control,
        table.dataTable.dtr-column>tbody>tr>td.control,
        table.dataTable.dtr-column>tbody>tr>th.control {
            cursor: pointer;
        }

        table.dataTable.dtr-column>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-column>tbody>tr>th.dtr-control:before,
        table.dataTable.dtr-column>tbody>tr>td.control:before,
        table.dataTable.dtr-column>tbody>tr>th.control:before {
            display: inline-block;
            box-sizing: border-box;
            content: "";
            border-top: 5px solid transparent;
            border-left: 10px solid rgba(0, 0, 0, 0.5);
            border-bottom: 5px solid transparent;
            border-right: 0px solid transparent;
        }

        table.dataTable.dtr-column>tbody>tr>td.dtr-control.arrow-right::before,
        table.dataTable.dtr-column>tbody>tr>th.dtr-control.arrow-right::before,
        table.dataTable.dtr-column>tbody>tr>td.control.arrow-right::before,
        table.dataTable.dtr-column>tbody>tr>th.control.arrow-right::before {
            border-top: 5px solid transparent;
            border-left: 0px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 10px solid rgba(0, 0, 0, 0.5);
        }

        table.dataTable.dtr-column>tbody>tr.dtr-expanded td.dtr-control:before,
        table.dataTable.dtr-column>tbody>tr.dtr-expanded th.dtr-control:before,
        table.dataTable.dtr-column>tbody>tr.dtr-expanded td.control:before,
        table.dataTable.dtr-column>tbody>tr.dtr-expanded th.control:before {
            border-top: 10px solid rgba(0, 0, 0, 0.5);
            border-left: 5px solid transparent;
            border-bottom: 0px solid transparent;
            border-right: 5px solid transparent;
        }

        table.dataTable>tbody>tr.child {
            padding: 0.5em 1em;
        }

        table.dataTable>tbody>tr.child:hover {
            background: transparent !important;
        }

        table.dataTable>tbody>tr.child ul.dtr-details {
            display: inline-block;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li {
            border-bottom: 1px solid #efefef;
            padding: 0.5em 0;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li:first-child {
            padding-top: 0;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        table.dataTable>tbody>tr.child span.dtr-title {
            display: inline-block;
            min-width: 75px;
            font-weight: bold;
        }

        div.dtr-modal {
            position: fixed;
            box-sizing: border-box;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 100;
            padding: 10em 1em;
        }

        div.dtr-modal div.dtr-modal-display {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 50%;
            height: fit-content;
            max-height: 75%;
            overflow: auto;
            margin: auto;
            z-index: 102;
            overflow: auto;
            background-color: #f5f5f7;
            border: 1px solid black;
            border-radius: 0.5em;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
        }

        div.dtr-modal div.dtr-modal-content {
            position: relative;
            padding: 2.5em;
        }

        div.dtr-modal div.dtr-modal-content h2 {
            margin-top: 0;
        }

        div.dtr-modal div.dtr-modal-close {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 22px;
            height: 22px;
            text-align: center;
            border-radius: 3px;
            cursor: pointer;
            z-index: 12;
        }

        div.dtr-modal div.dtr-modal-background {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 101;
            background: rgba(0, 0, 0, 0.6);
        }

        @media screen and (max-width: 767px) {
            div.dtr-modal div.dtr-modal-display {
                width: 95%;
            }
        }

        html.dark table.dataTable>tbody>tr>td.dtr-control:before,
        html[data-bs-theme=dark] table.dataTable>tbody>tr>td.dtr-control:before {
            border-left-color: rgba(255, 255, 255, 0.5) !important;
        }

        html.dark table.dataTable>tbody>tr>td.dtr-control.arrow-right::before,
        html[data-bs-theme=dark] table.dataTable>tbody>tr>td.dtr-control.arrow-right::before {
            border-right-color: rgba(255, 255, 255, 0.5) !important;
        }

        html.dark table.dataTable>tbody>tr.dtr-expanded>td.dtr-control:before,
        html.dark table.dataTable>tbody>tr.dtr-expanded>th.dtr-control:before,
        html[data-bs-theme=dark] table.dataTable>tbody>tr.dtr-expanded>td.dtr-control:before,
        html[data-bs-theme=dark] table.dataTable>tbody>tr.dtr-expanded>th.dtr-control:before {
            border-top-color: rgba(255, 255, 255, 0.5) !important;
            border-left-color: transparent !important;
            border-right-color: transparent !important;
        }

        html.dark table.dataTable>tbody>tr.child ul.dtr-details>li,
        html[data-bs-theme=dark] table.dataTable>tbody>tr.child ul.dtr-details>li {
            border-bottom-color: rgb(64, 67, 70);
        }

        html.dark div.dtr-modal div.dtr-modal-display,
        html[data-bs-theme=dark] div.dtr-modal div.dtr-modal-display {
            background-color: rgb(33, 37, 41);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
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
                        Movimientos
                    </h3>
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <!-- ====== Table Six Start -->
                    <table class="table display responsive nowrap" style="width:100%" id="table-movemnt">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-5 py-3 sm:px-6">
                                    Recidente
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Departamento
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Code
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Fecha de creacion
                                </th>
                                <th class="px-5 py-3 sm:px-6">
                                    Fecha de Modificacion
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
    <x-modal name="edit_resident">
        <form id="formEditMovement" onsubmit="">
            <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">
                <div class="modal-header">
                    <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl dark:text-white/90 lg:text-2xl"
                        id="create_resident_title">
                        Editar movimiento
                    </h5>
                </div>
                <div class="mt-8 modal-body">
                    <div>
                        <div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Event Title
                                </label>
                                <input id="event-title" type="text"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            </div>
                        </div>
                        <div class="mt-6">
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
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <button type="button" onclick="closeModal('edit_resident')"
                        class="btn modal-close-btn bg-danger-subtle text-danger flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Cerrar
                    </button>
                    <button type="button" onclick="onUpdateMovement()"
                        class="btn btn-primary btn-add-event flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    @push('java-script')
        @vite('resources/js/pages/movements/index.ts')
    @endpush
@endsection
