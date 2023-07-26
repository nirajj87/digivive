<script setup lang="ts">
import * as demoCode from '@/views/demos/forms/form-validation/demoCodeFormValidation';
import AppModuleEditable from '../view/list/AppModuleEditable.vue'
</script>

<template>
    <VCard class="mb-5">
        
            <VRow>
                <VCol
                key="meta.title"
                cols="12"
                sm="6"
                lg="12"
                >
                <VCard>
                    <VCardText class="d-flex justify-space-between">
                        <div>
                            <div class="d-flex align-center gap-2 my-1">
                                <span class="font-weight-semibold">Role and Permission Management</span>
                            </div>
                            <span>
                                <VBreadcrumbs class="pl-0">
                                    <VBreadcrumbsItem class="pl-0"><a href="#">{{$t("home")}}</a></VBreadcrumbsItem>
                                    <VBreadcrumbsDivider>
                                        <VIcon
                                            size="20"
                                            icon='tabler-chevron-left'
                                        />
                                    </VBreadcrumbsDivider>
                                    <VBreadcrumbsItem>{{$t("role_and_permission")}}</VBreadcrumbsItem>
                                </VBreadcrumbs>
                            </span>
                        </div>
                    </VCardText>
                </VCard>
            </VCol>
            </VRow>
        
    </VCard>
    <VCard>
        <VCardText>
            <VRow>
                <VCol cols="12" md="12">
                    <AppModuleEditable />
                </VCol>
            </VRow>
        </VCardText>
    </VCard>
</template>