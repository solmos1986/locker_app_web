import { dataTableServeSide } from "../../components/datatables";
import { closeModal, openModal } from "../../components/modal";
import { base_url } from "../../layout";
import axios from 'axios';
import { IResident, IResponse } from "../../libs/types";
import Swal from "sweetalert2";
import validator from "validator";

const inzializeformResident: IResident = {
    name: ""
}
const dataTableResident = dataTableServeSide({
    elementId: "#table-resident",
    url: `${base_url}/resident/data-table`,
    columns: [
        {
            data: "name",
            name: "name",
            width: "60%",
        },
        {
            data: "state",
            name: "state",
            width: "25%",
            render(data, type, row, meta) {
                if (data === 1) {
                    return `Activo`;
                } else {
                    return `Inactivo`;
                }
            }
        },
        {
            data: "user_id",
            name: "user_id",
            width: "15%",
            render(data, type, row, meta) {
                return `
                    <div class="flex gap-2">
                        <button onclick="editResident(${data})" class="inline-flex items-center gap-2 px-2 py-1 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                        </button>
                        <button onclick="deleteResident(${data})" class="inline-flex items-center gap-2 px-2 py-1 text-sm font-medium text-white transition rounded-lg bg-red-500 shadow-theme-xs hover:bg-red-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                `;
            },

        },
    ]
});

dataTableResident.on('draw', function () {
    console.log('draw')
});

export function createResident() {
    const title = document.getElementById('create_resident_title') as HTMLInputElement;
    title.textContent = "Nuevo residente";
    setFormResident(inzializeformResident)
    openModal({ elementId: 'create_resident', lockModal: true })
}

export async function storeResident() {
    const value = getFormResident()
    const isInvalid = isInvalidForm(value)
    if (isInvalid) {
        return
    }
    try {
        const { data } = await axios.post<IResponse<IResident>>(`${base_url}/resident`, value)
        Swal.fire({
            title: data.meta.message,
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
            target: document.getElementById("create_resident"),
        });
        //closeModal('create_resident')
        dataTableResident.draw()
    } catch (error) {
        Swal.fire({
            title: "ocurrio un errror",
            icon: "error",
            showConfirmButton: false,
            timer: 2000
        });
    }
}

export async function editResident(movement_id: number) {
    const title = document.getElementById('create_resident_title') as HTMLInputElement;
    title.textContent = "Editar residente";
    try {
        const { data } = await axios.get<IResponse<IResident>>(`${base_url}/resident/${movement_id}`)
        setFormResident(data.data)
        openModal({ elementId: 'create_resident', lockModal: true })
    } catch (error) {
        Swal.fire({
            title: "ocurrio un errror",
            icon: "error",
            showConfirmButton: false,
            timer: 2000
        });
    }
}

export function updateResident() {
    const value = getFormResident()
    const isInvalid = isInvalidForm(value)
    if (isInvalid) {
        return
    }
    openModal({ elementId: 'create_resident', lockModal: true })
}

export function deleteResident(movement_id: number) {
    openModal({ elementId: 'delete_resident', lockModal: true })
}

export function destroyResident() {
    openModal({ elementId: 'create_resident', lockModal: true })
}

export function setFormResident({ name }: IResident) {
    const inputElement = document.getElementById('name') as HTMLInputElement;
    inputElement.value = name;
}

export function getFormResident(): IResident {
    const nameImput = document.getElementById('name') as HTMLInputElement;
    const form: IResident = {
        name: nameImput.value
    }
    return form
}

export function isInvalidForm({ name }: IResident): boolean {
    const errors: string[] = []
    if (validator.isEmpty(name)) {
        errors.push(`Nombre es requerido </br>`)
    }
    Swal.fire({
        title: "ocurrio un errror",
        icon: "error",
        showConfirmButton: false,
        timer: 5000,
        html: errors.toString(),
        target: document.getElementById("create_resident"),
    });
    if (errors.length > 0) {
        return true
    } else {
        return false
    }
}

declare global {
    interface Window {
        editResident: any;
        deleteResident: any;
        createResident: any;
        storeResident: any;
        updateResident: any;
        destroyResident: any;
    }
}
window.createResident = createResident
window.editResident = editResident;
window.deleteResident = deleteResident;
window.storeResident = storeResident;
window.updateResident = updateResident;
window.destroyResident = destroyResident
