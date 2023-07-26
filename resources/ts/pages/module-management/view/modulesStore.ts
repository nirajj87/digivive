import {defineStore} from 'pinia';
import type {ModulesParams} from './types'
import axios from '@axios';




export const useModuleStore = defineStore('ModulesStore',{
actions: {

    //fetch all modules
    fetchModulesList(data:any) {
        return axios.get('/api/v1/module',{ params: data})
    },
      //fetch modules details
    fetchModulesDetails(id:number) {
        return axios.get(`/api/v1/module/${id}`)
    },

     //fetch roles
    fetchRolesList() {
        return axios.get(`/api/v1/role`)
    },

      //fetch parents
    fetchParentList() {
        return axios.get(`/api/v1/modules/get-parents`)
    },

       //fetch childs
    fetchChildList(id:number) {
        return axios.get(`/api/v1/modules/get-child/${id}`)
    },

    // add module data
    addModules(moduledData:ModulesParams) {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/module',{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },


    // update module data
    updateModules(moduledData:ModulesParams) {
        return new Promise((resolve,reject)=>{
            axios.patch(`/api/v1/module/${moduledData.id}`,{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

    // delete module data
    deleteModules(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`/api/v1/module/${id}`).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }

}
})


