import { defineStore } from 'pinia'
import axios from 'axios';
import {AdvisoryParams} from './type';
import API_BASE_URL from '../../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};



export const useContentAdvisoryStore = defineStore('ContentAdvisory', () => {

    // 👉 fething all advisory with pagination and search (if exists)
    const fetchAllAdvisory = async (params : AdvisoryParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/content-advisory/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new advisory
    const addAdvisory = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/content-advisory/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating advisory
    const updateAdvisory = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/content-advisory/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting advisory
    const deleteAdvisory = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/content-advisory/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular advisory
    const getDetailsAdvisory = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/content-advisory/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllAdvisory ,
        addAdvisory ,
        updateAdvisory ,
        deleteAdvisory ,
        getDetailsAdvisory
    }

})