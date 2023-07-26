<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue';
import { useModuleStore } from './view/ModuleStore';
import type { ModuleModel } from './view/type'
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import { useI18n } from 'vue-i18n';

const { t } = useI18n(); 
// 👉 Store

const moduleStore = useModuleStore();

const rowPerPage = ref(10)
const searchQuery = ref('')
const currentPage = ref(1)
const totalPage = ref(1)
const totalModule = ref(0);

const isSnackbarVisibileDialog = ref(false);
const snackbarClass = ref('')
const snackBarMessage = ref('');

const isConfirmDialogOpen = ref(false)
const expectedValue = ref('');

const deletedModuleID = ref();
const isLoading = ref(false);



const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

// 👉 getting module list variable
const moduleList = ref<ModuleModel[]>([]);

// 👉 fetching module and watching search query and pagination variable .
const fetchModules = () => {
    isLoading.value = true;
    
    moduleStore.fetchAllModule({
        'search': searchQuery.value,
        'perPage': rowPerPage.value,
        'page': currentPage.value,
        'sortBy': sortBy.value,
        'sortDirection': sortDirection.value

    }).then((response: any) => {
        isLoading.value = false;
        moduleList.value = response.data;
        totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
        totalModule.value = response.pagination.total;

    }).catch(error => {
        console.error(error)
        isSnackbarVisibileDialog.value = true;
        snackBarMessage.value = 'internal-server-error';
        snackbarClass.value = 'delete-snackbar';
    })
}

watchEffect(fetchModules);



// 👉 getting status word
const statusWord = (stat: any) => {
    if (stat === '0')
        return 'inactive'
    if (stat === '1')
        return 'active'
    if (stat === '2')
        return 'blocked'
    return 'primary'
}

// 👉 Computing pagination data
const paginationData = computed(() => {
    const firstIndex = moduleList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = moduleList.value.length + ((currentPage.value - 1) * rowPerPage.value)

    return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalModule.value })
})


const handleDelete = () => {

    moduleStore.deleteModule(deletedModuleID.value)
    .then((response: any) => {
        isSnackbarVisibileDialog.value = true;
        snackBarMessage.value = response.message;
        snackbarClass.value = 'success-snackbar';

        //for reload
        fetchModules();
    }).catch(error => {
        console.error('Some Internal Server Error Occured');
        isSnackbarVisibileDialog.value = true;
        snackBarMessage.value = 'internal-server-error';
        snackbarClass.value = 'delete-snackbar';
    })
}

// 👉 Deleting Module
const openConfirmDialog = (module: any) => {
    deletedModuleID.value = module.id;
    expectedValue.value = module.title;
    
    // 👉 setting dialogue box variable
    isConfirmDialogOpen.value = true;
}



const breadcrumbsData = ref({
    page_name: 'module_management',
    name: 'module_management',
});


</script>

<template>
    <section>
        
        <!-- SnackBar -->
        <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
            $t(snackBarMessage)}}
        </VSnackbar>

        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VRow>
            <VCol cols="12">
                <VCard>
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="9">
                                <VRow>
                                    <VCol cols="12" md="3">
                                        <div class="d-flex align-center">
                                            <span class="text-no-wrap me-3">{{ $t('show') }}:</span>
                                            <VSelect v-model="rowPerPage" density="compact" :items="[10, 20, 30, 40, 50]" />
                                        </div>
                                    </VCol>
                                    <VCol cols="12" md="3">
                                        <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-module-management-add' }">
                                            {{ $t("add") }}
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCol>


                            <VCol cols="12" md="3">
                                <VTextField v-model="searchQuery" :label="$t('search')" density="compact" />
                            </VCol>


                        </VRow>
                    </VCardText>

                    <VDivider />
                    <VCardText  class="pt-0 pb-0">


                        <!-- Loader -->
                        <div v-show="isLoading" class="loading-logo">
                            <PageLoader></PageLoader>
                        </div>

                        <VTable v-show="!isLoading" class="text-no-wrap table-background">
                            <thead class="text-uppercase">
                                <tr>
                                    <th class="sorting">{{ $t("module") }}
                                        <VIcon v-show="sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'desc' }"
                                            :size="16" icon="tabler-arrow-narrow-up" class="sorting-icon" />
                                        <VIcon v-show="!sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'asc' }"
                                            :size="16" icon="tabler-arrow-narrow-down" class="sorting-icon" />
                                    </th>
                                    <th class="text-center">{{ $t("parent_module") }}
                                    </th>
                                    <th class="text-center">{{ $t("icon") }}
                                    </th>
                                    <th class="text-center">{{ $t("roles") }}
                                    </th>
                                    <th class="text-center">{{ $t("status") }}</th>
                                    <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="module in moduleList" :key="module.id">
                                
                                    <td>{{ module.title }}</td>
                                    <td class="text-center">{{ module.parent_id ? module.parent_id?.title : '-' }}</td>
                                    <td class="text-center">{{ module.icon ? module.icon : '-' }}</td>
                                    <td class="text-center">
                                        <label v-for="role in  module.role"> {{ role.title }}<br />
                                        </label>
                                    </td>


                                    <td class="text-center">
                                        <VChip label
                                            :color="module.status == '1' ? 'success' : (module.status == '0' ? 'warning' : (module.status == '2' ? 'secondary' : 'primary'))"
                                            size="small" class="text-capitalize">
                                            <span>{{ $t(statusWord(module.status)) }}</span>
                                        </VChip>
                                    </td>

                                    <td class="text-center">

                                        <VBtn icon variant="text" color="default" size="x-small">
                                            <VIcon :size="22" icon="tabler-dots-vertical" />
                                            <VMenu activator="parent">
                                                <VList>


                                                    <VListItem
                                                        :to="{ name: 'masters-module-management-details-id', params: { id: module.id } }">
                                                        <template #prepend>
                                                            <VIcon :size="24" class="me-3" icon="tabler-eye" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem
                                                        :to="{ name: 'masters-module-management-edit-id', params: { id: module.id } }">
                                                        <template #prepend>
                                                            <VIcon icon="tabler-edit" size="24" class="me-3" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('edit') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem @click="openConfirmDialog(module)">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-trash" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('remove') }}</VListItemTitle>
                                                    </VListItem>
                                                </VList>
                                            </VMenu>
                                        </VBtn>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot v-show="!moduleList.length">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{ $t("no_data_found") }}
                                    </td>
                                </tr>
                            </tfoot>
                        </VTable>
                    </VCardText>
                    <VDivider />

                    <VCardText class="d-flex align-center flex-wrap justify-space-between gap-4 py-3 px-5">
                        <span class="text-sm text-disabled">
                            {{ paginationData }}
                        </span>

                        <VPagination v-model="currentPage" size="small" :total-visible="5" :length="totalPage" />
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <!-- Confirm Dialog -->
        <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen"  v-model:expected-value="expectedValue" 
            @confirm="handleDelete()" />

    </section>
</template>

<style lang="scss">
.app-user-search-filter {
    inline-size: 31.6rem;
}

.text-capitalize {
    text-transform: capitalize;
}

.user-list-name:not(:hover) {
    color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
}

</style>
