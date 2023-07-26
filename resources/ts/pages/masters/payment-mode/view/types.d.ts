export interface PaymentList {
    id?: number;
    title?: string;
    slug?: string,
    type?: string,
    image?: string,
    is_recurring?: any,
    additional_settings?: string,
    status?: any;
    perPage?: number,
    page?: number,
    search?:string,
    sortBy?:string,
    sortDirection?:string
}
