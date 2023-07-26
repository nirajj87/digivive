<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue';
import { useAdsStore } from './view/AdsStore'
import { AdsModel } from './view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useI18n } from 'vue-i18n';

// language file object
const { t } = useI18n(); 

// 👉 getting store object 
const AdsStore = useAdsStore();

const rowPerPage = ref(10);
const searchQuery = ref('');
const currentPage = ref(1)
const totalPage = ref(1)
const totalAds = ref(0)

const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const isConfirmDialogOpen = ref(false)
const expectedValue = ref('');

const deletedAdsID = ref();
const isLoading = ref(false);

const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

// 👉 getting Ads list variable
const adsList = ref<AdsModel[]>([]);


// 👉 fetching Ads and watching search query and pagination variable .
const fetchAds = () => {
    isLoading.value = true;

    AdsStore.fetchAllAds({
        'search': searchQuery.value,
        'perPage': rowPerPage.value,
        'page': currentPage.value,
        'sortBy' : sortBy.value ,
        'sortDirection' : sortDirection.value

    }).then((response: any) => {
        isLoading.value = false;
        adsList.value = response.data;
        totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
        totalAds.value = response.pagination.total;

    }).catch(error => {
        console.error(error)
    })
}

watchEffect(fetchAds);


// 👉 getting status word
const AdsIconVariant = (stat: any) => {
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
    const firstIndex = adsList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = adsList.value.length + ((currentPage.value - 1) * rowPerPage.value)

    // return `Showing ${firstIndex} to ${lastIndex} of ${totalAds.value} entries`
    return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalAds.value })
})

// 👉 Deleting Ads
const handleDelete = () => {

    AdsStore.deleteAds(deletedAdsID.value)
        .then((response: any) => {
            // 👉 setting snackbar variable
            isSnackbarVisibileDialog.value = true;
            snackBarMessage.value = response.message;
            snackbarClass.value = 'success-snackbar';

            // for reload
            fetchAds();
        })
        .catch((error) => {
            console.log('Some Internal Server Error Occured')
            isSnackbarVisibileDialog.value = true;
            snackBarMessage.value = 'internal-server-error';
            snackbarClass.value = 'delete-snackbar';
        })

}

// 👉 Deleting Ads
const openConfirmDialog = (Ads : any) => {

    deletedAdsID.value = Ads.id;
    expectedValue.value = Ads.title;
    
    // 👉 setting dialogue box variable
    isConfirmDialogOpen.value = true;

}


const breadcrumbsData = ref({
    page_name: 'ads_management',
    name: 'ads_management',
});

</script>


<template>
    <section>

        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackBarMessage }} </VSnackbar>


        <!-- 👉 BreadCrumbs -->
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
                                            <span class="text-no-wrap me-3">{{ $t('show') }} :</span>
                                            <VSelect v-model="rowPerPage" density="compact" :items="[10, 20, 30, 50]" />
                                        </div>
                                    </VCol>
                                    <VCol cols="12" md="3">
                                        <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-ads-management-add' }">
                                            {{ $t("add") }}
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCol>


                            <VCol cols="12" md="3">
                                <VTextField v-model="searchQuery" label="Search" density="compact" />
                            </VCol>


                        </VRow>
                    </VCardText>

                    <VDivider />
                    <VCardText class="pt-0 pb-0">

                        <!-- Loader -->
                        <div v-show="isLoading" class="loading-logo">
                            <PageLoader></PageLoader>
                        </div>

                        <VTable v-show="!isLoading" class="text-no-wrap table-background">


                            <thead class="text-uppercase">
                                <tr>
                                    <th class="sorting">{{ $t("ads") }}
                                        <VIcon
                                            v-show="sortingArrow" 
                                            @click="()=>{sortingArrow = !sortingArrow; sortBy = 'title' ; sortDirection='desc'}"
											:size="16"
											icon="tabler-arrow-narrow-up"
											class="sorting-icon"
										/>
										<VIcon
                                            v-show="!sortingArrow" 
                                            @click="()=>{sortingArrow = !sortingArrow; sortBy = 'title' ; sortDirection='asc'}"
											:size="16"
											icon="tabler-arrow-narrow-down"
											class="sorting-icon"
										/>
                                    </th>
                                    <th  class="text-center">{{ $t("status") }}</th>
                                    <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr v-for="ad in adsList" :key="ad.id">

                                    <td>{{ ad.title }}</td>
                                    <td class="text-center">
                                        <VChip label :color="ad.status == '1' ? 'success' : (ad.status == '0' ? 'warning' : (ad.status == '2' ? 'secondary' : 'primary'))" size="small"
                                            class="text-capitalize">
                                            <span>{{ $t(AdsIconVariant(ad.status)) }}</span>
                                        </VChip>
                                    </td>
                                    <td class="text-center">
                                        <VBtn icon variant="text" color="default" size="x-small">
                                            <VIcon :size="22" icon="tabler-dots-vertical" />

                                            <VMenu activator="parent">
                                                <VList>

                                                    
                                                    <VListItem :to="{ name : 'masters-ads-management-details-id' , params: { id: ad.id }}">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-eye" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem
                                                        :to="{ name: 'masters-ads-management-edit-id', params: { id: ad.id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-edit" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('edit') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem @click="openConfirmDialog(ad)">
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
                            <tfoot v-show="!adsList.length">
                                <tr>
                                    <td colspan="3" class="text-center">
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

        <ConfirmDialog  v-model:isDialogVisible="isConfirmDialogOpen"  v-model:expected-value="expectedValue"
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