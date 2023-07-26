export interface StateModel{
    id?:any ,
    title?: string ,
    country_id ?: string ,
    state_id ?: string ,
    state_code ?: string ,
    country_name ?: string ,
    latitude ?: string ,
    longitude ?: string ,
    slug?:string ,
    status?: string ,
}

export interface StateParams{
    perPage?: number ,
    page?: number ,
    search ?: string ,
    sortBy ?: string ,
    sortDirection ?: string
}

export interface StateByCountry{
    country_id ?: string
}

export interface CountryList {
    value ?: any ,
    title ?: string
}