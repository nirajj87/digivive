<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import ClientEditable from '../view/clientEditable.vue';
import {requiredClientParams} from '../view/type'
import { useClientStore } from './../view/clientModulesStore';

const clientStore = useClientStore()
const moduleData = ref<requiredClientParams>({});

const countriesList = ref<[]>([]);
const timezonesList = ref<[]>([]);
const dateFormatsList = ref<[]>([]);


const countriesFetch = () => {
    clientStore.fetchCountryList()
        .then((response: any) => {
            if (response.data.success) {
                countriesList.value = response.data.data;
            }
        })
        .catch((e) => {
            console.error(e)
        })
}
const timezonesFetch = () => {
    clientStore.fetchTimeZoneList()
        .then((response: any) => {
            if (response.data.success) {
                timezonesList.value = response.data.data;
            }
        })
        .catch((e) => {
            console.error(e);
        })
}
const dateFormastFetch = () => {
    clientStore.fetchDateFormatsList()
        .then((response: any) => {
            if (response.data.success) {
                dateFormatsList.value = response.data.data;
            }
        })
        .catch((e) => {
            console.error(e);
        })
}

onBeforeMount(()=>{
    countriesFetch();
    timezonesFetch();
    dateFormastFetch();
})

const breadcrumbsData = ref({
    page_name: 'add_client',
    name: 'add_client',
});
</script>

<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <ClientEditable :module-data="moduleData" :countries-list="countriesList" :timezones-list="timezonesList" :date-formats-list="dateFormatsList"/>
    </section>
</template>