<script setup lang="ts">
import { useStateStore } from '../view/StateStore';
import { useRoute, useRouter } from 'vue-router';
import { StateModel, CountryList } from '../view/type'
import StateEditable from '../view/StateEditable.vue';
import { useCountryStore } from '../../country-management/view/CountryStore';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const CountryStore = useCountryStore();

const stateStore = useStateStore()
const route = useRoute();
const router = useRouter();

const stateData = ref<StateModel>({
    title: ''
})


let countryList = ref<CountryList[]>([{
    title: "",
    value: "",
}])

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        stateStore.getDetailsState(moduleId)
            .then((response: any) => {
                if (response) {
                    stateData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-State-management' })

            })
    }


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
    page_name: 'edit_state',
    name: 'edit_state',
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
                        <StateEditable :state-data="stateData" :country-list="countryList" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>