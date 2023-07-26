<script setup lang="ts">

import type {BreadcrumbsParams} from './types'
interface Props {
    breadcrumbsData:BreadcrumbsParams,
        // profileData:userParams
    }
const props  = defineProps<Props>();


</script>

<template>
    <VRow>
        <VCol
        key="meta.title"
        cols="12"
        class="pt-0"
        >
            <VBreadcrumbs class="pl-0">
                <VBreadcrumbsItem class="pl-0" :to="{name:'dashboard'}"><a href="#" >{{$t("home")}}</a></VBreadcrumbsItem>
                <VBreadcrumbsDivider>
                    <VIcon
                        size="20"
                        icon='tabler-chevron-right'
                    />
                </VBreadcrumbsDivider>
                <VBreadcrumbsItem>{{$t(props.breadcrumbsData.page_name)}}</VBreadcrumbsItem>
            </VBreadcrumbs>
        </VCol>
    </VRow>
</template>