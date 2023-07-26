import type { AxiosResponse } from 'axios'
import { defineStore } from 'pinia'
import type { UserProperties } from '@/@fake-db/types'
import type { billingData } from './types'
import axios from '@axios'


const headers = {
  "Language": "en",
  "Content-Type": "application/json",
  "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};


export const userBillingStore = defineStore('UserBillingStore', () => {


  const addBillingDetails = (billingData: billingData) => {

    return new Promise((resolve, reject) => {
      axios.post('/api/v1/bank-details/', billingData,
        { headers: { "Content-Type": "multipart/form-data" } }
      )
        .then((response: any) => {
          return resolve(response.data)
        }).catch((error) => {
          reject(error)
        })
    })
  }


  const updateBillingDetails = ( billingData: FormData) => {
    console.log('Coming in update billing api with data : ',billingData);
    
    return new Promise((resolve, reject) => {
      // axios.put(`/api/v1/user/update-billing/${userId}`, {'billing' : {billingData}},
      axios.post(`/api/v1/user/update-billing/`, billingData  ,
        { headers : {
          "Language": "en",
          "Content-Type": "multipart/form-data",
          // "Content-Type": "application/json",
          "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
        }}
      )
        .then((response: any) => {
          return resolve(response.data)
        }).catch((error) => {
          reject(error)
        })
    })
  }



  const getBillingDetails = (userId: number) => {

    return new Promise((resolve, reject) => {
      axios.get(`/api/v1/user/${userId}`,
        { headers }
      )
        .then((response: any) => {
          console.log('My api response');
          
          return resolve(response.data)
        }).catch((error) => {
          reject(error)
        })
    })

  }



  return {
    addBillingDetails ,
    getBillingDetails ,
    updateBillingDetails
  }

})


