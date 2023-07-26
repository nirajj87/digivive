import {defineStore} from 'pinia';
import type {clientParams , requiredClientParams} from './type'
import axios from '@axios';
import API_BASE_URL from '../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    // "Content-Type": "multipart/form-data",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
  };

export const useClientStore = defineStore('ClientStore',{
actions: {

    //fetch all countries
    fetchCountryList() {
        return axios.get('/api/v1/countries')
    },
     //fetch all timezones
     fetchTimeZoneList() {
        return axios.get('/api/v1/timezones')
    },
     //fetch all date-formats
     fetchDateFormatsList() {
        return axios.get('/api/v1/date-formats')
    },

     //fetch all permissions
     getPermissions(user_type:number) {
        return axios.get(`/api/v1/user/get-role-permissions/${user_type}`)
    },
      //fetch modules details
    fetchUserDetails(id:number) {
        return axios.get(`/api/v1/client/${id}`)
    },

     //fetch roles
    fetchRolesList() {
        return axios.get(`/api/v1/role`)
    },

     //fetch user list
     fetchClientList(data:any) {
        return new Promise((resolve , reject)=>{
            axios.get(`/api/v1/client` , {params : data})
            .then((response : any)=>{
                resolve(response.data)
            }).catch((error)=>{
                reject(error);
            })
        })
    },

    // add module data
    editUser(moduledData:clientParams) {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/client/save-permissions',{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }, 
     // add client data
    addClient(moduledData:requiredClientParams) {
        // console.log('New Client : ',moduledData);
        
        return new Promise((resolve,reject)=>{
            axios.post(`${API_BASE_URL}/client/create-client`,
               moduledData,{headers: headers}
            ).then(response => {                
                resolve(response.data)})
            .catch(error=>reject(error))
        })
    },

     // add module data
     forgotPassword(data:any) {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/password/forgot',{
                ...data
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },


    // update module data
    updateUser(moduledData:clientParams) {
        return new Promise((resolve,reject)=>{
            axios.put(`/api/v1/client/${moduledData.id}`,
                moduledData,{headers: {"Content-Type": "multipart/form-data"}}
            ).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

    // delete client data
    deleteClient(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`/api/v1/client/${id}`).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }

}
})


