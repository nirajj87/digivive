export interface ModulesParams {
    id?:number;
    title?:string;
    slug?:string;
    status?:any;
    role?:any;
    order?:any;
    icon?:any;
    parent_id?:any;
    child_id?:any;
    created_by?:any;
    is_parent?:any;
    updated_at?:string;
    role?:RoleParams
}

export interface RoleParams {
    id?:nubmer;
    title?:string;
    slug?:string;
    created_by?:any;
    is_parent?:any;
    updated_at?:string;
}


export interface ParentParams {
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
}