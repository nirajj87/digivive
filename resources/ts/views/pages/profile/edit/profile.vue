<script lang="ts" setup>
import avatar1 from '@images/avatars/a1.jpg';
import type { VForm } from 'vuetify/components'
import 'flag-icons/css/flag-icons.min.css';
import 'v-phone-input/dist/v-phone-input.css';
import { lengthValidator, requiredValidator, emailValidator } from '@validators'
import router from '@/router'
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import type { UserParams } from './../types'

// 👉 Store
const userProfileStore = useUserProfileStore()

const route = useRoute()

const isSuccessDialogVisible = ref(false)
const isSnackbarVisible = ref(false)
const is_loading = ref(true)
const is_image_change = ref(false)

const successMessage = ref('')
const snackbarMessage = ref('')
const class_name = ref('')
const specifiedLength = ref('')

const refInputEl = ref<HTMLElement>()

const refForm = ref<VForm>();
const base_url = window.location.origin + '/storage/';

const userData: UserParams = ref({
    first_name: '',
    last_name: '',
    email: '',
    company_name: '',
    usercountry: {
        id: '',
        title: ''
    },
    profile_img: '',
    role_id: 0,
    designation: '',
    timezone: '',
    phone_number: '',
    date_format: [],
    dob: "",
    status: 0,
    is_two_factor_enable: 0,
    user_profile: {
        address: {
            line1: '',
            line2: '',
            landmark: '',
            city_id: '',
            state_id: '',
            country_id: '',
            zip_code: ''
        },
        billing: {
            bank_name: "",
            benificiary_name: "",
            account_number: "",
            ifsc_code: "",
            swift_code: "",
            address: "",
            cheque: "",
            gstin: "",
            cin: "",
            pan: ""
        },
        communication_channel: {
            email: "",
            phone_number: ""
        },
        managers: {
            name: "",
            email: "",
            phone_number: "",
            designation: ""
        },
        managers_id: []
    }
});


const countriesList = ref<[]>([]);
const timezonesList = ref<[]>([]);
const dateFormatsList = ref<[]>([]);
const cityList = ref<[]>([]);
const userCommuncationManagerList = ref([]);

const stateList = ref([{
    title: '',
    value: ''
}]);

const stateId = ref();
const cityId = ref();

const userId = Number(route.params.uid) ?? 0;

const fetchUser = () => {
    
    if (userId) {
        userProfileStore.fetchUser(userId).then(response => {
            if (response.data.success) {
                userData.value = response.data.data; 
            }
        }).catch(e => {
            is_loading.value = false;
            console.error(e)
        })
    }
}

const countriesFetch = () => {
    userProfileStore.fetchCountryList()
        .then((response: any) => {
         
            if (response.data.success) {
                countriesList.value = response.data.data.map((element: any) => {
                    return { 'title': element.title, 'value': element.id }
                });
                
            }
        })
        .catch((e) => {
            console.error(e)
        })
}

const stateFetch = () => {
    
    userProfileStore.fetchStateList(userData.value.user_profile.address.country_id, { 'perPage': -1 })
        .then((response: any) => {
            if (response.success) {
                stateList.value = response.data.map((element: any) => {
                    return { 'title': element.title, 'value': element.id }
                });
                const isSelectedState = stateList.value.find(item => {
                    item.value == stateId.value
                });
                stateId.value = stateList.value

                if (!isSelectedState) {
                    stateId.value = '';
                }
            }
        })
        .catch((e) => {
            console.error(e)
        })
}

const cityFetch = () => {
    userProfileStore.fetchCityByState(userData.value.user_profile.address.state_id, { 'perPage': -1 })
        .then((response: any) => {
            if (response.success) {
                cityList.value = response.data.map((element: any) => {
                    return { 'title': element.title, 'value': element.id }
                });
                const isSelectedCity = cityList.value.find(item => item.value == cityId.value);
              
                cityId.value = cityList.value
                // console.log("cityId.value======>",cityId.value);
                
                if (!isSelectedCity) {
                    cityId.value = ''
                }
                
                
            }
        })
        .catch((e) => {
            console.error(e)
        })
}

const timezonesFetch = () => {
    userProfileStore.fetchTimeZoneList()
        .then((response: any) => {
            if (response.data.success) {
                timezonesList.value = response.data.data;
            }
        })
        .catch((e) => {
            console.error(e);
        })
}

const dateFormatFetch = () => {
    userProfileStore.fetchDateFormatsList()
        .then((response: any) => {
            if (response.data.success) {
                dateFormatsList.value = response.data.data;
            }
        })
        .catch((e) => {
            console.error(e)
        })
}

const managerList = ref([{
    title: '',
    value: ''
}]);

