export interface AnalyticsModel{
    id?:any ,
    title?: string ,
    slug?:string ,
    tracking_id?:string ,
    additional_settings?:string ,
    status?: string ,
}

export interface AnalyticsParams{
    perPage?: number ,
    page?: number ,
    search ?: string ,
    sortBy ?: string ,
    sortDirection ?: string
}