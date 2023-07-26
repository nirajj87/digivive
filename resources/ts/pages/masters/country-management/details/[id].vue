<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useCountryStore } from '../view/CountryStore';
import { CountryModel } from '../view/type'

const countryStore = useCountryStore()
const router = useRouter();
const route = useRoute();


const countryData = ref<CountryModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'country_management',
    name: 'country_management',
});

onBeforeMount(() => {

    let countryId = Number(route.params.id) ?? 0;


    countryStore.getDetailsCountry(countryId)
        .then((response: any) => {
            countryData.value = response.data;
            if (typeof response.data.timezones == 'string') {
                countryData.value.timezones = JSON.parse(response.data.timezones)
            }
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

        })

})


</script>


<template>
    <section>


        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />


        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("country") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("country_name") }}</p>
                                        <h5>{{ countryData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("iso_code2") }}</p>
                                        <h5>{{ countryData.iso2 }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("iso_code3") }}</p>
                                        <h5>{{ countryData.iso3 }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("phone_code") }}</p>
                                        <h5>{{ countryData.phone_code }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("capital") }}</p>
                                        <h5>{{ countryData.capital }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("currency") }}</p>
                                        <h5>{{ countryData.currency }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("currency_name") }}</p>
                                        <h5>{{ countryData.currency_name }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("currency_symbol") }}</p>
                                        <h5>{{ countryData.currency_symbol }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("latitude") }}</p>
                                        <h5>{{ countryData.latitude }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("longitude") }}</p>
                                        <h5>{{ countryData.longitude }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ countryData.status == '1' ? 'Active' : (countryData.status == '0' ?
                                            'Inactive' : (countryData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5>

                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>
            </VCardText>


            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("timezone") }}</h5>
                        <VCard border v-for="timezone in countryData.timezones">
                            <VCardText>
                                <VRow>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("zone_name") }}</p>
                                        <h5>{{ timezone.zoneName }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("gmt_off_set") }}</p>
                                        <h5>{{ timezone.gmtOffset }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("gmt_off_set_name") }}</p>
                                        <h5>{{ timezone.gmtOffsetName }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("abbreviation") }}</p>
                                        <h5>{{ timezone.abbreviation }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("tz_name") }}</p>
                                        <h5>{{ timezone.tzName }}</h5>
                                    </VCol>


                                </VRow>
                            </VCardText>
                        </VCard>


                    </VCol>
                </VRow>
            </VCardText>


            <VCardText>
                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-country-management' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>

        </VCard>
    </section>
</template>
