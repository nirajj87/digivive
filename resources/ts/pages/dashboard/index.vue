<script setup lang="ts">

import { useClientStore } from './../client-management/view/clientModulesStore';
import type { clientParams } from './../client-management/view/type';

// 👉 Store
const clientStore = useClientStore()
const users = ref<clientParams[]>([])

const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0)

const isSnackbarVisible = ref(false)
const isConfirmDialogOpen = ref(false)
const is_loading = ref(true)

const searchQuery = ref('')
const snackbarMessage = ref('')
const confirmMessage = ref('')
const class_name = ref('')
const expectedValue = ref('')
const deleteId = ref()

const base_url = window.location.origin + '/storage/';

// 👉 Fetching users
const fetchUsers = () => {
    const data = {
        page: currentPage.value,
        per_page: rowPerPage.value,
        sort_by: 'id',
        sort_direction: 'desc',
        search: searchQuery.value
    }
    is_loading.value = true;
    clientStore.fetchClientList(data).then((response:any) => {
        is_loading.value = false;
        users.value = response.data;
        
        totalPage.value = response.pagination.last_page
        currentPage.value = response.pagination.current_page;
        totalUsers.value = response.pagination.total;
    }).catch(error => {
        is_loading.value = false;
        console.error(error)
    })
}
// 👉 watching current page
watchEffect(() => {
    fetchUsers();

    if (currentPage.value > totalPage.value)
        currentPage.value = totalPage.value
})

const openConfirmDialog = (user: any) => {
    deleteId.value = user.id;
    isConfirmDialogOpen.value = true;
    expectedValue.value = user.company_name;
}

const deleteClient = () => {
    is_loading.value = true;
    clientStore.deleteClient(deleteId.value).then((response: any) => {
        isSnackbarVisible.value = true;
        is_loading.value = false;
        class_name.value = 'success-snackbar';
        snackbarMessage.value = response.data.message;
        fetchUsers();
    }).catch(error => {
        is_loading.value = false;
        console.error(error);
    })

}

const forgotPassword = (email: any) => {
    const data = {
        email
    }
    clientStore.forgotPassword(data)
        .then((r: any) => {
            isSnackbarVisible.value = true;
            class_name.value = 'success-snackbar';
            snackbarMessage.value = r.data.message;
        })
        .catch(e => {
            console.error(e.response.data);
        })
}

// 👉 Computing pagination data
const paginationData = computed(() => {
    const firstIndex = users.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
    const lastIndex = users.value.length + ((currentPage.value - 1) * rowPerPage.value)

    return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})

</script>
<template>
    <section>

        <!-- SnackBar -->
        <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
            {{ $t('the_client_is_successfully_deleted') }}
        </VSnackbar>

        <VRow>
            <VCol cols="12" sm="6" md="4" v-for="user in users" :key="user.id">
                <VCard class="client-dashboard">
                    <VBtn icon variant="text" color="default" size="x-small" class="catalouge_action_btn">
                        <VIcon :size="22" icon="tabler-dots-vertical" />

                        <VMenu activator="parent">
                            <VList>
                                <VListItem :to="{ name: 'client-management-edit-id', params: { id: user.id } }">
                                    <template #prepend>
                                        <VIcon size="24" class="me-3" icon="tabler-edit" />
                                    </template>
                                    <VListItemTitle>{{ $t('edit') }}</VListItemTitle>
                                </VListItem>
                                <VListItem :to="{ name: 'client-management-details-id', params: { id: user.id } }">
                                    <template #prepend>
                                        <VIcon size="24" class="me-3" icon="tabler-eye" />
                                    </template>
                                    <VListItemTitle>{{ $t('view') }}</VListItemTitle>
                                </VListItem>
                                <VListItem @click="openConfirmDialog(user)">
                                    <template #prepend>
                                        <VIcon size="24" class="me-3" icon="tabler-trash" />
                                    </template>
                                    <VListItemTitle>{{ $t('remove_client') }}</VListItemTitle>
                                </VListItem>
                            </VList>
                        </VMenu>
                    </VBtn>
                    <VCardText>
                        <VAvatar class="avatar-name" size="150"
                            :image="user.profile_img ?  user.profile_img : ''">
                            <span v-if="user.company_name">{{ user.company_name.slice(0, 2) }}</span>
                        </VAvatar>

                    </VCardText>
                    <VCardSubtitle class="text-caption text-center">
                        <!-- <p class="text-sm text-success">{{ user.status == '1' ? $t("active") : $t("inactive") }}</p> -->
                        <p>
                        <VChip :color="user.status === '1' ? ($t('success')) : (user.status === '0' ? ($t('error')) : ($t('blocked')))"
                      size="small">
                      {{ user.status === '1' ? ($t('active')) : (user.status === '0' ?
                        ($t('inactive')) : ($t('blocked'))) }}</VChip></p>
                    </VCardSubtitle>
                </VCard>
            </VCol>
            <VCol cols="12" sm="6" md="4">
                <VCard :to="{ name: 'client-management-add' }" class="client-dashboard" :height="166"
                    style="display:flex;align-items: center;">
                    <VCardText class="position-relative text-center">
                        <VIcon size="100" icon="tabler-plus" />
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <!-- Confirm Dialog -->
        <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen" v-model:confirmation-msg="confirmMessage"
            @confirm="deleteClient()" v-model:expected-value="expectedValue" />

    </section>
</template>

<style>
.client-dashboard.v-card .v-card-text {
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.client-dashboard .catalouge_action_btn {
    position: absolute;
    right: 0;
    top: 0;
}
</style>