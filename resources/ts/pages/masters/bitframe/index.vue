<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import type { BitframeList } from '@/pages/masters/bitframe/view/types';
import { useBitframeStore } from '@/pages/masters/bitframe/view/useBitframeStore';

// 👉 Store
const bitframeListStore = useBitframeStore()
const bitframeList = ref<BitframeList[]>([])

const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalContent = ref(0)

const isLoading = ref(true)
const isSortingArrow = ref(true)
const isSnackbarVisibileDialog = ref(false)
const isConfirmDialogOpen = ref(false)

const message = ref('')
const searchQuery = ref('')
const sortBy = ref('')
const sortDirection = ref('')
const snackbarClass = ref('')
const dialogueBoxMessage = ref('')
const expectedValue = ref('')
const deleteId = ref()

// 👉 Fetching list
const fetchList = () => {
  return new Promise((resolve, reject) => {
    isLoading.value = true
    bitframeListStore
      .fetchList({
        search: searchQuery.value,
        perPage: rowPerPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDirection: sortDirection.value
      })
      .then((response: any) => {
        isLoading.value = false
        bitframeList.value = response.data

        totalPage.value = response.pagination.last_page ?? 0
        currentPage.value = response.pagination.current_page;
        totalContent.value = response.pagination.total

        resolve(bitframeList);

      })
      .catch(error => {
        console.log("Error in List===>", error);
        reject(bitframeList);
      });
  });
};

watchEffect(() => {
  fetchList();
  if (currentPage.value > totalPage.value) {
    currentPage.value = totalPage.value
  }
}
)

// Open Delete Dialog Box
const openDialog = (bitframe: any) => {
  deleteId.value = bitframe.id;
  expectedValue.value = bitframe.type;
  isConfirmDialogOpen.value = true;
  dialogueBoxMessage.value = 'are_sure_you_delete';
}

// Delete
const deleteModules = () => {
  bitframeListStore.delete(deleteId.value)
    .then((response: any) => {
      isSnackbarVisibileDialog.value = true;
      snackbarClass.value = 'success-snackbar';
      message.value = response.data.message
      fetchList();
    })
    .catch((error) => {
      console.log('Some Internal Server Error Occured')
      isSnackbarVisibileDialog.value = true;
      snackbarClass.value = 'delete-snackbar';
      console.error(error);
    })
}

const breadcrumbsData = ref({
  page_name: 'bitframe',
  name: 'bitframe',
});

const paginationData = computed(() => {
  const firstIndex = bitframeList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
  const lastIndex = bitframeList.value.length + ((currentPage.value - 1) * rowPerPage.value)

  return `Showing ${firstIndex} to ${lastIndex} of ${totalContent.value} entries`
})

</script>

<template>
  <section>
    <VSnackbar :class='snackbarClass' v-model="isSnackbarVisibileDialog" location="top right">
      {{ $t('delete_successfully') }}</VSnackbar>

    <!-- 👉 BreadCrumbs -->
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardText>
            <VRow>
              <VCol cols="9">
                <VRow>
                  <VCol cols="12" md="3">
                    <div class="d-flex align-center">
                      <span class="text-no-wrap me-3">{{ $t("show") }}:</span>
                      <VSelect v-model="rowPerPage" density="compact" :items="[10, 20, 30, 50]" />
                    </div>
                  </VCol>
                  <VCol cols="12" md="3">

                    <VBtn prepend-icon="tabler-plus" :to="{ name: 'masters-bitframe-add' }">
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

          <!-- SECTION Table -->
          <VCardText class="pt-0 pb-0">

             <!-- Loader -->
             <div v-show="isLoading" class="loading-logo">
              <PageLoader></PageLoader>
            </div>

            <VTable v-show="!isLoading" class="text-no-wrap table-background">
              <thead class="text-uppercase">
                <tr>
                  <th class="sorting">{{ $t("title") }}
                    <VIcon v-show="isSortingArrow"
                      @click="() => { isSortingArrow = !isSortingArrow; sortBy = 'title'; sortDirection = 'desc'; }"
                      :size="16" icon="tabler-arrow-narrow-up" class="sorting-icon" />
                    <VIcon v-show="!isSortingArrow" @click="() => {
                      isSortingArrow = !isSortingArrow; sortBy = 'title'; sortDirection = 'asc';
                    }" :size="16" icon="tabler-arrow-narrow-down" class="sorting-icon" />
                  </th>
                  <th>{{ $t("downloadable") }}</th>
                  <th>{{ $t("status") }}</th>

                  <th style="width:100px;text-align: center;">{{ $t('action') }}</th>
                </tr>
              </thead>

              <!-- Table Body -->
              <tbody v-if="!isLoading">

                <tr v-for="bitframe in bitframeList" :key="bitframe.id">

                  <!-- title -->
                  <td>
                    {{ bitframe.type }}
                  </td>

                  <!-- Is Download -->
                  <td>
                    {{ bitframe.is_downloadable == '1' ? ($t('yes')) : ($t('no')) }}
                  </td>

                  <!-- status -->
                  <td>
                    <VChip label
                      :color="bitframe?.status === '1' ? ($t('success')) : (bitframe?.status === '0' ? ($t('error')) : ($t('blocked')))"
                      size="small">
                      {{ bitframe?.status === '1' ? ($t('active')) : (bitframe?.status === '0' ?
                        ($t('inactive')) :
                        ($t('blocked'))) }}
                    </VChip>
                  </td>


                  <!-- Actions -->
                  <td class="text-center">
                    <VBtn icon variant="text" color="default" size="x-small">
                      <VIcon :size="22" icon="tabler-dots-vertical" />

                      <VMenu activator="parent">
                        <VList>

                          <VListItem :to="{ name: 'masters-bitframe-details-id', params: { id: bitframe.id } }">
                            <template #prepend>
                              <VIcon size="24" class="me-3" icon="tabler-eye" />
                            </template>
                            <VListItemTitle>{{ $t("view") }}</VListItemTitle>
                          </VListItem>

                          <VListItem :to="{ name: 'masters-bitframe-edit-id', params: { id: bitframe.id } }">
                            <template #prepend>
                              <VIcon size="24" class="me-3" icon="tabler-edit" />
                            </template>
                            <VListItemTitle>{{ $t("edit") }}</VListItemTitle>
                          </VListItem>

                          <VListItem @click="openDialog(bitframe)">
                            <template #prepend>
                              <VIcon size="24" class="me-3" icon="tabler-trash" />
                            </template>
                            <VListItemTitle>{{ $t('delete') }}</VListItemTitle>
                          </VListItem>

                        </VList>
                      </VMenu>
                    </VBtn>
                  </td>
                </tr>
                <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen"
                  :confirmation-msg="$t(`${dialogueBoxMessage}`)" @confirm="deleteModules()"
                  v-model:expected-value="expectedValue" />
              </tbody>

              <tfoot v-show="!bitframeList.length">
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