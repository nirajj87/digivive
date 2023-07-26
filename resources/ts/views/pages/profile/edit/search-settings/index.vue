<script lang="ts" setup>

import type { SearchSettingList } from '../.././types';
import { useUserProfileStore } from '../.././useUserProfileStore';

// Data store
const searchListStore = useUserProfileStore();
const searchList = ref<SearchSettingList[]>([])

const route = useRoute()

const isAlgoliya = ref(true)
const isTypsense = ref(true)

const userId = Number(route.params.userid) ?? 0;
// console.log("userId======>", userId);

const searchData = ref({
    search_type: '',
    app_id: '',
    key: '',
    collection_name: '',
    host: '',
    port: '',
    protocol: '',
})


// 👉 Fetching details
const fetchDetails = () => {
    return new Promise((resolve, reject) => {
        searchListStore
            .fetchSearchDetails(userId)
            .then((response: any) => {
                searchData.value = response.data
                // console.log("Details API===>",searchData.value);
                resolve(searchData);

            })
            .catch(error => {
                console.log("Error in Details===>", error);
                reject(searchData);
            });
    });
}

const fetchSearchType = () => {
    return new Promise((resolve, reject) => {
        searchListStore.searchType()
            .then((response: any) => {
                searchList.value = response.data;
                console.log("response type====>", searchList.value);

                resolve(searchList);

            })
            .catch(error => {
                console.log("Error in search type===>", error);
                reject(searchList);
            });
    });
}

watchEffect(() => {
    
    fetchDetails();
    fetchSearchType();
},

)

// const StorageItems = ["Algoliya", "Typesense"]4

const StorageItems = [
  { title: 'Algoliya', value: 'algoliya' },
  { title: 'Typesense', value: 'typesense' },
]

</script>

<template>
    <section>
        <VForm>
            <VCardText class="pt-0">
                <VRow>

                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("search_settings") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                   
                                    <VCol cols="12" >
                                      
                                        <VAutocomplete   :items="StorageItems" :label="$t('select') + ' *'" multiple />
                                       
                                    </VCol>
                               
                                    <VCol cols="12" md="6">
                                        <VCheckbox class="checkbox-label-font" :label="$t('algoliya')" v-model="isAlgoliya"
                                            @change="isAlgoliya ? false : true" />
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <VCheckbox class="checkbox-label-font" :label="$t('typesense')" v-model="isTypsense"
                                            @change="isTypsense ? false : true" />
                                    </VCol>

                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                    <VCol v-for="search in searchData" :key="search.id">
                        <VCol cols="12" v-if="isAlgoliya">
                            <h5 class="mb-2">{{ $t('algoliya') }}</h5>
                            <VCard border>
                                <VCardText>
                                    <VRow>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('app_id') + ' * '" v-model="search.app_id" />
                                        </VCol>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('key') + ' * '" v-model="search.key" />
                                        </VCol>
                                        <VCol cols="12" md="12">
                                            <VTextField :label="$t('collection_name') + ' * '"
                                                v-model="search.collection_name" />
                                        </VCol>
                                    </VRow>

                                </VCardText>
                            </VCard>

                        </VCol>


                        <VCol cols="12" v-if="isTypsense">
                            <h5 class="mb-2">{{ $t('typesense') }}</h5>
                            <VCard border>
                                <VCardText>
                                    <VRow>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('key') + ' * '" v-model="search.key" />
                                        </VCol>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('host') + ' * '" v-model="search.host" />
                                        </VCol>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('port') + ' * '" v-model="search.port" />
                                        </VCol>
                                        <VCol cols="12" md="6">
                                            <VTextField :label="$t('protocol') + ' * '" v-model="search.protocol" />
                                        </VCol>
                                        <VCol cols="12" md="12">
                                            <VTextField :label="$t('collection_name') + ' * '" v-model="search.protocol" />
                                        </VCol>
                                    </VRow>
                                </VCardText>
                            </VCard>
                        </VCol>


                    </VCol>
                    <VCol cols="12" class="text-right">
                        <VBtn variant="outlined" color="secondary mr-3">
                            {{ $t("can_btn") }}
                        </VBtn>
                        <VBtn color="primary mr-3"
                            :to="{ name: 'edit-profile-tab-uid', params: { tab: 'permissions', uid: userId } }">
                            {{ $t("previous") }}
                        </VBtn>
                        <VBtn type="submit" color="primary">
                            {{ $t("save") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </VForm>
    </section>
</template>