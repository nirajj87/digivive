export interface UserData {
    id?:any,
    first_name?: string,
    last_name?: string,
    email ?: string,
    role_id ?: string,
    manager ?: string
    department?: string,
    status?: string,
    client_id ?:string,
    phone_number ?: string,
    designation ?: string,
    client_id ?: string,
    profile_img ?: File | string | null 
}


export interface UserParams {
    perPage?: number,
    page?: number,
    search?: string,
    sortBy?: string,
    sortDirection?: string
}