export interface CountryModel{
    id?:any ,
    title?: string ,
    iso2 ?: string ,
    iso3 ?: string ,
    country_code ?: string ,
    phone_code ?: string ,
    capital ?: string ,
    currency ?: string ,
    currency_name ?: string ,
    currency_symbol ?: string ,
    timezones ?: Array ,
    latitude?: string ,
    longitude?: string ,
    status?: string ,
    emoji?: string ,
    image?: string ,
    slug ?: string
}

export interface CountryParams{
    perPage?: number ,
    page?: number ,
    search ?: string ,
    sortBy ?: string ,
    sortDirection ?: string
}

export interface TimeZone{
    zoneName ?: string ,
    gmtOffset ?: string ,
    gmtOffsetName ?: string ,
    abbreviation ?: string ,
    tzName ?: string ,
}