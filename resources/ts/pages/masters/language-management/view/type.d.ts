export interface LanguageModel{
    id?:any ,
    title?: string ,
    slug?:string ,
    iso_code2 ?: string ,
    iso_code3 ?: string ,
    country_code ?: string ,
    status?: string ,
}

export interface LanguageParams{
    perPage?: number ,
    page?: number ,
    search ?: string ,
    sortBy ?: string ,
    sortDirection ?: string
}