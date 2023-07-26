<script lang="ts" setup>
import avatarImage from '@images/avatars/a2.jpg'
import type { VForm } from 'vuetify/components';
import { useAdminStore } from './../view/adminModulesStore';
import type { userParams, permissionParams } from '../view/type';
import { emailValidator, fileSizeValidator, requiredValidator } from '@validators';
import router from '@/router'
import { useRoute } from 'vue-router';
import 'flag-icons/css/flag-icons.min.css';
import 'v-phone-input/dist/v-phone-input.css';
import { VPhoneInput } from 'v-phone-input';
const adminStore = useAdminStore()
const route = useRoute();

const refForm = ref<VForm>();
const refInputEl = ref<HTMLElement>()
interface Props {
    moduleData: userParams,
    managersList: any,
    departmentList: any,
}

const props = defineProps<Props>();

const isSnackbarVisible = ref(false);

const snackbarMessage = ref('');
const class_name = ref('');
const profileImageLocal = ref<File | null | string>(null)
const isImageChange = ref(false);
const isErrorGot = ref(false);

const isFormValid = ref(false)
const message = ref('');

const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    slug: undefined,
    is_parent: undefined,
    email: undefined,
    phone_number: undefined,
    role_id: undefined,
    manager: undefined,
    department: undefined,
    designation: undefined,
    profile_image: undefined,
})
const onSubmit = () => {
    // console.log("Module Data=======>",props.moduleData);

    let userId = Number(route.params.id) ?? 0;
    if (userId) {
        props.moduleData.id = userId;
        props.moduleData.timezone_id = props.moduleData.timezone_id.constructor === Object ?
            props.moduleData.timezone_id.id : props.moduleData.timezone_id;
        props.moduleData.country_id = props.moduleData.country_id.constructor === Object ?
            props.moduleData.country_id.id : props.moduleData.country_id;
        props.moduleData.date_format_id = props.moduleData.date_format_id.constructor === Object ?
            props.moduleData.date_format_id.id : props.moduleData.date_format_id;
        adminStore.editUser(props.moduleData)
            .then((response: any) => {
                message.value = response.data.message;
                isSnackbarVisible.value = true;
                snackbarMessage.value = message.value;
                class_name.value = 'success-snackbar';
                setTimeout(() => {
                    router.push({
                        name: "admin-management-list"
                    })
                }, 1000);
            })
            .catch((e) => {
                if (e.response.data.message) {
                    message.value = e.response.data.message;

                }
                const { errors: formErrors } = e.response.data;
                errors.value = formErrors;
            })
    }
    else {
        console.log('adding admin');

        console.log('New Admin Data : ', props.moduleData);
        adminStore.addUser({...props.moduleData , 'manager' : `[${props.moduleData.manager.toString()}]`})
            .then((response: any) => {
                console.log('Response is : ', response);

                message.value = response.message;
                isSnackbarVisible.value = true;
                snackbarMessage.value = response.message;
                class_name.value = 'success-snackbar';
                setTimeout(() => {
                    router.push({
                        name: "admin-management-list"
                    })
                }, 2000);


            })
            .catch((error) => {
                console.log('Catch Error : ', error)

                let res = error.response.data.data;

                for (const key in res) {
                    for (const e_key in errors.value) {
                        if (key == e_key) {
                            errors.value[e_key] = res[key][0];
                            class_name.value = 'delete-snackbar';
                            isSnackbarVisible.value = true;
                            snackbarMessage.value = res[key][0];
                            isErrorGot.value = true;
                            break;
                        }

                    }
                }

                if (isErrorGot.value == false) {
                    isSnackbarVisible.value = true;
                    snackbarMessage.value = 'internal-server-error';
                    class_name.value = 'delete-snackbar';
                }
            })
    }
}

const onAdminFormSubmit = () => {

    if (refForm.value) {
        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                onSubmit();
            }
            else {
                console.log('Not Filled all box');
            }
        })
    }
}

