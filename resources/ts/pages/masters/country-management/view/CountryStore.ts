import { defineStore } from 'pinia'
import axios from 'axios';
import {CountryParams} from './type';
import API_BASE_URL from '../../../../const';

const headers = {
    "Country": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const useCountryStore = defineStore('CountryStore', () => {

    // 👉 fething all Country with pagination and search (if exists)
    const fetchAllCountry = async (params : CountryParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/countries`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new Country
    const addCountry = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/countries`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating Country
    const updateCountry = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/countries/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting Country
    const deleteCountry = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/countries/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular Country
    const getDetailsCountry = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/countries/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllCountry ,
        addCountry ,
        updateCountry ,
        deleteCountry ,
        getDetailsCountry
    }

})