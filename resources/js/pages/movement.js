import DataTable from "datatables.net-dt";

const dataTableMovement = new DataTable("#table-movemnt", {
    processing: true,
    serverSide: true,
    scrollY: true,
    scrollX: true,
    scrollCollapse: true,
    ajax: `${base_url}/movement/data-table`,
    language: {
        searchPlaceholder: "...",
        search: "Buscar",
        emptyTable: "No hay resultados",
        loadingRecords: "Cargando",
        lengthMenu: "Mostrar _MENU_ entradas",
        paginate: {
            /*  first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior", */
            info: "Mostrando página _PAGE_ de _PAGES_",
        },
        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ entradas",
        infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 entradas",
    },
    columns: [
        {
            data: "recident",
            name: "recident",
        },
        {
            data: "department",
            name: "department",
        },
        {
            data: "create_at",
            name: "create_at",
        },
        {
            data: "delivered",
            name: "delivered",
        },
        {
            data: "create_at",
            name: "create_at",
        },
    ],
    pageLength: 100,
});
