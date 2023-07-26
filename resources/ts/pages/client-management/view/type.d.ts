import { Integer } from "type-fest";

export interface clientParams {
    id?:number;
    company_name?:string;
    email?:string;
    country_id?:any;
    state_id?:any;
    status?:any;
    city_id?:any;
    timezone_id?:any;
    profile_img?:any;
    phone_number?:any;
    date_format?:any;
    created_by?:any;
    is_parent?:any;
    profile_picture?:any;
    permission?:permissionParams;
    timezone?:string;
    title?:string;
    format_key?:string
}

export interface requiredClientParams {
    company_name ?: string,
    first_name ?: string,
    last_name ?: string,
    email ?: string,
    country_id ?: number,
    timezone ?: string,
    date_format ?: string,
    profile_img ?: string | File | null ;
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