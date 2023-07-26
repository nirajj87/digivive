<script setup lang="ts">

import { useUserListStore } from '@/views/apps/user/useUserListStore'
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import { useAdminStore } from './../view/adminModulesStore';
import type { userParams } from './../view/type'

// 👉 Store

const adminStore = useAdminStore();


const skill = ref(20)
const userListStore = useUserListStore()
const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0);
const is_loading = ref(true)
const users = ref<userParams[]>([]);

const isSnackbarVisible = ref(false);

const snackbarMessage = ref();
// const confirmMessage = ref('')
const class_name = ref('')



const dialogueBoxMessage = ref('')
const isConfirmDialogOpen = ref(false)
const expectedValue = ref('');

// api twice call if we use const ref
let sort_by = 'id';
let sort_direction = 'desc';


// 👉 Fetching users
const fetchUsers = () => {
	is_loading.value = true;

	adminStore.fetchUserList({
		'page': currentPage.value,
		'perPage': rowPerPage.value,
		'sortBy': sort_by,
		'sortDirection': sort_direction,
		'search': searchQuery.value
	}).then((response: any) => {

		users.value = response.data;
		totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
		currentPage.value = response.pagination.current_page;
		totalUsers.value = response.pagination.total;
		is_loading.value = false;

	}).catch(error => {
		is_loading.value = false;
		console.error(error);
		// refreshToken()
	})
}
// 👉 watching current page
watchEffect(() => {
	fetchUsers();
	//   refreshToken(); 
})

const sorting = (value: any) => {
	sort_by = value;
	sort_direction = sort_direction == 'desc' ? 'asc' : 'desc';
	fetchUsers();
}
// 👉 Computing pagination data
const paginationData = computed(() => {
	const firstIndex = users.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
	const lastIndex = users.value.length + ((currentPage.value - 1) * rowPerPage.value)

	return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})


const resolveUserStatusVariant = (stat: string) => {
	if (stat == '0')
		return 'error'
	if (stat == '1')
		return 'success'
	return 'primary'
}

const UserIconVariant = (stat: string) => {

	if (stat == '1')
		return 'active'
	if (stat == '0')
		return 'inactive'
	return 'inactive'
}


const delete_user_id = ref(0);
const openConfirmDialog = (user: any) => {
	isConfirmDialogOpen.value = true;
	// confirmMessage.value = 'are_sure_you_delete';
	delete_user_id.value = user.id;
	expectedValue.value = user.email;

}
const deleteAdmin = () => {

	is_loading.value = true;
	const id = delete_user_id.value;
	adminStore.deleteAdmin(id).then((response: any) => {
		isSnackbarVisible.value = true;
		snackbarMessage.value = response.data.message;
		class_name.value = 'success-snackbar';

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
	adminStore.forgotPassword(data)
		.then((r: any) => {
			isSnackbarVisible.value = true;
			class_name.value = 'success-snackbar';
			snackbarMessage.value = r.data.message;
		})
		.catch(e => {
			console.error(e.response.data);
		})
}

// const refreshToken = () => {
// 	adminStore.refreshToken()
// 		.then((response: any) => {
// 			console.log(response.data);
// 		})
// 		.catch((e) => {

// 		})
// }


const breadcrumbsData = ref({
	page_name: 'admin_management',
	name: 'admin_management',
});

</script>

<template>
	<div>


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
											<VBtn prepend-icon="tabler-plus" :to="{ name: 'admin-management-add' }">
												{{ $t('add_user') }}
											</VBtn>
										</VCol>
									</VRow>
								</VCol>
								<VCol cols="12" sm="3">
									<VTextField v-model="searchQuery" :label="$t('search')" density="compact" />
								</VCol>

							</VRow>
						</VCardText>

						<VDivider />

						<VCardText class="pb-0 pt-0">
							<!-- Loader -->
							<div v-show="is_loading" class="loading-logo">
								<PageLoader></PageLoader>
							</div>
							<VTable v-show="!is_loading" class="table-background">
								<thead class="text-uppercase">
									<tr>
										<th @click="sorting('email');" class="sorting">{{ $t("email") }}
											<VIcon :size="16"
												:icon="(sort_by == 'email' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'email' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />
										</th>
										<th @click="sorting('name');" class="text-center sorting">{{ $t("name") }}
											<VIcon :size="16"
												:icon="(sort_by == 'name' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'name' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />
										</th>
										<th @click="sorting('status');" class="text-center sorting">{{ $t("status") }}
											<VIcon :size="16"
												:icon="(sort_by == 'status' && sort_direction == 'desc') ? 'tabler-arrow-narrow-up' : ((sort_by == 'status' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
												color="secondary" class="sorting-icon" />
										</th>
										<th class="text-center">{{ $t("action") }}</th>
									</tr>
								</thead>
								<tbody>

									<tr v-for="user in users" :key="user.id">
										<td>{{ user.email }}</td>
										<td class="text-center">{{ user.first_name }} {{ user.last_name }} </td>
										<td class="text-center">
											<VChip label :color="resolveUserStatusVariant(user.status)" size="small"
												class="text-capitalize">
												<span>{{ $t(UserIconVariant(user.status)) }}</span>
											</VChip>
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
<!-- 
														<VListItem
															:to="{ name: 'admin-management-edit-id', params: { id: user.id } }">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-edit" />
															</template>
															<VListItemTitle>{{ $t('edit') }}</VListItemTitle>
														</VListItem> -->

														<VListItem
															:to="{ name: 'profile-tab-id', params: {tab : 'profile-details' , id: user.id } }">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-edit" />
															</template>
															<VListItemTitle>{{ $t('edit') }}</VListItemTitle>
														</VListItem>

														<VListItem
															:to="{ name: 'admin-management-details-id', params: { id: user.id } }">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-eye" />
															</template>
															<VListItemTitle>{{ $t('view') }}</VListItemTitle>
														</VListItem>
														<VListItem @click="openConfirmDialog(user)">
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-trash" />
															</template>
															<VListItemTitle>{{ $t('remove_user') }}</VListItemTitle>
														</VListItem>
													</VList>


												</VMenu>
											</VBtn>

										</td>
									</tr>





								</tbody>
								<tfoot v-show="!users.length">
									<tr>
										<td colspan="4" class="text-center">
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
				@confirm="deleteAdmin()" />
			<!-- SnackBar -->
			<VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
				{{ $t(snackbarMessage) }}
			</VSnackbar>


		</section>


	</div>
</template>