<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import AdminEditable from '../view/adminEditable.vue';
import { useAdminStore } from './../view/adminModulesStore';
import { userParams } from '../view/type'

const adminStore = useAdminStore();


// const countriesList = ref<[]>([]);
// const timezonesList = ref<[]>([]);
// const dateFormatsList = ref<[]>([]);
const managersList = ref<[]>([]);
const rolesList = ref<[]>([]);
const departmentList = ref<[]>([]);

const userData = JSON.parse(localStorage.getItem('userData') || 'null')
// const moduleData = ref({
//     first_name: '',
//     last_name: '',
//     email:'',
//     status:'',
//     role_id:'1',
//     date_format_id:'',
//     timezone_id:null,
//     permission:ref({})
// });
const moduleData = <userParams>ref({
});

// const adminStore = useAdminStore()
// const getPermissions = (user_type:number) => {
//     adminStore.getPermissions(user_type)
//       .then((response:any) =>{
//         console.log(response.data);
//        if(response.data.success) {
//             moduleData.value.permission = response.data.data;
//             console.log('moduleData',moduleData.value);
//        }
//       })
//       .catch((e) => {

//       })
// }

//     watchEffect(() => {
//         const user_type = 1; // 1 for admin 2 for client
//         getPermissions(user_type); 
// })




// const countriesFetch = () => {
//     adminStore.fetchCountryList()
//         .then((response: any) => {
//             // console.log(response.data);
//             if (response.success) {
//                 countriesList.value = response.data.map((element: any) => {
//                     return { 'title': element.title, 'value': element.id }
//                 });
//             }
//         })
//         .catch((e) => {
//             console.error(e)
//         })
// }

// const timezonesFetch = () => {
//     adminStore.fetchTimeZoneList()
//         .then((response: any) => {
//             // console.log(response.data);
//             if (response.success) {
//                 timezonesList.value = response.data.map((element: any) => {
//                     return { 'title': element.title, 'value': element.id }
//                 });
//             }
//         })
//         .catch((e) => {
//             console.error(e)
//         })
// }

// const dateFormatsFetch = () => {
//     adminStore.fetchDateFormatsList()
//         .then((response: any) => {
//             // console.log(response.data);
//             if (response.success) {
//                 dateFormatsList.value = response.data.map((element: any) => {
//                     return { 'title': element.title, 'value': element.format_key }
//                 });
//                 // console.log('Date Format : ',dateFormatsList.value);

//             }
//         })
//         .catch((e) => {
//             console.error(e)
//         })
// }

const getManagersList = () => {
    adminStore.fetchManagersList()
        .then((response: any) => {
            // console.log(response.data);
            if (response.success) {
                managersList.value = response.data.map((element: any) => {
                    return { 'title': element.name, 'value': element.id }
                });
            }
            console.log('managers list : ', managersList.value);

        })
        .catch((e) => {
            console.error(e)
        })
}

// const getRolesList = () => {
//     adminStore.getRolesList()
//         .then((response: any) => {
//             // console.log(response.data);
//             if (response.success) {
//                 rolesList.value = response.data.map((element: any) => {
//                     return { 'title': element.title, 'value': element.id }
//                 });
//             }
//             console.log('roles list : ', rolesList.value);

//         })
//         .catch((e) => {
//             console.error(e)
//         })
// }

const getDepartmentList = () => {
    adminStore.getDepartmentList()
        .then((response: any) => {
            // console.log(response.data);
            if (response.success) {
                departmentList.value = response.data.map((element: any) => {
                    return { 'title': element.title, 'value': element.slug }
                });
            }

        })
        .catch((e) => {
            console.error(e)
        })
}

onBeforeMount(() => {
    // countriesFetch();
    // timezonesFetch();
    // dateFormatsFetch();
    getManagersList();
    // getRolesList();
    getDepartmentList();
})



const breadcrumbsData = ref({
    page_name: 'add_user',
    name: 'add_user',
});
</script>

<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <!-- <AdminEditable :module-data="moduleData" :countries-list="countriesList" :timezones-list="timezonesList" :date-formats-list="dateFormatsList" :managers-list="managersList" :rolesList="rolesList" :department-list="departmentList" /> -->
        <AdminEditable :module-data="moduleData"  :managers-list="managersList"  :department-list="departmentList" />
    </section>
</template>