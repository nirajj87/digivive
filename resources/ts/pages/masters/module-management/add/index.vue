<script setup lang="ts">
import ModuleEditable from '../view/ModuleEditable.vue'
import { useModuleStore } from '../view/ModuleStore';
import { ModuleModel, RoleModel, ParentModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const ModuleStore = useModuleStore();


const moduleParams = ref<ModuleModel>({
    title: '',
})

const roleList = ref<RoleModel[]>([])
const parentList = ref<ParentModel[]>([])

const breadcrumbsData = ref({
    page_name: 'add_module',
    name: 'add_module',
});

onBeforeMount(() => {

    ModuleStore.getRolesList()
        .then((response: any) => {
            roleList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })
        })
        .catch((error) => {
            console.log("Some Internal Server error occured in fetching roles");
        })

    ModuleStore.getParentModuleList()
        .then((response: any) => {
            parentList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })
        })
        .catch((error) => {
            console.log("Some Internal Server error occured in fetching parent List");

        })
})

</script>

<template>
    <div>


        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <ModuleEditable :module-data="moduleParams" :parent-list="parentList" :role-list="roleList" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>

    </div>
</template>