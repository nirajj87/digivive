
export interface ModuleModel {
    id?:number;
    title?:string;
    slug?:string;
    status?:any;
    role?:Array;
    order?:any;
    icon?:any;
    parent_id?:any;
    child_id?:any;
    created_by?:any;
    is_parent?:any;
    updated_at?:string;
}


export interface RoleModel {
    id?:any;
    title?:string;
    slug?:string;
    created_by?:any;
    is_parent?:any;
    updated_at?:string;
}


export interface ParentModel {
    id?:number;
    title?:string;
    slug?:string;
    order?:number;
    status?:string;
    created_by?:any;
    parent_id?:number;
    role?:RoleParams;
    updated_at?:string;
    created_at?:string;
    value ?: any
}

export interface ModuleParams{
    perPage?: number ,
    page?: number ,
    search ?: string,
    sortBy ?: string ,
    sortDirection ?: string
}
