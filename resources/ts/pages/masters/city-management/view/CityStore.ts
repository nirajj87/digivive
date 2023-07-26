import { defineStore } from 'pinia'
import axios from 'axios';
import {CityParams} from './type';
import API_BASE_URL from '../../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const useCityStore = defineStore('CityStore', () => {

    // 👉 fething all City with pagination and search (if exists)
    const fetchAllCity = async (params : CityParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/city/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new City
    const addCity = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/city/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating City
    const updateCity = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/city/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting City
    const deleteCity = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/city/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular City
    const getDetailsCity = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/city/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting city by state
    const getCityByState = async (query : any , params : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/city/list-by-state-id/`,{
                'state_id' : `[${query.toString()}]`
            }, {
                headers: headers,
                params : params
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllCity ,
        addCity ,
        updateCity ,
        deleteCity ,
        getDetailsCity ,
        getCityByState
    }

})