<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import UsertEditable from '../view/userEditable.vue';
import { useClientUserStore } from '../view/userStore';
// import { UserData } from '../view/type';
import { UserData } from '../view/type'
import { useRoute } from 'vue-router';


const route = useRoute();
const clientId = route.params.id ? route.params.id : 0 ;

const clientUserStore = useClientUserStore();

const departmentList = ref<[]>([]);
const managersList = ref<[]>([]);
const moduleData = ref<UserData>({});
// const moduleData = ref();

const fetchDepartmentList = () => {
    clientUserStore.getDepartmentList()
        .then((response: any) => {
            departmentList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.slug }
            })
        }).catch((error: any) => {
            console.log('Internal Server Errror : ', error);

        })
}

const fetchManagersList = () => {
    clientUserStore.getManagersList(clientId)
        .then((response: any) => {
            managersList.value = response.data.map((element: any) => {
                return { 'title': element.name, 'value': element.id }
            })
        }).catch((error: any) => {
            console.log('Internal Server Errror : ', error);

        })
}

onBeforeMount(() => {
    fetchDepartmentList();
    fetchManagersList();
})

const breadcrumbsData = ref({
    page_name: 'add_user',
    name: 'add_user',
});
</script>

<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <UsertEditable :module-data="moduleData" :department-list="departmentList" :managers-list="managersList" />
    </section>
</template>