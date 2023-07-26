<script setup lang="ts">
import CountryEditable from '../view/CountryEditable.vue'
import { CountryModel, TimeZone } from '../view/type';
import { useRoute, useRouter } from 'vue-router';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const route = useRoute();

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
});


watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (!moduleId) {
        countryData.value.timezones.push({
            zoneName: '',
            gmtOffset: '',
            gmtOffsetName: '',
            abbreviation: '',
            tzName: '',
        })
    }
})

const breadcrumbsData = ref({
    page_name: 'add_country',
    name: 'add_country',
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