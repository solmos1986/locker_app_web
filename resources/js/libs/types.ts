export interface IResponse<T> {
    data: T
    meta: {
        code: number
        message: string
        status: string
    }
}
/**RESIDENTE */

export interface IResident {
    name: string
}