const managerId = ref();

// 👉 Fetching User Manager List
const fetchUserManagerList = () => {
    userProfileStore.fetchUserManagerList().then(response => {
        if (response.data.success) {
            managerList.value = response.data.data.map((element: any) => {
                return { 'title': element.name, 'value': element.id }
            })
            const isSelectedmanager = managerList.value.find(item => {
                item.value == managerId.value
            });
            managerId.value = managerList.value
        }
    }).catch(error => {
        is_loading.value = false;
        console.error(error)
    })
}

// 👉 Fetching User Communication Manager List
const fetchUserCommunicationManagerList = (userId) => {
    userProfileStore.fetchUserCommunicationManagerList(userId).then(response => {
        if (response.data.success) {
            userCommuncationManagerList.value = response.data.data;
        }
    }).catch(error => {
        is_loading.value = false;
        console.error(error)
    })
}

watchEffect(() => {
    fetchUser();
    countriesFetch();
    timezonesFetch();
    dateFormatFetch();
    fetchUserManagerList();
    fetchUserCommunicationManagerList(userId);
})

const errors = ref<Record<string, string | undefined>>({
    company_name: undefined,
    first_name: undefined,
    country_id: undefined,
    email: undefined,
    timezone: undefined,
    date_format: undefined
})

const onSubmit = () => {
    if (userId) {
        const userUpdate = userData._value
        console.log("UserData1=====>", userUpdate);

        userProfileStore.updateProfile(userUpdate)
            .then((response: any) => {
                userUpdate.value = response.data
                console.log("userUpdate.value====>", userUpdate.value);

                successMessage.value = response.data.message;
                isSnackbarVisible.value = true;
                snackbarMessage.value = successMessage.value;
                class_name.value = 'success-snackbar';
                router.push({
                    name: "edit-profile-tab-uid", params: { tab: 'billing', uid: userId }
                })
            })
            .catch((e) => {
                console.error(e);
            })
    }
}

const ProfileData = {
    avatarImg: avatar1
}

const ProfileDataLocal = ref(structuredClone(ProfileData))

const changeAvatar = (file: Event) => {
    const fileReader = new FileReader()
    const { files } = file.target as HTMLInputElement;
    is_image_change.value = true;
    userData.value.profile_img = files[0];

    // uploadProfile(formData);
    if (files && files.length) {
        fileReader.readAsDataURL(files[0])
        fileReader.onload = () => {
            if (typeof fileReader.result === 'string')
                ProfileDataLocal.value.avatarImg = fileReader.result
        }
    }
}

const resetAvatar = () => {
    // is_image_change.value =  true;
    userData.value.profile_img = '';
    ProfileDataLocal.value.avatarImg = ''
}

</script>

