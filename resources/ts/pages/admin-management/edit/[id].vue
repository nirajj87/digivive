<script setup lang="ts">
    import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
    import AdminEditable from '../view/adminEditable.vue';
    import { useAdminStore } from './../view/adminModulesStore';
    import { useRoute } from 'vue-router';

    const adminStore = useAdminStore();
    const route = useRoute();
    const moduleData = ref({
        name: '',
        email:'',
        country_id:'',
        // state_id:'',
        // city_id:'',
        status:0,
        timezone_id:'',
        date_format_id:'',
        permission:'',
        id:''
    });
    const getUserDetails = (id:number) => {
        adminStore.fetchUserDetails(id)
          .then((response:any) =>{
            console.log(response.data);
           if(response.data.success) {
                moduleData.value = response.data.data;
                console.log('moduleData',moduleData.value);
           }
          })
          .catch((e) => {
           
          })
    }

    watchEffect(() => {
        let userId = Number(route.params.id) ?? 0;

        if(userId) {
            getUserDetails(userId); 
        }
})

const breadcrumbsData = ref({
        page_name: 'edit_user',
        name: 'edit_user',
    });

</script>

<template>
    <section>
		<breadcrumbs :breadcrumbs-data="breadcrumbsData" />
   
        <AdminEditable :module-data="moduleData" />
    </section>
</template>