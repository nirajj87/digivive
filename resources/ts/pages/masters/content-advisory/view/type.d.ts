export interface AdvisoryModel{
    id?:any ,
    title?: string ,
    slug?:string ,
    status?: string ,
    view_order ?: number
}

export interface AdvisoryParams{
    perPage?: number ,
    page?: number ,
    search ?: string,
    sortBy ?: string ,
    sortDirection ?: string
}