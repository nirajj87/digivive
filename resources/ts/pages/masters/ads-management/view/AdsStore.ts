import { defineStore } from 'pinia'
import axios from 'axios';
import {AdsParams} from './type';
import API_BASE_URL from '../../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};



export const useAdsStore = defineStore('AdsStore', () => {

    // 👉 fething all Ads with pagination and search (if exists)
    const fetchAllAds = async (params : AdsParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/adsmanagement/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new Ads
    const addAds = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/adsmanagement/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating Ads
    const updateAds = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/adsmanagement/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting Ads
    const deleteAds = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/adsmanagement/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular Ads
    const getDetailsAds = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/adsmanagement/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllAds ,
        addAds ,
        updateAds ,
        deleteAds ,
        getDetailsAds
    }

})