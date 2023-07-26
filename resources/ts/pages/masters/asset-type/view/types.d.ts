export interface AssetList {
    id?: number;
    title?: string;
    slug?: string;
    status?: any;
    perPage?: number,
    page?: number,
    search?:string
    sortBy?:string,
    sortDirection?:string
}
