interface IModal {
    elementId: string
    lockModal?: boolean
}

export const openModal = ({ elementId, lockModal }: IModal): void => {
    document.getElementById(elementId)!.style.display = "flex";
    if (lockModal) {

    }
};

export const closeModal = (elementId: string): void => {
    console.log('closeModal', elementId)
    document.getElementById(elementId)!.style.display = "none";
};

declare global {
    interface Window {
        openModal: any;
        closeModal: any;
    }
}

window.openModal = openModal;
window.closeModal = closeModal;
