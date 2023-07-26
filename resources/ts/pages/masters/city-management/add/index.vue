<script setup lang="ts">
import CityEditable from '../view/CityEditable.vue'
import { CityModel } from '../view/type';
import { useStateStore } from '../../state-management/view/StateStore';
import { useCountryStore } from '../../country-management/view/CountryStore'
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const StateStore = useStateStore();
const CountryStore = useCountryStore();


const cityParams = ref<CityModel>({
    title: '',
})



const breadcrumbsData = ref({
    page_name: 'add_city',
    name: 'add_city',
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
                        <CityEditable :city-data="cityParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>