<template>
    <VCardText class="pt-0">
        <VForm ref="refForm" @submit.prevent="onSubmit" class="form-border">

            <!-- Profile -->
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("profile_detail") }}</h5>
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="2">

                                    <div class="logo-upload">

                                        <div class="position-relative">

                                            <!-- 👉 Avatar -->
                                            <VAvatar class="border-upload avatar-name" size="80"
                                                :image="!is_image_change && userData.profile_img ? userData.profile_img : (is_image_change ? ProfileDataLocal.avatarImg : '')">
                                                <span v-if="userData.first_name">{{ (userData.first_name).slice(0, 2) }}</span>
                                            </VAvatar>

                                            <div class="upload-icon">
                                                <VIcon v-if="!userData.profile_img" @click="refInputEl?.click();"
                                                    icon="tabler-pencil" size="20" class="img-upload" />
                                                <input ref="refInputEl" type="file" name="file" accept=".jpeg,.png,.jpg,GIF"
                                                    hidden @input="changeAvatar">
                                                <VIcon v-if="userData.profile_img" icon="tabler-trash" @click="resetAvatar"
                                                    size="18" class="img-upload-remove" />
                                            </div>
                                        </div>

                                        <small>{{ $t("img_upload_detail") }}</small>
                                    </div>

                                </VCol>
                                <VCol cols="12" md="10">
                                    <VRow>
            
                                        <VCol cols="12" md="6">
                                       
                                            <VTextField  v-model="userData.company_name" :label="$t('company_name') + '*'"
                                                :rules="[requiredValidator]" :error-messages="errors.company_name" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VTextField v-model="userData.email" :label="$t('email') + '*'"
                                                :rules="[requiredValidator, emailValidator]"
                                                :error-messages="errors.email" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VTextField v-model="userData.first_name" :label="$t('first_name') + '*'"
                                                :rules="[requiredValidator]" :error-messages="errors.first_name" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VTextField v-model="userData.last_name" :label="$t('last_name')" />
                                        </VCol>

                                        <VCol cols="12" md="6">

                                            <v-phone-input v-model="userData.phone_number">
                                                <template #country-icon="{ country }">
                                                    <img
                                                        :src="`http://localhost:5173/node_modules/flag-icons/flags/4x3/${country.iso2}.svg`">
                                                    <span>+{{ country.dialCode }}</span>
                                                </template>
                                            </v-phone-input>

                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VAutocomplete v-if="userData.usercountry" :items="countriesList" v-model="userData.usercountry.id"
                                                :rules="[requiredValidator]"
                                                :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                :label="$t('country') + '*'" item-value="value" clearable
                                                :error-messages="errors.country_id" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VAutocomplete :items="dateFormatsList" v-model="userData.date_format"
                                                :rules="[requiredValidator]"
                                                :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                :label="$t('date_format') + '*'" item-value="format_key" clearable
                                                :error-messages="errors.date_format" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VTextField v-model="userData.designation" :label="$t('designation')" />
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VAutocomplete :items="managerList" :menu-props="{ maxHeight: '300' }"
                                                :label="$t('account_manager') + '*'" item-value="value" item-title="title"
                                                v-model="managerId" multiple>

                                                <template v-slot:selection="{ item, index }">
                                                    <v-chip v-if="index < 2">
                                                        <span>{{ item.title }}</span>
                                                    </v-chip>
                                                    <span v-if="index === 2"
                                                        class="text-grey text-caption align-self-center">
                                                        (+{{ item.length - 2 }} others)
                                                    </span>
                                                </template>
                                            </VAutocomplete>
                                        </VCol>

                                        <VCol cols="12" md="6">
                                            <VAutocomplete v-model="userData.timezone" variant="outlined"
                                                :label="$t('timezone') + '*'" :rules="[requiredValidator]"
                                                :items="timezonesList" clearable item-value="id"
                                                :menu-props="{ maxHeight: '300' }" />
                                        </VCol>
                                    </VRow>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- COMMUNICATION CHANNEL -->
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("communication_channel") }}</h5>
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('email') + '*'" :rules="[requiredValidator, emailValidator]"
                                        v-model="userData.email" :error-messages="errors.email" />

                                </VCol>
                                <VCol cols="12" md="6">
                                    <v-phone-input v-model="userData.user_profile.communication_channel.phone_number">
                                        <template #country-icon="{ country, decorative }">
                                            <img
                                                :src="`http://localhost:5173/node_modules/flag-icons/flags/4x3/${country.iso2}.svg`">
                                            <span>+{{ country.dialCode }}</span>
                                        </template>
                                    </v-phone-input>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- ADDRESS INFORMATION -->
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("address_information") }}</h5>
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="userData.user_profile.address.line1"
                                        :label="$t('address_line1')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="userData.user_profile.address.line2"
                                        :label="$t('address_line2')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="userData.user_profile.address.landmark" :label="$t('landmark')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VAutocomplete v-model="userData.user_profile.address.country_id" variant="outlined"
                                        @update:model-value="stateFetch()" :label="$t('country')" :items="countriesList"
                                        item-value="value" clearable :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VAutocomplete v-model="userData.user_profile.address.state_id" variant="outlined"
                                        @update:model-value="cityFetch()" :label="$t('state')" :items="stateList"
                                        item-value="value" clearable :menu-props="{ maxHeight: '300' }" />
                                </VCol> 
                                <VCol cols="12" md="4">
                                    <VAutocomplete v-model="userData.user_profile.address.city_id" variant="outlined"
                                        :label="$t('city')" :items="cityList" item-value="value" clearable
                                        :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VTextField type="number" max=6 v-model="userData.user_profile.address.zip_code"
                                        :label="$t('postal_zip_code')" :rules="[lengthValidator(specifiedLength, 3)]" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- Buttons -->
            <VRow>
                <VCol cols="12 text-right">
                    <VBtn variant="outlined" color="secondary mr-3" :to="{
                        name: 'profile-tab-id',
                        params: { tab: 'profile-details', id: userId }
                    }">
                        {{ $t("can_btn") }}
                    </VBtn>
                    <VBtn color="secondary" class="mr-3">
                        {{ $t("previous") }}
                    </VBtn>
                    <VBtn type="submit" color="primary">
                        {{ $t("next") }}
                    </VBtn>
                </VCol>
            </VRow>

        </VForm>
    </VCardText>

    <SuccessDialog v-model:isDialogVisible="isSuccessDialogVisible" v-model:success-msg="successMessage" />

    <!-- SnackBar -->
    <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
        {{ $t(snackbarMessage) }}
    </VSnackbar>
</template>
