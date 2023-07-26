<script setup lang="ts">
import ModuleEditable from '../view/ModuleEditable.vue'
import { useModuleStore } from '../view/ModuleStore';
import { useRoute, useRouter } from 'vue-router';
import { ModuleModel, RoleModel, ParentModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const moduleStore = useModuleStore()
const route = useRoute();
const router = useRouter();

const moduleData = ref<ModuleModel>({
    // title: ''
})

const isDataReady = ref(false);

const parentList = ref<ParentModel[]>([]);
const roleList = ref<RoleModel[]>([]);

onBeforeMount(() => {
    console.log("Inside On Before Mount Block");

    let moduleId = Number(route.params.id) ?? 0;

    moduleStore.getDetailsModule(moduleId)
        .then((response: any) => {
            moduleData.value = response.data;
            // console.log("Getting response from before mount Module Data : ", moduleData.value);
            // isDataReady.value = true;

            return moduleStore.getRolesList();
        })
        .then((response: any) => {
            roleList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })

            return moduleStore.getParentModuleList()
        })
        .then((response: any) => {
            parentList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })
            // console.log('Parent List : ', parentList.value);
            isDataReady.value = true;
        })
        .catch((e) => {
            console.log("Some internal server error occured");
            router.push({ name: 'masters-content-module' });
        })

})


const breadcrumbsData = ref({
    page_name: 'edit_module',
    name: 'edit_module',
});

</script>

<template>
    <div>
        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <PageLoader v-if="!isDataReady"></PageLoader>

        <VCard v-if="isDataReady">
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <ModuleEditable :module-data="moduleData" :parent-list="parentList" :role-list="roleList" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>