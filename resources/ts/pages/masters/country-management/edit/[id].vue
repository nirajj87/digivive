<script setup lang="ts">
import { useCountryStore } from '../view/CountryStore';
import { useRoute, useRouter } from 'vue-router';
import { CountryModel } from '../view/type'
import CountryEditable from '../view/CountryEditable.vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const CountryStore = useCountryStore()
const route = useRoute();
const router = useRouter();

const countryData = ref<CountryModel>({
    title: '',
    iso2: '',
    iso3: '',
    country_code: '',
    phone_code: '',
    capital: '',
    currency: '',
    currency_name: '',
    currency_symbol: '',
    latitude: '',
    longitude: '',
    timezones: [],
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        CountryStore.getDetailsCountry(moduleId)
            .then((response: any) => {
                if (response) {
                    countryData.value = response.data;
                    if (typeof response.data.timezones == 'string') {
                        countryData.value.timezones = JSON.parse(response.data.timezones)
                    }
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-country-management' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_country',
    name: 'edit_country',
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
                        <CountryEditable :country-data="countryData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>