const handleImageChange = (file: Event) => {

    // console.log('coming in handle image change');

    const fileReader = new FileReader();
    const { files } = file.target as HTMLInputElement;
    isImageChange.value = true;
    props.moduleData.profile_image = files ? files[0] : null;

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

const removeImage = () => {
    isImageChange.value = false;
    profileImageLocal.value = null;
    props.moduleData.profile_image = null;
}


</script>

<template >
    <div>

        <section>


            <!-- SnackBar -->
            <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
                {{ snackbarMessage == 'internal-server-error' ? $t(snackbarMessage) : snackbarMessage }}
            </VSnackbar>

            <VCard>
                <VCardText>
                    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onAdminFormSubmit" class="form-border">
                        <VRow>
                            <VCol cols="12">
                                <h5 class="mb-2">{{ $t("add_user") }}</h5>
                                <VCard border class="mb-5">
                                    <VCardText>
                                        <VRow>
                                            <VCol cols="12" md="2">
                                                <div class="logo-upload">


                                                    <!-- 👉 Avatar -->
                                                    <div class="position-relative">
                                                        <VAvatar class="avatar-name" size="80"
                                                            :image="isImageChange ? profileImageLocal as string : avatarImage">
                                                        </VAvatar>


                                                        <div class="upload-icon">
                                                            <VIcon v-if="!isImageChange" icon="tabler-pencil"
                                                                class="img-upload" size="20"
                                                                @click="refInputEl?.click();" />
                                                            <input ref="refInputEl" type="file" name="file"
                                                                accept=".jpeg,.png,.jpg,.webp" hidden
                                                                @change="handleImageChange">

                                                            <!-- <VImg class="cheque_img" ref="refInputEl" type="file" name="file"  :rules="[requiredValidator,fileSizeValidator]"
                                                                accept=".jpeg,.png,.jpg,.webp" hidden
                                                                @change="handleImageChange">
                                                            </VImg> -->
                                                            <VIcon v-if="isImageChange" icon="tabler-trash" type="reset"
                                                                class="img-upload-remove" size="18" @click="removeImage" />
                                                        </div>
                                                    </div>



                                                    <small>{{ $t("img_upload_detail") }}</small>
                                                </div>
                                            </VCol>
                                            <VCol cols="10">
                                                <!-- old form start -->
                                                <!-- <VRow>
                                                <VCol cols="12" md="6">
                                                    <VTextField v-model="props.moduleData.name" :label="$t('name') + '*'"
                                                        :rules="[requiredValidator]" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VTextField v-model="props.moduleData.email" :label="$t('email') + '*'"
                                                        :rules="[requiredValidator, emailValidator]" />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="countriesList" v-model="props.moduleData.country_id"
                                                        :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('country') + '*'" item-value="id" clearable />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="timezonesList" v-model="props.moduleData.timezone_id"
                                                        :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('timezone') + '*'" item-value="id" clearable />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VAutocomplete :items="dateFormatsList"
                                                        v-model="props.moduleData.date_format_id" :rules="[requiredValidator]"
                                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                        :label="$t('date_format') + '*'" item-value="id" clearable />
                                                </VCol>
                                                <VCol cols="12" md="6">
                                                    <VSelect v-model="props.moduleData.status" :rules="[requiredValidator]"
                                                        :items="status" :label="$t('status') + '*'" />
                                                </VCol>
                                            </VRow> -->
                                                <!-- old form end -->
                                                <VRow>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.first_name"
                                                            :label="$t('first_name') + '*'" :rules="[requiredValidator]" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.last_name"
                                                            :label="$t('last_name') + '*'" :rules="[requiredValidator]" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.email"
                                                            :label="$t('email') + '*'" :rules="[requiredValidator , emailValidator]" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <v-phone-input v-model="props.moduleData.phone_number">
                                                            <template #country-icon="{ country, decorative }">
                                                                <img
                                                                    :src="`http://localhost:5173/node_modules/flag-icons/flags/4x3/${country.iso2}.svg`">
                                                                <span>+{{ country.dialCode }}</span>
                                                            </template>
                                                        </v-phone-input>
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VAutocomplete :items="props.departmentList"
                                                            v-model="props.moduleData.department"
                                                            :menu-props="{ maxHeight: '300' }"
                                                            :label="$t('department') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VTextField v-model="props.moduleData.designation"
                                                            :rules="[requiredValidator]" :label="$t('designation') + '*'" />
                                                    </VCol>
                                                    <VCol cols="12" md="6">
                                                        <VAutocomplete :items="props.managersList" item-value="value"
                                                            v-model="props.moduleData.manager" :rules="[requiredValidator]"
                                                            :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                                            :label="$t('account_manager') + '*'" clearable multiple>
                                                            <template v-slot:selection="{ item, index }">
                                                                <v-chip v-if="index < 2">
                                                                    <span>{{ item.title }}</span>
                                                                </v-chip>
                                                                <span v-if="index === 2"
                                                                    class="text-grey text-caption align-self-center">
                                                                    (+{{ managersList.length - 2 }} others)
                                                                </span>
                                                            </template>
                                                        </VAutocomplete>
                                                    </VCol>

                                                    <VCol cols="12" md="6">
                                                        <VSelect v-model="props.moduleData.status"
                                                            :rules="[requiredValidator]"
                                                            :items="[{ 'title': $t('active'), 'value': '1' }, { 'title': $t('inactive'), 'value': '0' }]"
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
                            <VCol cols="12" class="text-right">
                                <VBtn :to="{ name: 'admin-management-list' }" color="secondary mr-3">
                                    {{ $t("can_btn") }}
                                </VBtn>
                                <VBtn type="submit" color="primary">
                                    <ButtonLoader v-show="isSnackbarVisible"></ButtonLoader>
                                    {{ $t("sub_btn") }}
                                </VBtn>
                            </VCol>
                        </VRow>

                    </VForm>
                </VCardText>
            </VCard>

        </section>

</div></template>