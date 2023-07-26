<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useCountryStore } from './CountryStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { CountryModel, TimeZone } from './type';

import 'flag-icons/css/flag-icons.min.css';
import 'v-phone-input/dist/v-phone-input.css';


// defining props type
interface Props {
    countryData: CountryModel
}

// defining props
const props = defineProps<Props>();

const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')

const timeZoneObject = ref<TimeZone>({
    zoneName: '',
    gmtOffset: '',
    gmtOffsetName: '',
    abbreviation: '',
    tzName: '',
})


const isFormValid = ref(false)
const router = useRouter();
const route = useRoute();

const items = [
    { title: 'active', value: '1' },
    { title: 'inactive', value: '0' },
]


// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    country_code: undefined,
    status: undefined,
})


const CountryStore = useCountryStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                console.log('Every thing is ok');

                const moduleId: any = route.params.id ? route.params.id : 0;

                console.log('Exclusive Country : ',props.countryData);
                

                // updating Country
                if (moduleId) {

                    const newCountry = {
                        ...props.countryData,
                        "timezones": JSON.stringify(props.countryData.timezones)
                    }

                    
                    console.log("updated country is : ", newCountry);

                    CountryStore.updateCountry(moduleId, newCountry)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-country-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {
                            if (error.response.data.message) {
                                isSnackbarVisibileDialog.value = true;
                                snackbarMessage.value = 'internal-server-error';
                                snackbarClass.value = 'delete-snackbar'
                            }
                        })
                }
                //adding new Country
                else {
                    console.log('Adding');
                    const newCountry = {
                        ...props.countryData,
                        "timezones": JSON.stringify(props.countryData.timezones)
                    }

                    console.log("new country is : ", newCountry);

                    CountryStore.addCountry(newCountry)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-country-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {

                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = 'internal-server-error';
                            snackbarClass.value = 'delete-snackbar'
                        })
                }

            }
        })

    }
    else {
        console.log('Data mistake : ', refForm.value);
        console.log('Error is : ', errors.value);

    }

}

const handleCancel = () => {
    router.push({ name: 'masters-country-management' })
}

const addTimeZone = () => {


    props.countryData.timezones.push(timeZoneObject.value);
    console.log('Props timezone after adding : ',props.countryData.timezones);
    

    resetTimeZoneObject();
}

const resetTimeZoneObject = () => {
    timeZoneObject.value = {
        zoneName: '',
        gmtOffset: '',
        gmtOffsetName: '',
        abbreviation: '',
        tzName: '',
    };
};

const removeTimeZone = (index: number) => {
    props.countryData.timezones.splice(index , 1);
    console.log('props timezone after deletion : ', props.countryData.timezones);
}

</script>


<template>
    <section>
        <h5 v-show="!route.params.id" class="mb-4">{{  $t("add") }} {{ $t("country") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{  $t("edit") }} {{ $t("country") }}</h5>

        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackbarMessage }} </VSnackbar>

        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="handleSubmit()">
            <!-- <VForm ref="refForm" v-model="isFormValid" > -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.title" :rules="[requiredValidator]"
                                        :label="$t('country_name') + '*'" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.iso2" :rules="[requiredValidator]"
                                        :label="$t('iso_code2')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.iso3" :rules="[requiredValidator]"
                                        :label="$t('iso_code3')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.phone_code" :rules="[requiredValidator]"
                                        :label="$t('phone_code')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.capital" :rules="[requiredValidator]"
                                        :label="$t('capital')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.currency" :rules="[requiredValidator]"
                                        :label="$t('currency')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.currency_name" :rules="[requiredValidator]"
                                        :label="$t('currency_name')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.currency_symbol" :rules="[requiredValidator]"
                                        :label="$t('currency_symbol')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.latitude" :rules="[requiredValidator]"
                                        :label="$t('latitude')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.countryData.longitude" :rules="[requiredValidator]"
                                        :label="$t('longitude')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.countryData.status"
                                        :items="items.map((item) => { return { 'title': $t(item.title), 'value': $t(item.value) } })"
                                        :label="$t('status')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                



                <VCol cols="12">
                    <h5 class="mb-2">{{$t("timezone")}}</h5>
                    <VCard border class="mb-2" v-for="(timezone, index) in props.countryData.timezones">
                        <VCardText>
                            <VRow>
                                <VBtn v-show="props.countryData.timezones.length > 1" icon size="x-small" @click="removeTimeZone(index)"
                                    class="remove-icon">
                                    <VIcon size="20" icon="tabler-x" />
                                </VBtn>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="timezone.zoneName" :rules="[requiredValidator]"
                                        :label="$t('zone_name')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="timezone.gmtOffset" :rules="[requiredValidator]"
                                        :label="$t('gmt_off_set')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="timezone.gmtOffsetName" :rules="[requiredValidator]"
                                        :label="$t('gmt_off_set_name')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="timezone.abbreviation" :rules="[requiredValidator]"
                                        :label="$t('abbreviation')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="timezone.tzName" :rules="[requiredValidator]"
                                        :label="$t('tz_name')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VCardText>
                        <VRow>
                            <VCol cols="12" class="text-right">
                                <VBtn @click="addTimeZone">
                                    <VIcon
                                        icon="tabler-square-plus"
                                    />
                                    {{$t("add")}} {{$t("timezone")}}
                                </VBtn>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCol>





            </VRow>
            <VRow>
                <VCol cols="12" class="text-right">
                    <VBtn class="mr-3" color="secondary" @click="handleCancel()">
                        {{ $t("can_btn") }}
                    </VBtn>
                    <VBtn  type="submit" @click="refForm?.validate()">
                        <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
                        {{ $t("sub_btn") }}
                    </VBtn>

                </VCol>
            </VRow>
        </VForm>
    </section>
</template>

<style>
.remove-icon {
    position: absolute;
    right: 0;
    top: 0
}

</style>