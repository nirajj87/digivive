<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue';
import { useStateStore } from './view/StateStore'
import { StateModel } from './view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useCountryStore } from '../country-management/view/CountryStore'
import { useI18n } from 'vue-i18n';

// language file object
const { t } = useI18n(); 


// 👉 getting store object 
const StateStore = useStateStore();
const countryStore = useCountryStore();
const countriesList = ref([{
    'title': '',
    'value': ''
}]);

const countriesID = ref([]);


const rowPerPage = ref(10);
const searchQuery = ref('');
const currentPage = ref(1)
const totalPage = ref(1)
const totalState = ref(0)

const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const isLoading = ref(false);

const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

// 👉 getting State list variable
const stateList = ref<StateModel[]>([]);


// 👉 fetching State and watching search query and pagination variable .
const fetchState = () => {

    if (countriesID.value.length == 0) {
        
        isLoading.value = true;

        StateStore.fetchAllState({
            'search': searchQuery.value,
            'perPage': rowPerPage.value,
            'page':  searchQuery.value == '' ? currentPage.value  : 1  , 
            'sortBy': sortBy.value,
            'sortDirection': sortDirection.value

        }).then((response: any) => {
            isLoading.value = false;
            stateList.value = response.data;
            totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
            totalState.value = response.pagination.total;

        }).catch(error => {
            console.error(error)
        })
    }


}

// 👉 This watcheffect is for fetching state globally
watchEffect(fetchState);


// 👉 fetching state by country
const fetchStateByCountry = ()=>{
    if(countriesID.value.length != 0){
        isLoading.value = true;
        
        StateStore.getStateByCountry(countriesID.value , {
            'search': searchQuery.value,
            'perPage': rowPerPage.value,
            'page': searchQuery.value == '' ? currentPage.value  : 1  , 
            'sortBy': sortBy.value,
            'sortDirection': sortDirection.value
        })
        .then((response:any)=>{
            isLoading.value = false;
            stateList.value = response.data;
            totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
            totalState.value = response.pagination.total;
        })
        .catch((error)=>{
            console.log("Intenal server error watcheffect 2 : ",error);
            
        })
    }
}

// 👉 This watcheffect for fetching state by country
watchEffect(fetchStateByCountry);

// 👉 getting status word
const StateIconVariant = (stat: any) => {
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
    const firstIndex = stateList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = stateList.value.length + ((currentPage.value - 1) * rowPerPage.value)

    // return `Showing ${firstIndex} to ${lastIndex} of ${totalState.value} entries`
    return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalState.value })
})


onBeforeMount(() => {
    countryStore.fetchAllCountry({ 'perPage': -1 })
        .then((response: any) => {
            countriesList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })
        });

})


const breadcrumbsData = ref({
    page_name: 'state_management',
    name: 'state_management',
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
                                    <VCol cols="12" md="2">
                                        <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-state-management-add' }">
                                            {{ $t("add") }}
                                        </VBtn>
                                    </VCol>
                                    <VCol cols="12" md="7">
                                        <VAutocomplete :items="countriesList" v-model="countriesID"
                                            :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                            :label="$t('country')" item-value="value" clearable multiple>
                                            <template v-slot:selection="{ item, index }">

                                                <v-chip v-if="index < 2">
                                                    <span>{{ item.title }}</span>
                                                </v-chip>
                                                <span v-if="index === 2" class="text-grey text-caption align-self-center">
                                                    (+{{ countriesID.length - 2 }} others)
                                                </span>
                                            </template>
                                        </VAutocomplete>
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
                                    <th class="sorting">{{ $t("state") }}
                                        <VIcon v-show="sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'desc' }"
                                            :size="16" icon="tabler-arrow-narrow-up" class="sorting-icon" />
                                        <VIcon v-show="!sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'asc' }"
                                            :size="16" icon="tabler-arrow-narrow-down" class="sorting-icon" />
                                    </th>
                                    <th class="text-center">{{ $t("state_code") }}</th>
                                    <th class="text-center">{{ $t("country") }}</th>
                                    <th class="text-center">{{ $t("status") }}</th>
                                    <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="state in stateList" :key="state.id">
                                    <td>{{ state.title }}</td>
                                    <td class="text-center">{{ state.state_code }}</td>
                                    <td class="text-center">{{ state.country_name }}</td>
                                    <td class="text-center">
                                        <VChip label
                                            :color="state.status == '1' ? 'success' : (state.status == '0' ? 'warning' : (state.status == '2' ? 'secondary' : 'primary'))"
                                            size="small" class="text-capitalize">
                                            <span>{{ $t(StateIconVariant(state.status)) }}</span>
                                        </VChip>
                                    </td>
                                    <td class="text-center">
                                        <VBtn icon variant="text" color="default" size="x-small">
                                            <VIcon :size="22" icon="tabler-dots-vertical" />

                                            <VMenu activator="parent">
                                                <VList>


                                                    <VListItem
                                                        :to="{ name: 'masters-state-management-details-id', params: { id: state.state_id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-eye" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem
                                                        :to="{ name: 'masters-state-management-edit-id', params: { id: state.state_id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-edit" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('edit') }}</VListItemTitle>
                                                    </VListItem>

                                                    <!-- <VListItem @click="openConfirmDialog(state.id)">
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
                            <tfoot v-show="!stateList.length">
                                <tr>
                                    <td colspan="5" class="text-center">
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