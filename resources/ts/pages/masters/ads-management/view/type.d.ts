export interface AdsModel{
    id?:any ,
    title?: string ,
    slug?:string ,
    status?: string ,
    view_order ?: number
}

export interface AdsParams{
    perPage?: number ,
    page?: number ,
    search ?: string,
    sortBy ?: string ,
    sortDirection ?: string
}