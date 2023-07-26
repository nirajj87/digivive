<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useModuleStore } from '../view/ModuleStore';
import { ModuleModel } from '../view/type'
import router from '@/router';

const moduleStore = useModuleStore()
const route = useRoute();

const isLoading = ref(true);
const isSnackbarVisibileDialog = ref(false);
const snackbarClass = ref('');
const snackBarMessage = ref('');


const moduleData = ref<ModuleModel>({
    title: ''
});

const parentName = ref('');


onBeforeMount(() => {

    let moduleId = Number(route.params.id) ?? 0;

    moduleStore.getDetailsModule(moduleId)
        .then((response: any) => {
            moduleData.value = response.data;
            console.log('Inside Parent Module')
            console.log('Parent Data is : ',moduleData.value);
            

            // if it is child module
            if (moduleData.value.parent_id  != 0) {
                console.log('Inside Child Module')
                moduleStore.getDetailsModule(moduleData.value.parent_id)
                    .then((response: any) => {
                        console.log('Inside Then Block Child Module')
                        parentName.value = response.data.title;
                        isLoading.value = false;
                    })
                    .catch((error) => {
                        console.log("Some Intenal Server in fetching parent detail");
                        isSnackbarVisibileDialog.value = true;
                        snackbarClass.value = 'delete-snackbar';
                        snackBarMessage.value = 'internal-server-error';
                    })
            }
            else{
                isLoading.value = false;                
            }
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");
            isSnackbarVisibileDialog.value = true;
            snackbarClass.value = 'delete-snackbar';
            snackBarMessage.value = 'internal-server-error';
            setTimeout(() => {
                router.push({ name: 'masters-module-management' })
            }, 2000);
        })

})


// 👉 getting status word
const statusWord = (stat: any) => {
    if (stat === 0)
        return 'inactive'
    if (stat === 1)
        return 'active'
    if (stat === 2)
        return 'blocked'
    return 'primary'
}

const breadcrumbsData = ref({
    page_name: 'module_management',
    name: 'module_management',
});

</script>


<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />


        <!-- SnackBar -->
        <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
            $t(snackBarMessage) }}
        </VSnackbar>

        <VCard>
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("module") }}</h5>
                        <VCard border>
                            <VCardText>
                                <PageLoader v-if="isLoading"></PageLoader>
                                <VRow v-if="!isLoading">
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("module_name") }}</p>
                                        <h5>{{ moduleData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("roles") }}</p>
                                        <h5>
                                            <label v-for="role in moduleData.role"> {{ role.title }}<br />
                                            </label>
                                        </h5>
                                    </VCol>
                                    <VCol v-show="parentName" cols="12" md="6">
                                        <p class="mb-0">{{ $t("parent_module") }}</p>
                                        <h5>{{ parentName }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("icon") }}</p>
                                        <h5>{{ moduleData.icon ? moduleData.icon : '-' }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ $t(statusWord(moduleData.status)) }} </h5>

                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>
                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-module-management' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>
