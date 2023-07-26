import { defineStore } from 'pinia'
import axios from 'axios';
import {UserParams} from './type';
import API_BASE_URL from '../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "multipart/form-data",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};



export const useClientUserStore = defineStore('ClientUserStore', () => {

    // 👉 fething all user of client with pagination and search (if exists)
    const fetchAllUserOfClient = async (id:any , params : UserParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/client/get-client-user/${id}`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new user of client
    const addClientUser = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/client/create-client-user`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating advisory
    const updateClientUser = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/content-advisory/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting advisory
    const deleteClientUser = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/content-advisory/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular advisory
    const getDetailsOfClientUser = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/content-advisory/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    const getDepartmentList = async()=>{
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/department-list`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // const getManagersList = async()=>{
    //     return new Promise((resolve, reject) => {
    //         axios.get(`${API_BASE_URL}/common/manager-list`, {
    //             headers: headers
    //         }).then(response => resolve(response.data))
    //             .catch(error => reject(error))
    //     })
    // }

    const getManagersList = async(id : any)=>{
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/client/get-managers/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }


    return {
        fetchAllUserOfClient ,
        addClientUser ,
        updateClientUser ,
        deleteClientUser ,
        getDetailsOfClientUser ,
        getDepartmentList ,
        getManagersList
    }

})