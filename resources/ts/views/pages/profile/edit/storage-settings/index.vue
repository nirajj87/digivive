<script lang="ts" setup>
 import { userAdvanceSettingStore } from '@/views/pages/profile/advanceSettingStore';
 import type {StorageSettingDetails} from './../../types';
 import type { VForm } from 'vuetify/components';
 const refVForm = ref<VForm>();
    const aws = ref(true)
    const wowza = ref(false);
    const route = useRoute()
    const userId = Number(route.params.userid) ?? 0;
    const StorageItems = ["AWS S3", "Wowza"];

    const selected_storage_type = ref([]);

    const useAdvanceSettingStore = userAdvanceSettingStore();
    const storage_setting_data:any =  ref<StorageSettingDetails>();
    const getDetails = () => {
        const data = {
                user_id:userId
            }
        useAdvanceSettingStore.getStorageSettingDetails(data).then(response => {
            console.log(' response.data', response.data);
            storage_setting_data.value = response.data.data;
            selected_storage_type.value = response.data.data.storage_type;
           
            selected_storage_type.value =  selected_storage_type.value.filter((x:any) => {
                return x.is_selected == 1
            })
            console.log(selected_storage_type.value);
            console.log(' storage_setting_data', storage_setting_data.value);
        })
        }

        watchEffect(() => {
            getDetails(); 
    });

    const onSubmit = () => {
        console.log('data',storage_setting_data);
        console.log('selected_storage_type',selected_storage_type);
        let data:any = [];
        selected_storage_type.value.forEach((cdn:any) => {
            const storage_type:any = cdn.slug ? cdn.slug:cdn;
            if(storage_type == 'aws') {
                let storage = storage_setting_data.value['aws'];
                storage.storage_type = storage_type;
                data.push(storage);
                console.log(storage_setting_data.value['aws']); 
            }else if(storage_type == 'wowza') {
                let storage = storage_setting_data.value['wowza'];
                storage.storage_type = storage_type;
                data.push(storage);
                console.log(storage_setting_data.value['wowza']);  
            }
        });
        console.log('data',data);
        
        // return;
        if(userId) {
            useAdvanceSettingStore.updateStorageSettingDetails(data)
            .then((response:any) =>{
            })
            .catch((e) => {
                let res = e.response.data.data;
            })
        }
   
    }

    const onFormSubmit = () => {
            console.log( refVForm.value);
            
            // refVForm.value?.validate()
            // .then(({ valid: isValid }) => {
            //     console.log(isValid);
                
            // if (isValid)
                 onSubmit()
            // })
        }
  
</script>
<template>
    <section>
        <VCardText class="pt-0">
            <VForm
            ref="refForm"
            @submit.prevent="onFormSubmit"
            class="form-border"
        >
            <VRow v-for="(value, key) in storage_setting_data">
                <VCol cols="12" v-if="key == 'storage_type'">
                    <h5 class="mb-2">{{ $t("storage_settings") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <VAutocomplete
                                    v-model="selected_storage_type"
                                        :items="storage_setting_data?.storage_type"
                                        :label="$t('select_storage') + ' *'"
                                        item-value="slug"
                                        multiple
                                    />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12"  v-if="key == String('aws')">
                    <h5 class="mb-2">{{ $t(key) }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].key" :label="$t('key') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].secret" :label="$t('secret') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].region" :label="$t('region') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].default_acl" :label="$t('default_acl') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].bucket" :label="$t('content_bucket') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].backup" :label="$t('backup_bucket') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextField v-model="storage_setting_data[key].cdn_url" :label="$t('path_url') + ' * ' " />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12" v-if="key == String('wowza')">
                    <h5 class="mb-2">{{ $t(key) }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].host" :label="$t('host') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].user_name" :label="$t('user_name') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].password" type="password" :label="$t('password') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="storage_setting_data[key].content_path" :label="$t('content_path') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextField v-model="storage_setting_data[key].wowza_path_format" :label="$t('path_format') + ' * ' " />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                
            </VRow>
            <VRow>
                <VCol cols="12" class="text-right">
                    <VBtn
                        variant="outlined"
                        color="secondary mr-3"
                        >
                        {{$t("can_btn")}}
                    </VBtn>
                    <VBtn
                    color="primary mr-3"
                    :to="{name:'edit-profile-tab-uid', params: {tab:'permissions', uid: userId} }"

                >
                    {{$t("previous")}}
                </VBtn>
                    <VBtn
                        type="submit"
                        color="primary"
                    >
                        {{$t("save")}}
                    </VBtn>
                </VCol>
            </VRow>
            </VForm>
        </VCardText>
    </section>
</template>