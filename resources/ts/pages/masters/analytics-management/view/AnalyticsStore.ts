import { defineStore } from 'pinia'
import axios from 'axios';
import {AnalyticsParams} from './type';
const API_BASE_URL = 'http://localhost:8000/api/v1';

const headers = {
    "Analytics": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const useAnalyticsStore = defineStore('AnalyticsStore', () => {

    // 👉 fething all Analytics with pagination and search (if exists)
    const fetchAllAnalytics = async (params : AnalyticsParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/analytics-management/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new Analytics
    const addAnalytics = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/analytics-management/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating Analytics
    const updateAnalytics = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/analytics-management/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting Analytics
    const deleteAnalytics = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/analytics-management/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular Analytics
    const getDetailsAnalytics = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/analytics-management/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllAnalytics ,
        addAnalytics ,
        updateAnalytics ,
        deleteAnalytics ,
        getDetailsAnalytics
    }

})