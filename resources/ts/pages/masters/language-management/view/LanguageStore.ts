import { defineStore } from 'pinia'
import axios from 'axios';
import {LanguageParams} from './type';
const API_BASE_URL = 'http://localhost:8000/api/v1';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const useLanguageStore = defineStore('LanguageStore', () => {

    // 👉 fething all Language with pagination and search (if exists)
    const fetchAllLanguage = async (params : LanguageParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/language/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new Language
    const addLanguage = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/language/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating Language
    const updateLanguage = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/language/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting Language
    const deleteLanguage = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/language/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular Language
    const getDetailsLanguage = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/language/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllLanguage ,
        addLanguage ,
        updateLanguage ,
        deleteLanguage ,
        getDetailsLanguage
    }

})