export interface BitframeList {
    id?: number;
    type?: string;
    is_downloadable?: string,
    download_type?: string,
    presets?: string,
    status?: any;
    perPage?: number,
    page?: number,
    search?:string,
    sortBy?:string,
    sortDirection?:string
}
