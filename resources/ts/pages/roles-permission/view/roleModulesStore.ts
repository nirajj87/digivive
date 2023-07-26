import {defineStore} from 'pinia';
import type {RoleParams} from './type'
import axios from '@axios';



export const useRoleModuleStore = defineStore('ModulesStore',{
actions: {

    //fetch all modules
    fetchRolesList(data:any) {
        return axios.get('/api/v1/role',{ params: data})
    },
      //fetch modules details
    fetchModulesDetails(id:number) {
        return axios.get(`/api/v1/role/${id}`)
    },

    // add module data
    addModules(moduledData:RoleParams) {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/role',{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },


    // update module data
    updateModules(moduledData:RoleParams) {
        return new Promise((resolve,reject)=>{
            axios.patch(`/api/v1/role/${moduledData.id}`,{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

     // delete role data
     deleteRole(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`/api/v1/role/${id}`).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }

}
})


