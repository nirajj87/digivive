import {defineStore} from 'pinia';
import type {ModulesParams} from './types'
import axios from '@axios';


export const authModuleStore = defineStore('ModulesStore',{
actions: {

    registerModules(moduledData:ModulesParams) {
        return new Promise((resolve,reject)=>{
            axios.post('/register',{
                moduledData
            }).then(response => resolve(response))
            .catch(error=>reject(error))
        })
    }

}
})


