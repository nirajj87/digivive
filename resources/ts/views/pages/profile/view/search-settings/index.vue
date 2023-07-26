<script lang="ts" setup>
import type {SearchSettingList} from '../.././types';
import {useUserProfileStore} from '../.././useUserProfileStore';

// Data store
const searchListStore = useUserProfileStore();
const searchList = ref<SearchSettingList[]>([])

const route = useRoute();

const userId = Number(route.params.id) ?? 0;

// 👉 Fetching list
const fetchList = () => {
  return new Promise((resolve, reject) => {
    searchListStore
      .fetchSearchList(userId)
      .then((response: any) => {
        searchList.value = response.data
        // console.log("Listing API===>",searchList.value);
        resolve(searchList);

      })
      .catch(error => {
        console.log("Error in List===>", error);
        reject(searchList);
      });
  });
}


watchEffect(() => {
  fetchList();
}
)

</script>

<template>
    <section>
        <VCardText class="pt-0">
            <h4 class="mb-2">{{ $t("search_settings") }}</h4>
            <VRow  v-for="search in searchList" :key="search.id">
                <VCol cols="12">
                   
                    <VCard border class="mb-5">
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        <!-- <VIcon icon="tabler-brand-aws" /> -->
                                        {{ $t("algoliya") }}
                                    </h5>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("app_id") }}</h5>
                                    <p class="mb-0">{{search.app_id}}</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("key") }}</h5>
                                    <p class="mb-0">{{search.key}}</p>
                                </VCol>
                                <VCol cols="12">
                                    <h5>{{ $t("collection_name") }}</h5>
                                    <p class="mb-0">{{search.collection_name}}</p>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VCard border class="mb-5">
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        {{ $t("typesense") }}</h5>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("key") }}</h5>
                                    <p class="mb-0">{{search.key}}</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("host") }}</h5>
                                    <p class="mb-0">{{search.host}}</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("port") }}</h5>
                                    <p class="mb-0">{{search.port}}</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("protocol") }}</h5>
                                    <p class="mb-0">{{ search.protocol }}</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("collection_name") }}</h5>
                                    <p class="mb-0">{{ search.collection_name }}</p>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VRow>
                        <VCol cols="12 text-right">
                            <VBtn
                                prepend-icon="tabler-edit"
                                :to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'search-settings',userid:userId} }"
                            >
                                {{$t('edit_search_setting')}}
                            </VBtn>
                        </VCol>
                    </VRow>
                </VCol>
            </VRow>
        </VCardText>
    
    </section>
</template>