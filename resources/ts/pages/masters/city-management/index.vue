<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue';
import { useCityStore } from './view/CityStore'
import { CityModel } from './view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useCountryStore } from '../country-management/view/CountryStore'
import { useStateStore } from '../state-management/view/StateStore';
import { useI18n } from 'vue-i18n';

// language file object
const { t } = useI18n(); 


// 👉 getting store object 
const CityStore = useCityStore();
const countryStore = useCountryStore();
const stateStore = useStateStore();

const countriesID = ref([]);
const statesID = ref([]);

const rowPerPage = ref(10);
const searchQuery = ref('');
const currentPage = ref(1)
const totalPage = ref(1)
const totalCity = ref(0)

const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const isConfirmDialogOpen = ref(false)
const expectedValue = ref('');

const deletedCityID = ref();
const isLoading = ref(false);

const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

const stateList = ref([{
    'title': '',
    'value': ''
}]);

const countryList = ref([{
    'title' : '' ,
    'value' : ''
}])

// 👉 getting City list variable
const cityList = ref<CityModel[]>([]);


// 👉 fetching City and watching search query and pagination variable .
const fetchCity = () => {

    if (statesID.value.length == 0) {
        console.log('Fist watcheffect called : ', statesID.value);

        isLoading.value = true;

        CityStore.fetchAllCity({
            'search': searchQuery.value,
            'perPage': rowPerPage.value,
            // 'page': currentPage.value,
            'page': searchQuery.value == '' ? currentPage.value  : 1  ,
            'sortBy': sortBy.value,
            'sortDirection': sortDirection.value

        }).then((response: any) => {
            isLoading.value = false;
            cityList.value = response.data;
            totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
            totalCity.value = response.pagination.total;

        }).catch(error => {
            console.error(error)
        })
    }


}

watchEffect(fetchCity);


// 👉 fetching city by State
const fetchCityByState = () => {
    if (statesID.value.length != 0) {

        console.log('Second Watcheffect called : ', statesID.value);

        isLoading.value = true;

        CityStore.getCityByState(statesID.value , {
            'search': searchQuery.value,
            'perPage': rowPerPage.value,
            // 'page': currentPage.value,
            'page': searchQuery.value == '' ? currentPage.value  : 1  ,
            'sortBy': sortBy.value,
            'sortDirection': sortDirection.value

        }).then((response: any) => {
            isLoading.value = false;
            cityList.value = response.data;
            totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
            totalCity.value = response.pagination.total;

        }).catch(error => {
            console.error(error)
        })


    }
}

// 👉 This watcheffect for fetching city by state
watchEffect(fetchCityByState);

// 👉 Fetching state based on selected country
watch(countriesID , (newvalue , oldvalue)=>{
    stateStore.getStateByCountry(newvalue , {
        'perPage' : -1 
    }).then((response : any)=>{
        stateList.value = response.data.map((element : any)=>{
            return {'title':element.title , 'value':element.state_id}
        }) ;
    })
})

// 👉 getting status word
const CityIconVariant = (stat: any) => {
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
    const firstIndex = cityList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = cityList.value.length + ((currentPage.value - 1) * rowPerPage.value)

    return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalCity.value })
})

// 👉 Deleting City
const handleDelete = () => {

    CityStore.deleteCity(deletedCityID.value)
        .then((response: any) => {
            // 👉 setting snackbar variable
            isSnackbarVisibileDialog.value = true;
            snackBarMessage.value = response.message;
            snackbarClass.value = 'success-snackbar';

            fetchCity();
        })
        .catch((error) => {
            console.log('Some Internal Server Error Occured')
            isSnackbarVisibileDialog.value = true;
            snackBarMessage.value = 'internal-server-error';
            snackbarClass.value = 'delete-snackbar';
        })

}

// 👉 Deleting City
const openConfirmDialog = (city : any) => {

    deletedCityID.value = city.id;
    expectedValue.value = city.title;

    // 👉 setting dialogue box variable
    isConfirmDialogOpen.value = true;

}


const breadcrumbsData = ref({
    page_name: 'city_management',
    name: 'city_management',
});


onBeforeMount(() => {
    
    countryStore.fetchAllCountry({ 'perPage': -1 })
        .then((response: any) => {
            countryList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.id }
            })
        })
})

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
                                        <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-city-management-add' }">
                                            {{ $t("add") }}
                                        </VBtn>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <VAutocomplete :items="countryList" v-model="countriesID"
                                            :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                            :label="$t('country')" item-value="value" clearable multiple>
                                            <template v-slot:selection="{ item, index }">

                                                <v-chip v-if="index < 1">
                                                    <span>{{ item.title }}</span>
                                                </v-chip>
                                                <span v-if="index === 1" class="text-grey text-caption align-self-center">
                                                    (+{{ countriesID.length - 1 }} others)
                                                </span>
                                            </template>
                                        </VAutocomplete>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <VAutocomplete :items="stateList" v-model="statesID"
                                            :menu-props="{ transition: 'scroll-y-transition', maxHeight: '300' }"
                                            :label="$t('state')" item-value="value" clearable multiple>
                                            <template v-slot:selection="{ item, index }">

                                                <v-chip v-if="index < 1">
                                                    <span>{{ item.title }}</span>
                                                </v-chip>
                                                <span v-if="index === 1" class="text-grey text-caption align-self-center">
                                                    (+{{ statesID.length - 1 }} others)
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
                                    <th class="sorting">{{ $t("city") }}
                                        <VIcon v-show="sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'desc' }"
                                            :size="16" icon="tabler-arrow-narrow-up" class="sorting-icon" />
                                        <VIcon v-show="!sortingArrow"
                                            @click="() => { sortingArrow = !sortingArrow; sortBy = 'title'; sortDirection = 'asc' }"
                                            :size="16" icon="tabler-arrow-narrow-down" class="sorting-icon" />
                                    </th>
                                    <th class="text-center">{{ $t("state") }}</th>
                                    <th  class="text-center">{{ $t("country") }}</th>
                                    <th  class="text-center">{{ $t("status") }}</th>
                                    <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr v-for="city in cityList" :key="city.id">

                                    <td>{{ city.title }}</td>
                                    <td class="text-center">{{ city.state_name }}</td>
                                    <td class="text-center">{{ city.country }}</td>
                                    <td class="text-center">
                                        <VChip label
                                            :color="city.status == '1' ? 'success' : (city.status == '0' ? 'warning' : (city.status == '2' ? 'secondary' : 'primary'))"
                                            size="small" class="text-capitalize">
                                            <span>{{ $t(CityIconVariant(city.status)) }}</span>
                                        </VChip>
                                    </td>
                                    <td class="text-center">
                                        <VBtn icon variant="text" color="default" size="x-small">
                                            <VIcon :size="22" icon="tabler-dots-vertical" />

                                            <VMenu activator="parent">
                                                <VList>


                                                    <VListItem
                                                        :to="{ name: 'masters-city-management-details-id', params: { id: city.id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-eye" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem
                                                        :to="{ name: 'masters-city-management-edit-id', params: { id: city.id } }">
                                                        <template #prepend>
                                                            <VIcon size="24" class="me-3" icon="tabler-edit" />
                                                        </template>
                                                        <VListItemTitle>{{ $t('Edit') }}</VListItemTitle>
                                                    </VListItem>

                                                    <VListItem @click="openConfirmDialog(city)">
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
                            <tfoot v-show="!cityList.length">
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

        <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen"   v-model:expected-value="expectedValue"
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