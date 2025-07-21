interface IModal {
    elementId: string
    lockModal?: boolean
}

export const openModal = ({ elementId, lockModal }: IModal): void => {
    document.getElementById('modal')!.style.display = "flex";
    if (lockModal) {

    }
};

export const closeModal = (elementId: string): void => {
    document.getElementById('modal')!.style.display = "none";
};

declare global {
    interface Window {
        openModal: any;
        closeModal: any;
    }
}

window.openModal = openModal;
window.closeModal = closeModal;
