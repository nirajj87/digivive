import {defineStore} from 'pinia';
import type {AssetList} from './types'
import axios from '@axios';

const BASE_URL = '/api/v1/asset-type';

const headers = { 
    "Language": "en",
    "Content-Type": "application/json",
    "Authorization": 'Bearer '+ localStorage.getItem('accessToken')
  };

export const useAssetStore = defineStore('AssetStore',{
actions: {

    //fetch all List
    fetchList(params: AssetList) {
        return new Promise((resolve, reject) => {
          axios
            .get(`${BASE_URL}/list`, { params, headers })
            .then(response => {
              resolve(response.data); // Resolve the promise with the platform list data
            })
            .catch(error => {
              console.error("Error fetching content list:", error);
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
              //   console.log("Platform List======>", response.data);
                resolve(response.data); // Resolve the promise with the platform list data
              })
              .catch(error => {
                console.error("Error fetching content list:", error);
                reject(error); // Reject the promise with the error
              });
          });
    },
  
    // add module data
    add(moduleData:AssetList) {
        
        return new Promise((resolve,reject)=>{
            axios.post(`${BASE_URL}/create`,{
             ...moduleData
              
            },{headers}).then(response => {
                resolve(response)})
            .catch(error=>reject(error))
        })
    },

    // update module data
    update(moduleData:AssetList) {
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
    }
}
})


