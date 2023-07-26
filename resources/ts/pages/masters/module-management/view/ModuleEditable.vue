<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useModuleStore } from './ModuleStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { ModuleModel, ParentModel, RoleModel } from './type';

//getting router variable
const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')

const isFormValid = ref(false)
const router = useRouter();
const route = useRoute();

const isParent = ref(1);
const parentId = ref();
const childId = <any>ref();

const roleIdArr = ref([]);

const childModuleList = ref([{
    title: '',
    value: 0
}])

const items = [
    { title: 'active', value: 1 },
    { title: 'inactive', value: 0 },
]

// defining props type
interface Props {
    moduleData: ModuleModel,
    roleList: RoleModel[],
    parentList: ParentModel[],
}


// defining props
const props = defineProps<Props>();

const ModuleStore = useModuleStore();

const refForm = ref<VForm>();

const handleSubmit = () => {
    

    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            console.log('coming in before valid block due : ', valid);
            if (valid) {
                console.log('coming in after valid block');
                const moduleId: any = route.params.id ? route.params.id : 0;
                // updating Module
                if (moduleId) {
                    console.log('coming in edit funtion');

                    let updatedModule = {
                        'title': props.moduleData.title,
                        'status': props.moduleData.status,
                        'is_parent': isParent.value == 1 ? '0' : '1',
                        'parent_id': parentId.value,
                        'role': roleIdArr.value,
                        'icon': props.moduleData.icon,
                        // 'child_id' : isParent.value == 0 ?( childId.value == '' ? 0 : childId.value) : 0,
                        'child_id' : isParent.value == 0 ?( childModuleList.value.length == 0 ? 0 : childId.value) : 0,
                    };

                    ModuleStore.updateModule(moduleId, updatedModule)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-module-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {
                            console.log('Error occured in adding module : ', error);

                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = error.response ? error.response.data.data : 'internal-server-error';
                            snackbarClass.value = 'delete-snackbar'

                        })
                }
                //adding new Module
                else {

                    let newModule = {
                        ...props.moduleData,
                        'is_parent': isParent.value == 1 ? '0' : '1',
                        // 'parent_id': parentId.value,
                        'parent_id': props.parentList.length == 0 ? 0 : parentId.value,
                        // 'role': roleIdArr.value,
                        'role': `[${roleIdArr.value.toString()}]`,
                        'icon': props.moduleData.icon ? props.moduleData.icon  : ''
                    }

                    //👉 for adding child module
                    if (isParent.value == 0) {
                        newModule = {
                            ...newModule,
                            // 'child_id': childId.value == '' ? 0 : childId.value
                            'child_id': childModuleList.value.length == 0 ? 0 : childId.value
                        }
                    }

                    console.log('Coming Till Add API : ',newModule);
                    

                    ModuleStore.addModule(newModule)
                        .then((response: any) => {
                            console.log("Successfully Created ! ");

                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-module-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {
                            console.log('Internal Server Error : ',error.response);
                            
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = error.response ? error.response.data.data : 'internal-server-error';
                            snackbarClass.value = 'delete-snackbar'
                        })
                }

            }
        })

    }
}

//👉 checking parentId , if it changes then show child of that parent
watch(parentId, (newValue, oldValue) => {

    // 👉 if it is child module , then only fetch child of corresponding parent . 
    // Benefit is to stop unnecessarily fetching child module api , if it is parent module
    if (parentId.value && isParent.value == 0) {
        ModuleStore.getChildModuleList(newValue)
            .then((response: any) => {
                childModuleList.value = response.data.map((element: any) => {
                    return { title: element.title, 'value': element.id }
                });

                const selectedChild = childModuleList.value.find((child) => child.value == childId.value);

                // 👉 if parent module is changed , then bydefault its first child module should be shown in child dropdown .
                if (!selectedChild) {
                    childId.value = childModuleList.value[0] ? childModuleList.value[0].value : '';
                }

                // 👉 To properly set the order of child module in child dropdown .
                else {
                    const index = childModuleList.value.findIndex(item => item.value == props.moduleData.id)
                    // 👉 if it is not first module in list
                    if (index != 0) {
                        childId.value = childModuleList.value[index - 1].value;
                    }
                }

            })
            .catch((error) => {
                console.log('Some internal server error in fetching child of particular parent');
                isSnackbarVisibileDialog.value = true;
                snackbarMessage.value = 'internal-server-error';
                snackbarClass.value = 'delete-snackbar';
            });
    }
})

