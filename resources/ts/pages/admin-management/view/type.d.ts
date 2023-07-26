export interface userParams {
    id?:number;
    first_name?:string;
    last_name?:string;
    email?:string;
    phone_number?:string;
    role_id?:any;
    manager?:any;
    country_id?:any;
    state_id?:any;
    status?:any;
    city_id?:any;
    timezone?:any;
    timezone_id?:any;
    date_format_id?:any;
    date_format?:any;
    department?:any;
    created_by?:any;
    is_parent?:any;
    permission?:permissionParams;
    designation ?: string ;
    profile_image ?:  string | File | null;
}
export interface permissionParams {
    id?:number;
    title?:string;
    slug?:string;
    status?:any;
    role?:any;
    order?:any;
    parent_id?:any;
    created_by?:any;
    is_parent?:any;
    updated_at?:string;
    privileges?:any;
    sub_modules?:SubModulesParams;
}

export interface privilegesParams {
    parent?:ParentParams;
}


export interface ParentParams {
    is_add?:number;
    is_edit?:number;
    is_delete?:number;
    is_view?:number;
}

export interface SubModulesParams {
    id?:number;
    title?:string;
    slug?:string;
    order?:number;
    status?:string;
    created_by?:any;
    parent_id?:number;
    updated_at?:string;
    created_at?:string;
    privileges?:privilegesParams;
}