
import DataTable, { ConfigColumns } from "datatables.net-dt";

interface IDataTableServeSide {
    url: string
    elementId: string
    columns?: ConfigColumns[];
}

export function dataTableServeSide({ elementId, url, columns }: IDataTableServeSide) {
    const dataTableMovement = new DataTable(elementId, {
        processing: true,
        serverSide: true,
        scrollCollapse: true,
        ajax: url,
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
            },
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ entradas",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 entradas",
        },
        columns: columns,
        pageLength: 10,
    });
    return dataTableMovement;
}

//export const dataTableServeSide