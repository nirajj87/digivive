<script setup lang="ts">

import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import { useClientStore } from './../view/clientModulesStore';
import type { clientParams } from './../view/type'

// 👉 Store
const clientStore = useClientStore();
const users = ref<clientParams[]>([]);

const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0)

const is_loading = ref(true)
const isSnackbarVisible = ref(false)
const isConfirmDialogOpen = ref(false)
const isSnackbarVisibileDialog = ref(false)

const searchQuery = ref('')
const snackbarMessage = ref('')
const snackbarClass = ref()
const class_name = ref('')
const expectedValue = ref('')
const deleteId = ref()

// api twice call if we use const ref
let sort_by = 'id';
let sort_direction = 'desc';

// 👉 Fetching users
const fetchUsers = () => {
	is_loading.value = true;
	const data = {
		'page': currentPage.value,
		'perPage': rowPerPage.value,
		'sortBy': sort_by,
		'sortDirection': sort_direction,
		'search': searchQuery.value
	}
	clientStore.fetchClientList(data).then((response: any) => {
		is_loading.value = false;
		users.value = response.data;
		// totalPage.value = response.pagination.last_page ;
		totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
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
})

// Open Delete Dialog Box
const openDialog = (user: any) => {
	console.log('dialogue box : ', user);

	deleteId.value = user.id;
	expectedValue.value = user.company_name;
	isConfirmDialogOpen.value = true;
	// dialogueBoxMessage.value = 'are_sure_you_delete';
}

// Delete
const deleteModules = () => {
	clientStore.deleteClient(deleteId.value)
		.then((response: any) => {
			isSnackbarVisibileDialog.value = true;
			snackbarClass.value = 'success-snackbar';
			snackbarMessage.value = response.data.message
			fetchUsers();
		})
		.catch((error) => {
			console.log('Some Internal Server Error Occured')
			isSnackbarVisible.value = true;
			snackbarClass.value = 'delete-snackbar';
			console.error(error);
		})
}

// 👉 Computing pagination data
const paginationData = computed(() => {
	const firstIndex = users.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
	const lastIndex = users.value.length + ((currentPage.value - 1) * rowPerPage.value)

	return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})

const breadcrumbsData = ref({
	page_name: 'client_management',
	name: 'client_management',
});

const sorting = (value: any) => {
	sort_by = value;
	sort_direction = sort_direction == 'desc' ? 'asc' : 'desc';
	fetchUsers();
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

</script>

<template>
	<div>
		<!-- <section> -->


		<section>
			<breadcrumbs :breadcrumbs-data="breadcrumbsData" />

			<VCard>
				<VRow>
					<VCol cols="12">

						<VCardText>
							<VRow>
								<VCol cols="12" md="9">
									<VRow>
										<VCol cols="12" md="3">
											<div class="d-flex align-center">
												<span class="text-no-wrap me-3">{{ $t('show') }}:</span>
												<VSelect v-model="rowPerPage" density="compact"
													:items="[10, 20, 30, 40, 50]" />
											</div>
										</VCol>
										<VCol cols="12" md="3">
											<VBtn prepend-icon="tabler-plus" :to="{ name: 'client-management-add' }">
												{{ $t('add_client') }}
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

						<VCardText class="pb-0 pt-0">

							<div v-show="is_loading" class="loading-logo">
								<PageLoader></PageLoader>
							</div>

							<VTable v-show="!is_loading" class="table-background">
								<thead class="text-uppercase">
									<tr>
										<th @click="sorting('name');" class="sorting">{{ $t("company_name") }}
											<VIcon :size="16"
												:icon="(sort_by == 'name' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'name' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />

										</th>
										<th @click="sorting('email');" class="text-center sorting">{{ $t("email") }}
											<VIcon :size="16"
												:icon="(sort_by == 'email' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'email' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />

										</th>
										<th @click="sorting('status');" class="text-center sorting">{{ $t("status") }}
											<VIcon :size="16"
												:icon="(sort_by == 'status' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'status' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />

										</th>
										<th class="text-center">Users</th>
										<th class="text-center">{{ $t("action") }}</th>
									</tr>
								</thead>
								<tbody>

									<tr v-for="user in users" :key="user.id">
										<td>{{ user.company_name ? user.company_name : '-' }}</td>
										<td class="text-center">{{ user.email }}</td>
										<td class="text-center">
											<VChip label
												:color="user?.status === '1' ? ($t('success')) : (user?.status === '0' ? ($t('error')) : ($t('blocked')))"
												size="small">
												{{ user?.status === '1' ? ($t('active')) : (user?.status === '0' ?
													($t('inactive')) : ($t('blocked'))) }}
											</VChip>
										</td>
										<td class="text-center">
											<VBtn variant="plain"
												:to="{ name: 'user-management-list-id', params: { id: user.id } }">
												<VIcon icon="tabler-users" />
											</VBtn>
										</td>
										<td class="text-center">
											<VBtn icon variant="text" color="default" size="x-small">

												<VIcon :size="22" icon="tabler-dots-vertical" />

												<VMenu activator="parent">
													<VList>
														<VListItem @click="forgotPassword(user.email)">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-key" />
															</template>
															<VListItemTitle>{{ $t('reset_password') }}</VListItemTitle>
														</VListItem>

														<VListItem
															:to="{ name: 'user-management-list-id', params: { id: user.id } }">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-users" />
															</template>
															<VListItemTitle>{{ $t('user_management') }}</VListItemTitle>
														</VListItem>


														<VListItem
															:to="{ name: 'profile-tab-id', params: { tab: 'profile-details', id: user.id } }">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-settings" />
															</template>
															<VListItemTitle>{{ $t('client_setting') }}</VListItemTitle>
														</VListItem>

														<VListItem @click="openDialog(user)">
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
								</tbody>
								<tfoot v-if="is_loading">
									<tr>
										<td colspan="6" class="text-center">

											<span v-show="!users.length && !is_loading">{{ $t('no_data_found') }}</span>
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
					</VCol>
				</VRow>
			</VCard>


			<!-- Confirm Dialog -->
			<ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen" v-model:expected-value="expectedValue"
				@confirm="deleteModules()" />
			<!-- <ConfirmDialog v-model:isDialogVisible="isConfirmDialogOpen" 
			@confirm="deleteModules()" v-model:expected-value="expectedValue" /> -->

			<!-- SnackBar -->
			<VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
				{{ $t(snackbarMessage) }}
			</VSnackbar>
		</section>
	</div>
</template>

