<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import ClientEditable from '../view/clientEditable.vue';
import { useClientStore } from './../view/clientModulesStore';
import { useRoute } from 'vue-router';

const clientStore = useClientStore();

const route = useRoute();

const moduleData = ref({
    company_name: '',
    email: '',
    country_id: null,
    state_id: null,
    status: null,
    city_id: null,
    role_id: '2',
    timezone_id: null,
    date_format_id: null,
    profile_img: null,
    timezone: '',
    title: '',
    format_key: '',
    permission: ref({})
});

const getUserDetails = (id: number) => {
    clientStore.fetchUserDetails(id)
        .then((response: any) => {
            console.log(response.data);
            if (response.data.success) {
                moduleData.value = response.data.data;
                moduleData.value.role_id = moduleData.value.role_id
                console.log('moduleData', moduleData.value);
                // console.log('timezone',moduleData.value.timezone_id.id);
            }
        })
        .catch((e) => {
            console.error(e);
        })
}

watchEffect(() => {
    let userId = Number(route.params.id) ?? 0;

    if (userId) {
        getUserDetails(userId);
    }
})

const breadcrumbsData = ref({
    page_name: 'edit_user',
    name: 'edit_user',
});

</script>

<template>
    <div>
        <section>
            <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

            <ClientEditable :module-data="moduleData" />
        </section>
    </div>
</template>