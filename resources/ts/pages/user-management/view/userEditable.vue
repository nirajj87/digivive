<script lang="ts" setup>
import { VAutocomplete, type VForm } from 'vuetify/components'
import avatar from '@images/avatars/a2.jpg';
import { UserData } from '../view/type'
import { useClientUserStore } from '../view/userStore'
import router from '@/router'
import { useRoute } from 'vue-router';
const route = useRoute();

const clientUserStore = useClientUserStore();

import 'flag-icons/css/flag-icons.min.css';
import 'v-phone-input/dist/v-phone-input.css';
import { emailValidator, requiredValidator, fileSizeValidator } from '@/@core/utils/validators';

const refVForm = ref<VForm>();

const refInputEl = ref<HTMLElement>();

const profileImage = ref<File | null | string>(null)
const profileImageLocal = ref<File | null | string>(null)
const isImageChange = ref(false);


const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const errors = ref<Record<string, string | undefined>>({
    first_name: undefined,
    last_name: undefined,
    email: undefined,
    phone_number: undefined,
    role_id: undefined,
    manager: undefined,
    department: undefined,
    designation: undefined,
    profile_image: undefined,
})


interface Props {
    moduleData: UserData
    departmentList: any,
    managersList: any
}

const props = defineProps<Props>();


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
}

const onSubmit = () => {

    const clientId = route.params.id ? route.params.id : 0;

    console.log("New Client User is : ", { ...props.moduleData, client_id: clientId });
    setTimeout(() => {
                router.push({
                    name: 'user-management-list-id', params: { 'id': clientId }
                })
            }, 3000);

    // clientUserStore.addClientUser({ ...props.moduleData, client_id: clientId, 'manager': `[${props.moduleData.manager?.toString()}]` })
    //     .then((response: any) => {

    //         isSnackbarVisibileDialog.value = true;
    //         snackbarClass.value = 'success-snackbar';
    //         snackBarMessage.value = response.message;

    //         setTimeout(() => {
    //             router.push({
    //                 name: 'user-management-list-id', params: { 'id': clientId }
    //             })
    //         }, 3000);
    //     })
    //     .catch((e) => {
    //         let res = e.response.data.data;

    //         for (const key in res) {
    //             for (const e_key in errors.value) {
    //                 if (key == e_key) {
    //                     errors.value[e_key] = res[key][0];
    //                     console.log(errors.value[e_key]);
    //                     snackbarClass.value = 'delete-snackbar';
    //                     isSnackbarVisibileDialog.value = true;
    //                     //snackbarMessage.value = res[key][0];
    //                     snackBarMessage.value = res.message
    //                     break;
    //                 }
    //             }
    //         }
    //     })
}


const onFormSubmit = () => {
    refVForm.value?.validate()
        .then(({ valid }) => {
            if (valid) {
                onSubmit();
            }
        })
}

</script>

<template>
    <div>

        <section>


            <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
                snackBarMessage }} </VSnackbar>

            <VCard>
                <VCardText>
                    <VForm ref="refVForm" class="form-border" @submit.prevent="onFormSubmit">
                        <VRow>
                            <VCol cols="12">
                                <h5 class="mb-2">
                                    {{ $t("add_user") }}
                                </h5>
                                <VCard border>
                                    <VCardText>

                                        <VRow>
                                            <VCol cols="12" md="2">
                                                <div class="logo-upload">
                                                    <div class="position-relative">
                                                        <VAvatar class="avatar-name" size="80"
                                                            :image="isImageChange ? profileImageLocal as string : avatar">
                                                        </VAvatar>
                                                        <div class="upload-icon">
                                                            <VIcon v-if="!props.moduleData.profile_img" icon="tabler-pencil"
                                                                @click="refInputEl?.click()" class="img-upload" size="20" />
                                                            <input ref="refInputEl" type="file" name="file"
                                                                accept=".jpeg,.png,.jpg,GIF,.webp" hidden
                                                                @input="changeAvatar">
                                                            <!-- <VImg ref="refInputEl" type="file" name="file"  :rules="[fileSizeValidator]"
                                                                accept=".jpeg,.png,.jpg,GIF,.webp" hidden
                                                                @input="changeAvatar" /> -->
                                                            <VIcon v-if="props.moduleData.profile_img" icon="tabler-trash"
                                                                type="reset" @click="resetAvatar" class="img-upload-remove"
                                                                size="18" />
                                                        </div>
                                                    </div>
                                                    <small>{{ $t("img_upload_detail") }}</small>
                                                </div>
                                            </VCol>
                                            <VCol cols="12" md="10">
                                                <VRow>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.first_name"
                                                            :rules="[requiredValidator]" :label="$t('first_name') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.last_name"
                                                            :rules="[requiredValidator]" :label="$t('last_name') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.email"
                                                            :rules="[requiredValidator, emailValidator]"
                                                            :label="$t('email') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <v-phone-input v-model="props.moduleData.phone_number"
                                                            :rules="[requiredValidator]">
                                                            <template #country-icon="{ country, decorative }">
                                                                <img
                                                                    :src="`http://localhost:5173/node_modules/flag-icons/flags/4x3/${country.iso2}.svg`">
                                                                <span>+{{ country.dialCode }}</span>
                                                            </template>
                                                        </v-phone-input>
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VAutocomplete v-model="props.moduleData.department"
                                                            :rules="[requiredValidator]" :items="props.departmentList"
                                                            :item-value="'value'" :menu-props="{ maxHeight: '300' }"
                                                            :label="$t('department') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.designation"
                                                            :rules="[requiredValidator]" :label="$t('designation') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VAutocomplete :label="$t('account_manager') + '*'"
                                                            v-model="props.moduleData.manager" :items="props.managersList"
                                                            :rules="[requiredValidator]" multiple
                                                            :menu-props="{ maxHeight: '300' }" :item-value="'value'">
                                                            <template v-slot:selection="{ item, index }">
                                                                <v-chip v-if="index < 2">
                                                                    <span>{{ item.title }}</span>
                                                                </v-chip>
                                                                <span v-if="index === 2"
                                                                    class="text-grey text-caption align-self-center">
                                                                    (+{{ props.managersList.length - 2 }} {{ $t('Others')
                                                                    }})
                                                                </span>
                                                            </template>
                                                        </VAutocomplete>
                                                    </VCol>

                                                    <VCol cols="12" md="6">
                                                        <VSelect v-model="props.moduleData.status" :item-value="'value'"
                                                            :rules="[requiredValidator]"
                                                            :items="[{ title: $t('active'), value: '1' }, { title: $t('inactive'), value: '0' }]"
                                                            :label="$t('status') + '*'" />
                                                    </VCol>
                                                </VRow>
                                            </VCol>
                                        </VRow>
                                    </VCardText>
                                </VCard>
                            </VCol>
                        </VRow>

                        <VRow>
                            <VCol cols="12 text-right">
                                <VBtn variant="outlined" color="secondary mr-3">
                                    {{ $t("can_btn") }}
                                </VBtn>

                                <VBtn type="submit" color="primary">
                                    <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
                                    {{ $t("submit_btn") }}
                                </VBtn>
                            </VCol>
                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </section>


</div></template>