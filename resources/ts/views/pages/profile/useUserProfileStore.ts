import type { AxiosResponse } from 'axios'
import { defineStore } from 'pinia'
import type { UserProperties } from '@/@fake-db/types'
import type { SearchSettingList, UserParams, UserProfile, permissionParams, ChangePassword } from './types'
import axios from '@axios'

const headers = {
  "Language": "en",
  "Content-Type": "application/json",
  "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
};

export const useUserProfileStore = defineStore('UserProfileStore', {
  actions: {

    // 👉 fetch single user
    fetchUser(id: number) {
      return new Promise<AxiosResponse>((resolve, reject) => {
        axios.get(`/api/v1/user/${id}`).then(response =>{
          // console.log("User Api=======>",response);
          
          resolve(response)}).catch(error => reject(error))
      })
    },

    //fetch all permissions
    getPermissions(id: number) {
      return axios.get(`/api/v1/user/get-permissions/${id}`)
    },

    // 👉 update User profile
    updateProfile(userData: UserParams) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/user/update-profile/${userData.id}`,
          userData, { headers: { "Content-Type": "multipart/form-data" } }
        ).then(response => {          
          resolve(response)})
          .catch(error => reject(error))
      })
    },

    // 👉 update User permissions
    updatePermission(permissionData: permissionParams) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/user/save-permissions`, {
          ...permissionData,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update password
    changePassword(data: ChangePassword) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/password/change-password`, {
          ...data,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    enableDisable2FA(data: any) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/two-factor-enable-disable`, {
          ...data,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    twoFactorAuthenticate(data: any) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/two-factor-enable-disable-otp-verify`, {
          ...data,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    resendTwoFactorAuthCode(data: any) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/v1/resend-two-factor-auth-code`, {
          ...data,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 upload image
    uploadImage(data: any, id: number) {
      return new Promise<AxiosResponse>((resolve, reject) => {
        axios.post(`/api/v1/user/update-profile-picture/17`,
          data, { headers: { "Content-Type": "multipart/form-data" } }
        ).then(response => resolve(response)).catch(error => reject(error))
      })
    },

    //fetch all countries
    fetchCountryList() {
      return axios.get('/api/v1/countries')
    },
     
   fetchStateList(query : any, params :any) {
    return new Promise((resolve, reject) => {
      axios.post(
        '/api/v1/state/list-by-country-id',
        { 'country_id': `[${query}]` },
        {
          headers: headers,
          params: params
        }
      )
        .then(response => resolve(response.data))
        .catch(error => reject(error))
    })
    },
   
   fetchCityByState(query : any, params :any) {
    return new Promise((resolve, reject) => {
      axios.post(
        '/api/v1/city/list-by-state-id/',
        { 'state_id': `[${query}]` },
        {
          headers: headers,
          params: params
        }
      )
        .then(response => resolve(response.data))
        .catch(error => reject(error))
    })
    },
    
    //fetch all timezones
    fetchTimeZoneList() {
      return axios.get('/api/v1/timezones')
    },

    //fetch all date-formats
    fetchDateFormatsList() {
      return axios.get('/api/v1/date-formats')
    },

    //fetch user list
    fetchClientList(data: any) {
      return axios.get(`/api/v1/client`, { params: data })
    },

    //fetch manager list
    fetchUserManagerList() { 
      return axios.get(`/api/v1/common/manager-list`)
    },

    //fetch manager list
    fetchManagerList(user_id: number) {
      return axios.get(`/api/v1/client/get-managers/` + user_id)
    },

    //fetch manager list
    fetchUserCommunicationManagerList(user_id: number) {
      return axios.get(`/api/v1/common/communication-manager-list/` + user_id)
    },

    fetchSearchList(user_id: number) {
      return new Promise<AxiosResponse>((resolve, reject) => {
        axios.post(`/api/v1/search-setting/details`,  { user_id: user_id } ).then(response => {
          // console.log("Response List====>", response);

          resolve(response.data)
        }).catch(error => reject(error))
      })
    },

    //fetch search details
    fetchSearchDetails(user_id: number) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/v1/search-setting/details`, { user_id: user_id })
          .then(response => {
            // console.log("Details===>", response);
            
            resolve(response.data); // Resolve the promise with the platform list data
          })
          .catch(error => {
            console.error("Error fetching Details:", error);
            reject(error); // Reject the promise with the error
          });
      });
    },

    // update search setting data
    updateSearch(data: SearchSettingList) {
      return new Promise((resolve, reject) => {
        axios.put(`/api/v1/search-setting/edit`, {
          ...data
        }).then(response => {
          console.log("response search===>", response);

          resolve(response)
        })
          .catch(error => reject(error))
      })
    },

    searchType() {
      return new Promise((resolve, reject) => {
        axios.get(`/api/v1/search-setting/searchtype`).then(response => {
          // console.log("search type===>", response);

          resolve(response.data)
        })
          .catch(error => reject(error))
      })
    },
  },
})