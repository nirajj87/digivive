import type { AxiosResponse } from 'axios'
import { defineStore } from 'pinia'
import type { UserProperties } from '@/@fake-db/types'
import type {StorageSettingDetails,Storage} from './types'
import axios from '@axios'

export const userAdvanceSettingStore = defineStore('UserAdvanceSettingStore', {
  actions: {

     //add bank details
     updateStorageSettingDetails(data: Storage) {
        return axios.put(`/api/v1/storage-setting/edit/`,data)
      },
    
       //get bank details
     getStorageSettingDetails(data: any) {
        return axios.post(`/api/v1/storage-setting/details/`,data)
      },
    
},
})
