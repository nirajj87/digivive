export interface CityModel{
    id?:any ,
    state_id ?: any ,
    title?: string ,
    slug?:string ,
    state_name ?: string ,
    country ?: string ,
    status?: string ,
    latitude ?: string ,
    longitude ?: string
}

export interface CityParams{
    perPage?: number ,
    page?: number ,
    search ?: string ,
    sortBy ?: string ,
    sortDirection ?: string
}
