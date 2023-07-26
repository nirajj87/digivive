import { defineStore } from 'pinia'
import axios from 'axios';
import {PlatformParams} from './type'
const API_BASE_URL = 'http://localhost:8000/api/v1';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const usePlatformStore = defineStore('PlatformStore', () => {

    // 👉 fething all platform with pagination and search (if exists)
    const fetchAllPlatform = async (params : PlatformParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/platform/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new platform
    const addPlatform = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/platform/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating platform
    const updatePlatform = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/platform/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting platform
    const deletePlatform = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/platform/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular platform
    const getDetailsPlatform = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/platform/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllPlatform ,
        addPlatform ,
        updatePlatform ,
        deletePlatform ,
        getDetailsPlatform
    }

})