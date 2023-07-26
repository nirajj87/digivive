<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useCityStore } from './CityStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { CityModel } from './type';
import { useCountryStore } from '../../country-management/view/CountryStore';
import { useStateStore } from '../../state-management/view/StateStore'


const countryStore = useCountryStore();
const stateStore = useStateStore();
const cityStore = useCityStore();

//getting router variable
const router = useRouter();
const route = useRoute();


const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('');

const country_id = ref();
const state_id = ref();

const isLoading = ref(true);

const stateList = ref([{
    title: '',
    value: ''
}])
const countryList = ref([{
    title: '',
    value: '',
}])

onMounted(() => {

    // for add page
    if (!route.params.id) {

        countryStore.fetchAllCountry({ "perPage": -1 }).then((response: any) => {
            countryList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            });
        }).catch((error) => {
            console.log("Some Internal Server Error occured in fetching country");
            // router.push({name : 'masters-city-management'})
        })
    }
    //for edit page
    else {

        countryStore.fetchAllCountry({ "perPage": -1 }).then((response: any) => {
            countryList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            });

            return stateStore.getDetailsState(props.cityData.state_id);
        })
            .then((response: any) => {
                country_id.value = response.data.country_id ;
                return stateStore.getStateByCountry([response.data.country_id] , {'perPage' : -1});
            })
            .then((response: any) => {
                stateList.value = response.data.map((element: any) => {
                    return { 'title': element.title, 'value': element.state_id };
                });
                state_id.value = props.cityData.state_id;
                isLoading.value = false ;
            })

            .catch((error) => {
                console.log("Some Internal Server Error occured in fetching ");
                // router.push({name : 'masters-city-management'})
            })
    }


})

watchEffect(() => {

    stateStore.getStateByCountry([country_id.value] , {
        'perPage' : -1
    }).then((response: any) => {
        stateList.value = response.data.map((element: any) => {
            return { 'title': element.title, 'value': element.state_id }
        })
        //in add city page
        if(!route.params.id){
            state_id.value = stateList.value[0].value ;
        }
        else{
            // state_id.value = stateList.value[0].value  ;
            console.log('State List : ',stateList.value);
            console.log('State Id : ',state_id.value);
            
            
            const isSelectedState = stateList.value.find(item => item.value == state_id.value);
            console.log('isSelected State : ',isSelectedState);
            
            if(!isSelectedState){
                state_id.value = stateList.value[0].value ? stateList.value[0].value : '';
            }
        }
    }).catch((error) => {
        console.log("Some internal server error occurend in fetching state");

    })


})

const isFormValid = ref(false)


const items = [
    { title: 'active', value: '1' },
    { title: 'inactive', value: '0' },
]


// defining props type
interface Props {
    cityData: CityModel,

}

// defining props
const props = defineProps<Props>();

// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    iso_code2: undefined,
    iso_code3: undefined,
    country_code: undefined,
    status: undefined,
})


const CityStore = useCityStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                console.log('Every thing is ok');

                const moduleId: any = route.params.id ? route.params.id : 0;

                // updating City
                if (moduleId) {
                    
                    CityStore.updateCity(moduleId, {
                        ...props.cityData,
                        'state_id': state_id.value
                    })
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-city-management"
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
                //adding new City
                else {
                    
                    CityStore.addCity({
                        ...props.cityData,
                        'state_id': state_id.value
                    })
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-city-management"
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
        console.log('Error is : ', errors.value);

    }

}

const handleCancel = () => {
    router.push({ name: 'masters-city-management' })
}

</script>


<template>
    <section>

        <h5 v-show="!route.params.id" class="mb-4">{{ $t("add") }} {{ $t("city") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{ $t("edit") }} {{ $t("city") }}</h5>


        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackbarMessage }} </VSnackbar>

        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="handleSubmit()">
            <!-- <VForm ref="refForm" v-model="isFormValid" > -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <PageLoader v-show="route.params.id && isLoading"></PageLoader>
                            <VRow v-show="!(route.params.id && isLoading)">

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.cityData.title" :rules="[requiredValidator]"
                                        :label="$t('city')" />
                                </VCol>

                                <VCol cols="12" md="4">

                                    <VAutocomplete :rules="[requiredValidator]" v-model="country_id" :items="countryList"
                                        :label="$t('country')" clearable :menu-props="{ maxHeight: '300' }" />
                                </VCol>


                                <VCol cols="12" md="4">

                                    <VAutocomplete :rules="[requiredValidator]" v-model="state_id" :items="stateList"
                                        :label="$t('state')" clearable :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.cityData.latitude" :rules="[requiredValidator]"
                                        :label="$t('latitude')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.cityData.longitude" :rules="[requiredValidator]"
                                        :label="$t('longitude')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.cityData.status"
                                        :items="items.map((item) => { return { 'title': $t(item.title), 'value': $t(item.value) } })"
                                        :label="$t('status')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
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

</style>