<script setup lang="ts">

</script>

<template>
    <VRow>
        <VCol
        key="meta.title"
        cols="12"
        class="pt-0"
        >
            
            <VBreadcrumbs class="pl-0">
                <VBreadcrumbsItem class="pl-0"><a href="#">{{$t("home")}}</a></VBreadcrumbsItem>
                <VBreadcrumbsDivider>
                    <VIcon
                        size="20"
                        icon='tabler-chevron-right'
                    />
                </VBreadcrumbsDivider>
                <VBreadcrumbsItem>Edit Page Breadcrumbs</VBreadcrumbsItem>
            </VBreadcrumbs>
        </VCol>
    </VRow>
</template>