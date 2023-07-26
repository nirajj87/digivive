import { defineStore } from 'pinia'
import axios from 'axios';
import {StateParams , StateByCountry} from './type'
const API_BASE_URL = 'http://localhost:8000/api/v1';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const useStateStore = defineStore('StateStore', () => {

    // 👉 fething all State with pagination and search (if exists)
    const fetchAllState = async (params : StateParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/state/list`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new State
    const addState = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/state/create`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating State
    const updateState = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/state/edit/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting State
    const deleteState = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/state/delete/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular State
    const getDetailsState = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/state/details/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting state by country
    // const getStateByCountry = async (queryParams : any) => {
    //     console.log('statebycountry query : ',queryParams);
        
    //     return new Promise((resolve, reject) => {
    //         axios.post(`${API_BASE_URL}/state/list-by-country-id/`, {
    //             params : {'country_id' : queryParams},
    //             headers: headers
    //         }).then(response => {resolve(response.data); console.log('In then block');
    //         })
    //             .catch(error => {reject(error) ; console.log('in catch block ',error);
    //             })
    //     })
    // }


    const getStateByCountry = async (query : any , params : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/state/list-by-country-id`,  {
                'country_id' :  `[${query.toString()}]` },{
                headers: headers ,
                params : params
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    return {
        fetchAllState ,
        addState ,
        updateState ,
        deleteState ,
        getDetailsState,
        getStateByCountry
    }

})