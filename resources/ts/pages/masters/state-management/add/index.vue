<script setup lang="ts">
import StateEditable from '../view/StateEditable.vue'
import { StateModel, CountryList } from '../view/type';
import { useCountryStore } from '../../country-management/view/CountryStore'
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const CountryStore = useCountryStore();

const stateParams = ref<StateModel>({
    title: '',
    country_id: '',
    state_code: '',
})

let countryList = ref<CountryList[]>([{
    title: "",
    value: "",
}])


// let countryList : CountryList[] = [] ;

// console.log('At add page in index.vue : ', stateParams);

watchEffect(() => {
    CountryStore.fetchAllCountry({ "perPage": 253 }).then((response: any) => {
        // countryList.value = response.data ;

        const fetchedCountryList = response.data.map((element: any) => {
            return { 'title': element.title, 'value': element.id };
        });

        countryList.value = fetchedCountryList


    }).catch((error) => {
        console.log("Internal server error occured .");

    });

})


const breadcrumbsData = ref({
    page_name: 'add_state',
    name: 'add_state',
});

</script>

<template>
    <div>
        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <StateEditable :state-data="stateParams" :country-list="countryList" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>