// 👉 This onMounted() is only for update module page
onMounted(() => {
    if (route.params.id) {

        // 👉 if it is parent module
        if (props.moduleData.parent_id == 0) {

            // 👉 this is parent module , then watch funtion won't  get called.
            isParent.value = 1;
            parentId.value = props.moduleData.id;

            const index = props.parentList.findIndex(item => item.value == props.moduleData.id)

            // 👉 if it is not first module in list
            if (index != 0) {
                parentId.value = props.parentList[index - 1].value;
            }

        }
        //👉 if it is child module
        else {

            isParent.value = 0;
            parentId.value = props.moduleData.parent_id;

            childId.value = props.moduleData.id;
        }

        roleIdArr.value = props.moduleData.role.map((element: any) => {
            return element.id;
        });
    }
});

</script>


<template>
    <section>
        <h5 v-show="!route.params.id" class="mb-4">{{ $t("add_module") }} </h5>
        <h5 v-show="route.params.id" class="mb-4">{{ $t("edit_module") }} </h5>

        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackbarMessage == 'internal-server-error' ? $t(snackbarMessage) : snackbarMessage }} </VSnackbar>

        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="handleSubmit()">
            <!-- <VForm ref="refForm" v-model="isFormValid" > -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.moduleData.title" :rules="[requiredValidator]"
                                        :label="$t('module_name')" />
                                </VCol>

                                <VCol cols="12" md="8">
                                    <div class="box-border">
                                        <VRadioGroup v-model="isParent" inline :disabled="route.params.id ? true : false">
                                            <VRadio :label="$t('parent_module')" :value="1" />
                                            <VRadio :label="$t('child_module')" :value="0" />
                                        </VRadioGroup>
                                    </div>
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VAutocomplete :rules="props.parentList.length == 0 ? [] : [requiredValidator]" v-model="parentId" :items="props.parentList"
                                        :label="isParent ? $t('parent_module_order') : $t('select_parent')"
                                        :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <VCol v-show="!isParent" cols="12" md="4">
                                    <VAutocomplete :rules="isParent == 1  || childModuleList.length == 0 ? [] : [requiredValidator]" v-model="childId" :items="childModuleList"
                                        :label="$t('child_module_order')" :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <!-- For Roles -->
                                <VCol cols="12" md="4">
                                    <VAutocomplete :rules="[requiredValidator]" :items="props.roleList" v-model="roleIdArr"
                                        :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                        :label="$t('roles')" item-value="value" clearable multiple>
                                        <template v-slot:selection="{ item, index }">
                                            <v-chip v-if="index < 2">
                                                <span>{{ item.title }}</span>
                                            </v-chip>
                                            <span v-if="index === 2" class="text-grey text-caption align-self-center">
                                                (+{{ props.roleList.length - 2 }} {{ $t('Others') }})
                                            </span>
                                        </template>
                                    </VAutocomplete>

                                </VCol>

                                <VCol cols="12" md="4">
                                    <!-- <VTextField v-model="props.moduleData.icon" :rules="[requiredValidator]" -->
                                    <VTextField v-model="props.moduleData.icon" 
                                        :label="$t('icon')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.moduleData.status"
                                        :items="items.map((item) => { return { 'title': $t(item.title), 'value': item.value } })"
                                        :label="$t('status')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" class="text-right">
                    <VBtn :to="{ name: 'masters-module-management' }" class="mr-3" color="secondary">
                        {{ $t("can_btn") }}
                    </VBtn>
                    <VBtn type="submit" @click="refForm?.validate()">
                        <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>

                        {{ $t("sub_btn") }}
                    </VBtn>
                </VCol>
            </VRow>
        </VForm>
    </section>
</template>

<style>
.box-border {
    border: 1px solid;
    border-color: #B1B0B5;
    border-radius: 6px;
    padding: 0 16px;
}
</style>