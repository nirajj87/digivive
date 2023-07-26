<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import UsertEditable from '../view/userEditable.vue';




const breadcrumbsData = ref({
    page_name: 'add_user',
    name: 'add_user',
});
</script>

<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <UsertEditable />
    </section>
</template>