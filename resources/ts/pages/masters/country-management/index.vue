<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue';
import { useCountryStore } from './view/CountryStore'
import { CountryModel } from './view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useI18n } from 'vue-i18n';

// language file object
const { t } = useI18n(); 


// 👉 getting store object 
const CountryStore = useCountryStore();


const rowPerPage = ref(10);
const searchQuery = ref('');
const currentPage = ref(1)
const totalPage = ref(1)
const totalCountry = ref(0)

const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const isConfirmDialogOpen = ref(false)

const deletedCountryID = ref();
const isLoading = ref(false);

const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

// 👉 getting Country list variable
const countryList = ref<CountryModel[]>([]);


// 👉 fetching Country and watching search query and pagination variable .
const fetchCountry = () => {
    isLoading.value = true;

    CountryStore.fetchAllCountry({
        'search': searchQuery.value,
        'perPage': rowPerPage.value,
        'page': currentPage.value,
        'sortBy' : sortBy.value ,
        'sortDirection' : sortDirection.value

    }).then((response: any) => {
        isLoading.value = false;
        countryList.value = response.data;
        totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
        totalCountry.value = response.pagination.total;

    }).catch(error => {
        console.error(error)
    })
}

watchEffect(fetchCountry);


// 👉 getting status word
const CountryIconVariant = (stat: any) => {
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
    const firstIndex = countryList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = countryList.value.length + ((currentPage.value - 1) * rowPerPage.value)

    return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalCountry.value })
})

const breadcrumbsData = ref({
    page_name: 'country_management',
    name: 'country_management',
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
                                        <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-country-management-add' }">
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
                                    <th class="sorting">{{ $t("country") }}
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
                                    
                                    <th class="text-center">{{ $t("iso_code2") }}</th>
                                    <th class="text-center">{{ $t("iso_code3") }}</th>
                                    <th class="text-center">{{ $t("capital") }}</th>
                                    <th class="text-center">{{ $t("phone_code") }}</th>
                                    <th class="text-center">{{ $t("status") }}</th>
                                    <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="country in countryList" :key="country.id">
                                    <td>{{ country.title }}</td>
                                    <td class="text-center">{{ country.iso2 }}</td>
                                    <td class="text-center">{{ country.iso3 }}</td>
                                    <td class="text-center">{{ country.capital }}</td>
                                    <td class="text-center">{{ country.phone_code }}</td>
                                    <td class="text-center">
                                        <VChip label :color="country.status == '1' ? 'success' : (country.status == '0' ? 'warning' : (country.status == '2' ? 'secondary' : 'primary'))" size="small"
                                            class="text-capitalize">
                                            <span>{{ $t(CountryIconVariant(country.status)) }}</span>
                                        </VChip>
                                    </td>
                                    <td class="text-center">
                                        <VBtn icon variant="text" color="default" size="x-small">
                                            <VIcon :size="22" icon="tabler-dots-vertical" />
                                            <VMenu activator="parent">
                                                <VList>
                                                    <VListItem :to="{ name : 'masters-country-management-details-id' , params: { id: country.id }}">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-eye" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                                    </VListItem>
                                                    <VListItem
                                                        :to="{ name: 'masters-country-management-edit-id', params: { id: country.id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-edit" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('Edit') }}</VListItemTitle>
                                                    </VListItem>
                                                    <!-- <VListItem @click="openConfirmDialog(country.id)">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-trash" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('remove') }}</VListItemTitle>
                                                    </VListItem> -->
                                                </VList>
                                            </VMenu>
                                        </VBtn>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot v-show="!countryList.length">
                                <tr>
                                    <td colspan="7" class="text-center">
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

        <!-- <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen" 
            @confirm="handleDelete()" /> -->
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