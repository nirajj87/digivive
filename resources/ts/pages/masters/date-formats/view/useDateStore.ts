import {defineStore} from 'pinia';
import type {DateList} from './types'
import axios from '@axios';

const BASE_URL = '/api/v1/date-formats';

const headers = { 
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer '+ localStorage.getItem('accessToken')
  };

export const useDateStore = defineStore('DateStore',{
actions: {

    //fetch all
    fetchList(params:DateList) {
        return new Promise((resolve, reject) => {
          axios
            .get(`${BASE_URL}`, {params, headers})
            .then(response => {
               
            //   console.log("List======>", response.data);
              resolve(response.data); // Resolve the promise with the platform list data
            })
            .catch(error => {
              console.error("Error fetching list:", error);
              reject(error); // Reject the promise with the error
            });
        });
    },
      
    //fetch modules details
    fetchDetails(id:number) {
        return new Promise((resolve, reject) => {
            axios
              .get(`${BASE_URL}/${id}`, {headers})
              .then(response => {
                resolve(response.data); // Resolve the promise with the platform list data
              })
              .catch(error => {
                console.error("Error fetching content list:", error);
                reject(error); // Reject the promise with the error
              });
          });
    },
  
    // add module data
    add(moduleData:DateList) {
        return new Promise((resolve,reject)=>{
            axios.post(`${BASE_URL}`,{
             ...moduleData
            },{headers}).then(response => {
                resolve(response)})
            .catch(error=>reject(error))
        })
    },

    // update module data
    update(moduleData:DateList) {
        return new Promise((resolve,reject)=>{
            axios.put(`${BASE_URL}/${moduleData.id}`,{
                ...moduleData
            },{headers}).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    },

    // delete module data
    delete(id:number) {
        return new Promise((resolve,reject)=>{
            axios.delete(`${BASE_URL}/${id}`,{headers}).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }
}
})


