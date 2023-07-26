export interface PlatformModel {
    id ?: any;
    title ?: string;
    slug ?: string;
    status ?: string ;
}

export interface PlatformParams {
    per_page?: number ,
    page?: number ,
    search ?: string,
    sortBy ?: string ,
    sortDirection ?: string
}