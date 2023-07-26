<script lang="ts" setup>
import type { VForm } from 'vuetify/components';
import { useClientStore } from './../view/clientModulesStore';
import type { requiredClientParams, permissionParams } from '../view/type';
import { emailValidator, requiredValidator } from '@validators';
import router from '@/router'
import { useRoute } from 'vue-router';
import avatarImage from '@images/avatars/a2.jpg'
const base_url = window.location.origin + '/storage/';

const isSnackbarVisibileDialog = ref(false);
const snackbarMessage = ref('');
const snackbarClass = ref('');

const refVForm = ref<VForm>();
const is_loading = ref(false)
// const status = [{ title: 'active', value: '1' }, { title: 'inactive', value: '0' }];
const isFormValid = ref(false)
const message = ref('');


const refInputEl = ref<HTMLElement>()
const profileImage = ref<File | null | string>(null)
const profileImageLocal = ref<File | null | string>(null)
const isImageChange = ref(false);
// const ProfileData = {
//     avatarImg: avatar1
// }
const clientStore = useClientStore()
const route = useRoute();

interface Props {
    moduleData: requiredClientParams
    countriesList: any
    timezonesList: any
    dateFormatsList: any
}

const props = defineProps<Props>();


const errors = ref<Record<string, string | undefined>>({
    company_name: undefined,
    first_name: undefined,
    country_id: undefined,
    email: undefined,
    timezone: undefined,
    date_format: undefined
})

const onSubmit = () => {
    is_loading.value = true;
    // setTimeout(() => {
    //     router.push({
    //         name: 'client-management-list'
    //     })
    // }, 3000);

    console.log('New Client is : ', props.moduleData);

    clientStore.addClient(props.moduleData)
        .then((response: any) => {

            console.log('response is ======' , response );
            
            is_loading.value = false;
            snackbarMessage.value = response.message;
            isSnackbarVisibileDialog.value = true;
            snackbarClass.value = 'success-snackbar';

            setTimeout(() => {
                router.push({
                    name: 'client-management-list'
                })
            }, 3000);
        })
        .catch((e) => {
            console.log('Error occured : ',e)
            is_loading.value = false;
            let res = e.response.data.data;
            snackbarMessage.value = res.email[0];
            isSnackbarVisibileDialog.value = true;
            snackbarClass.value = 'delete-snackbar';


            // for (const key in res) {
            //     for (const e_key in errors.value) {
            //         if (key == e_key) {
            //             errors.value[e_key] = res[key][0];
            //             console.log(errors.value[e_key]);
            //             snackbarClass.value = 'delete-snackbar';
            //             isSnackbarVisibileDialog.value = true;
            //             //snackbarMessage.value = res[key][0];
            //             snackbarMessage.value = message.value
            //             break;
            //         }
            //     }
            // }
        })
}

const changeAvatar = (file: Event) => {


    console.log('coming in handle image change');

    const fileReader = new FileReader();
    const { files } = file.target as HTMLInputElement;
    isImageChange.value = true;
    profileImage.value = files ? files[0] : null;
    props.moduleData.profile_img = files ? files[0] : null;

    // const file = file.target.files[0];

    if (files && files.length) {
        fileReader.readAsDataURL(files[0]);
        fileReader.onload = () => {
            if (typeof fileReader.result == 'string') {
                profileImageLocal.value = fileReader.result;
            }
        }
    }

}

const resetAvatar = () => {

    isImageChange.value = false;
    profileImage.value = null;
    profileImageLocal.value = null;
    props.moduleData.profile_img = null;


    // props.moduleData.profile_img = '';
    // ProfileDataLocal.value.avatarImg = avatar1
}

const onFormSubmit = () => {

    if (refVForm.value) {

        refVForm.value.validate()
            .then(({ valid }) => {
                if (valid) {
                    onSubmit();
                } else {
                    console.log('All field is not filled');
                    onSubmit();
                }
            })

    }

    // refVForm.value?.validate()
    //     .then(({ valid: isValid }) => {
    //         if (isValid)
    //             onSubmit()
    //     })
    // setTimeout(() => {
    //     router.push({
    //         name: 'client-management-list'
    //     })
    // }, 2000);
}

</script>

