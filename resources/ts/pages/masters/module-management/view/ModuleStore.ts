import { defineStore } from 'pinia'
import axios from 'axios';
import {ModuleParams} from './type';
import API_BASE_URL from '../../../../const';

const headers = {
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};



export const useModuleStore = defineStore('ModuleStore', () => {

    // 👉 fething all Module with pagination and search (if exists)
    const fetchAllModule = async (params : ModuleParams) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/module/`,  {
                params : params ,
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 adding new Module
    const addModule = async (data : any) => {
        return new Promise((resolve, reject) => {
            axios.post(`${API_BASE_URL}/module/`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 updating Module
    const updateModule = async (id : string ,data : Object) => {
        return new Promise((resolve, reject) => {
            axios.put(`${API_BASE_URL}/module/${id}`, data , {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 deleting Module
    const deleteModule = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.delete(`${API_BASE_URL}/module/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting details of particular Module
    const getDetailsModule = async (id : number) => {
        return new Promise((resolve, reject) => {
            axios.get(`${API_BASE_URL}/module/${id}`, {
                headers: headers
            }).then(response => resolve(response.data))
                .catch(error => reject(error))
        })
    }

    // 👉 getting all parent module list
    const getParentModuleList = async ()=>{
        return new Promise((resolve , reject)=>{
            axios.get(`${API_BASE_URL}/modules/get-parents` , {
                headers : headers
            }).then((response =>{
                resolve(response.data)
            })).catch(error => reject(error))
        })
    }

    // 👉 getting all child module list
    const getChildModuleList = async (id : number)=>{
        return new Promise((resolve , reject)=>{
            axios.get(`${API_BASE_URL}/modules/get-child/${id}` , {
                headers : headers
            }).then((response =>{
                resolve(response.data)
            })).catch(error => reject(error))
        })
    }

    // 👉 getting all child module list
    const getRolesList = async ()=>{
        return new Promise((resolve , reject)=>{
            axios.get(`${API_BASE_URL}/role` , {
                headers : headers
            }).then((response =>{
                resolve(response.data)
            })).catch(error => reject(error))
        })
    }

    return {
        fetchAllModule ,
        addModule ,
        updateModule ,
        deleteModule ,
        getDetailsModule,
        getParentModuleList ,
        getChildModuleList,
        getRolesList
    }

})