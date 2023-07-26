import {defineStore} from 'pinia';
import type {userParams} from './type'
import axios from '@axios';


const headers = {
    "Language": "en",
    // "Content-Type": "application/json",
    "Content-Type": "multipart/form-data",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
  };
  

export const useAdminStore = defineStore('AdminStore',{
actions: {

    //fetch all countries

    fetchCountryList() {
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/countries')
            .then((response)=>{
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },
    
    //fetch all timezones
    fetchTimeZoneList() {
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/timezones')
            .then((response)=>{
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },

    //fetch all date-formats
    fetchDateFormatsList() {
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/date-formats')
            .then((response)=>{
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },

    //fetch all date-formats
    fetchManagersList() {
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/common/manager-list')
            .then((response)=>{
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },


    getRolesList(){
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/role')
            .then((response)=>{                
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },

    getDepartmentList(){
        return new Promise((resolve , reject)=>{

            axios.get('/api/v1/department-list')
            .then((response)=>{                
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },

    //fetch all date-formats
     getPermissions(user_type:number) {
        return axios.get(`/api/v1/user/get-role-permissions/${user_type}`)
    },

    //fetch modules details
    fetchUserDetails(id:number) {
        return axios.get(`/api/v1/user/${id}`)
    },

    //fetch user list
     fetchUserList(data:any) {
        // return axios.get(`/api/v1/user`,{ params: data});

        return new Promise((resolve , reject)=>{
            axios.get('/api/v1/user',{ params: data})
            .then((response)=>{
                resolve(response.data)
            })
            .catch((error)=>{
                reject(error)
            })
        })
    },

    // edit module data
    editUser(moduledData:userParams) {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/user/save-permissions',{
                ...moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }, 
    // add module data
    addUser(moduledData:userParams) {
        console.log('In store file user Data : ',moduledData);
        
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/user/create-admin',
                moduledData,{headers}
            ).then(response => resolve(response.data))
            .catch(error=>reject(error))
        })
    }, 
    
    
    deleteAdmin(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`/api/v1/user/${id}`).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

    // refresh token
    refreshToken() {
        return new Promise((resolve,reject)=>{
            axios.post('/api/v1/refresh').then(r => {
                let accessToken:any = r.data.data.authorisation.token;
                localStorage.setItem('accessToken', accessToken)
            })
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
}
})