<template >
    <section>
        <VCard>
            <!-- SnackBar -->
            <!-- <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass">
                {{ $t(snackbarMessage) }}
            </VSnackbar> -->

            <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
            snackbarMessage }} </VSnackbar>

            <VCardText>
                <VForm ref="refVForm" v-model="isFormValid" @submit.prevent="onFormSubmit()" class="form-border">
                    <VRow>
                        <VCol cols="12">
                            <h5 class="mb-2">
                                {{ $t("add_client") }}
                            </h5>
                            <VCard border>
                                <VCardText>

                                    <VRow>
                                        <VCol cols="12" md="2">
                                            <div class="logo-upload">
                                                <!-- 👉 Avatar -->
                                                <div class="position-relative">
                                                    <!-- <VAvatar class="avatar-name" size="80"
                                                        :image="!is_image_change && props.moduleData.profile_img ? base_url + props.moduleData.profile_img : ((is_image_change) ? ProfileDataLocal.avatarImg : '')">
                                                        <span>{{(props.moduleData.company_name).slice(0, 2)}}</span>
                                                    </VAvatar> -->
                                                    <VAvatar class="avatar-name" size="80"
                                                        :image="isImageChange ? profileImageLocal as string : avatarImage">
                                                        <!-- <span>{{(props.moduleData.company_name).slice(0, 2)}}</span> -->
                                                    </VAvatar>
                                                    <div class="upload-icon">
                                                        <VIcon v-if="!props.moduleData.profile_img" icon="tabler-pencil"
                                                            @click="refInputEl?.click()" class="img-upload" size="20" />
                                                        <input ref="refInputEl" type="file" name="file"
                                                            accept=".jpeg,.png,.jpg,GIF" hidden @input="changeAvatar">
                                                        <VIcon v-if="props.moduleData.profile_img" icon="tabler-trash"
                                                            type="reset" @click="resetAvatar" class="img-upload-remove"
                                                            size="18" />
                                                    </div>
                                                    <!-- <small>{{ $t("img_upload_detail") }}</small> -->
                                                </div>
                                                <small>{{ $t("img_upload_detail") }}</small>
                                            </div>
                                        </VCol>
                                        <VCol cols="12" md="10">
                                            <VRow>
                                                <VCol cols="12" md="12">
                                                    <VTextField v-model="props.moduleData.company_name"
                                                        :label="$t('company_name') + '*'" :rules="[requiredValidator]"
                                                        :error-messages="errors.company_name" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VTextField v-model="props.moduleData.first_name"
                                                        :label="$t('first_name') + '*'" :rules="[requiredValidator]"
                                                        :error-messages="errors.first_name" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VTextField v-model="props.moduleData.last_name"
                                                        :label="$t('last_name')" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VTextField v-model="props.moduleData.email" :label="$t('email') + '*'"
                                                        :rules="[requiredValidator, emailValidator]"
                                                        :error-messages="errors.email" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="props.countriesList"
                                                        v-model="props.moduleData.country_id" :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('country') + '*'" item-value="id" clearable
                                                        :error-messages="errors.country_id" />
                                                </VCol>

                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="props.timezonesList"
                                                        v-model="props.moduleData.timezone" :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('timezone') + '*'" :item-title="item => item.title"
                                                        :item-value="item => item.timezone" clearable
                                                        :error-messages="errors.timezone" />
                                                </VCol>

                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="props.dateFormatsList"
                                                        v-model="props.moduleData.date_format" :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('date_format') + '*'" item-value="format_key" clearable
                                                        :error-messages="errors.date_format" />
                                                </VCol>

                                                <!-- <VCol cols="12" md="6">
                                                    <VSelect v-model="props.moduleData.status" :rules="[requiredValidator]"
                                                        :items="status" :label="$t('status') + '*'" />
                                                </VCol> -->
                                            </VRow>
                                        </VCol>
                                    </VRow>
                                </VCardText>
                            </VCard>
                        </VCol>
                    </VRow>

                    <VRow>
                        <VCol cols="12" class="text-right">
                            <VBtn :to="{ name: 'client-management-list' }" color="secondary mr-3">
                                {{ $t("can_btn") }}
                            </VBtn>
                            <VBtn type="submit" color="primary">
                                <ButtonLoader v-if="is_loading"></ButtonLoader>

                                {{ $t("sub_btn") }}
                            </VBtn>
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>
    </section>
</template>
