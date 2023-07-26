import {defineStore} from 'pinia';
import type {BitframeList} from './types'
import axios from '@axios';

const BASE_URL = '/api/v1/bitframe';

const headers = { 
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer '+ localStorage.getItem('accessToken')
  };

export const useBitframeStore = defineStore('BitframeListStore',{
actions: {

    //fetch all
    fetchList(params: BitframeList) {
        return new Promise((resolve, reject) => {
          axios
            .get(`${BASE_URL}/list`, {params, headers})
            .then(response => {
              resolve(response.data); // Resolve the promise with the platform list data
            })
            .catch(error => {
              console.error("Error fetching platform list:", error);
              reject(error); // Reject the promise with the error
            });
        });
    },
      
    //fetch modules details
    fetchDetails(id:number) {
        return new Promise((resolve, reject) => {
            axios
              .get(`${BASE_URL}/details/${id}`, {headers})
              .then(response => {
                resolve(response.data); // Resolve the promise with the platform list data
              })
              .catch(error => {
                console.error("Error fetching Details:", error);
                reject(error); // Reject the promise with the error
              });
          });
    },
  
    // add module data
    add(moduleData:BitframeList) {   
        return new Promise((resolve,reject)=>{
            axios.post(`${BASE_URL}/create`,{
             ...moduleData    
            },{headers}).then(response => {
                resolve(response)})
            .catch(error=>reject(error))
        })
    },

    // update module data
    update(moduleData:BitframeList) {
        return new Promise((resolve,reject)=>{
            axios.put(`${BASE_URL}/edit/${moduleData.id}`,{
                ...moduleData
            },{headers}).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

    // delete module data
    delete(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`${BASE_URL}/delete/${id}`,{headers}).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },
}
})


