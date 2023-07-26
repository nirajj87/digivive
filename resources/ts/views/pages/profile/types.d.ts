import { Integer } from "type-fest"

export interface UserParams {
  id: number,
  first_name?: string,
  last_name?: string,
  email?: string,
  company_name?: string,
  usercountry?: UserCountry | undefined,
  profile_img?: string,
  role_id?: number,
  designation?: string,
  timezone?: string,
  phone_number?: string,
  date_format?: string,
  dob?: string,
  status?: number,
  is_two_factor_enable?: number
  user_profile?: UserProfile | undefined,
}

interface UserProfile {
  address: Address,
  billing: Billing,
  communication_channel: CommunicationChannel,
  managers: Managers,
}

interface UserCountry {
  id?: number,
  title?: string,
}

interface Address {
  line1?: string,
  line2?: string,
  landmark?: string,
  city_id ?: number,
  state_id ?: number,
  country_id ?: number,
  city?: City | undefined,
  state?: State | undefined,
  country?: Country | undefined,
  zip_code?: string
}

export interface City {
  id?: string,
  title?: string,
  state_id?: string
}

export interface State {
  id?: string,
  title?: string,
  state_id?: string
}

export interface Country {
  id?: any,
  title?: string,
}

interface Billing {
  bank_name?: string,
  benificiary_name?: string,
  account_number?: string,
  ifsc_code?: string,
  swift_code?: string,
  address?: string,
  cheque?: string,
  gstin?: string,
  gstin_image?: string,
  cin?: string,
  cin_image?: string,
  pan?: string,
  pan_image?: string
}

export interface CommunicationChannel {
  email?: string,
  phone_number?: string
}

export interface Managers {
  id?: number,
  name?: string,
  email?: string,
  phone_number?: string,
  designation?: string
}

export interface UserProfile {
  addressline_1?: string,
  address_line_2?: string,
  city_id?: any,
  country_id?: any,
  profile_picture?: any,
  landmark?: any,
  state_id?: any,
  zip_code?: any,
  managers_id?:any
}

export interface permissionParams {
  id?: number;
  title?: string;
  slug?: string;
  status?: any;
  role?: any;
  order?: any;
  parent_id?: any;
  created_by?: any;
  is_parent?: any;
  updated_at?: string;
  privileges?: any;
  sub_modules?: SubModulesParams;
}

export interface billingData {
  id?: number;
  beneficiary_name?: string;
  bank_name?: string;
  ifsc_code?: any;
  bank_address?: any;
  cancelled_cheque?: any;
  gstin?: any;
  cin?: any;
  pan?: any;
  status?: string;
  user_id?: any;
  account_number?: any;
}


export interface privilegesParams {
  parent?: ParentParams;
}


export interface ParentParams {
  is_add?: number;
  is_edit?: number;
  is_delete?: number;
  is_view?: number;
}

export interface SubModulesParams {
  id?: number;
  title?: string;
  slug?: string;
  order?: number;
  status?: string;
  created_by?: any;
  parent_id?: number;
  updated_at?: string;
  created_at?: string;
  privileges?: privilegesParams;
}

export interface ChangePassword {
  email?: string;
  current_password?: string;
  new_password?: string;
  conform_pass?: string;
}

export interface SearchSettingList {
  id?: number,
  title?: string,
  slug?: string,
  search_type?: string,
  app_id?: string,
  key?: string,
  collection_name?: string,
  host?: string,
  port?: string,
  protocol?: string,
  user_id?: number,
  status?: string,
  perPage?: number,
  page?: number,
  search?: string
  sortBy?: string,
  sortDirection?: string
}

export interface StorageSettingDetails {

  storage_type?: StorageType,
  storage: Storage

}

export interface StorageType {
  title?: string,
  slug?: string,
  is_selected?: number
}

export interface Storage {
  id?: any
  storage_type?: string,
  host?: string,
  user_name?: string,
  password?: string,
  content_path?: string,
  wowza_path_format?: string,
  user_id?: string,
  cdn_url?: string,
  status?: string,
  deleted_at?: string,
  created_at?: string,
  key?: string,
  secret?: string,
  region?: string,
  default_acl?: string,
  bucket?: string,
  row?: string,
  content?: string,
  backup?: string
}

