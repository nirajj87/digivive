<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRoute } from 'vue-router';
import avatar from '@images/avatars/a2.jpg';
import { useClientUserStore } from '../view/userStore';
import { UserData } from '../view/type'
import { useI18n } from 'vue-i18n';

// language file object
const { t } = useI18n();

const route = useRoute();
const clientUserStore = useClientUserStore();


const rowPerPage = ref(10);
const searchQuery = ref('');
const currentPage = ref(1)
const totalPage = ref(1)
const totalUser = ref(0)

const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')
const snackBarMessage = ref('')

const isConfirmDialogOpen = ref(false)
const expectedValue = ref('');

const deletedUserID = ref();
const isLoading = ref(false);


const sortBy = ref('title');
const sortDirection = ref('');
const sortingArrow = ref(true);

const clientId = route.params.id ?? 0;

// 👉 getting advisory list variable
const userList = ref<UserData[]>([]);

const fetchAllUserOfClient = () => {
	clientUserStore.fetchAllUserOfClient(clientId, {
		'search': searchQuery.value,
		'perPage': rowPerPage.value,
		'page': currentPage.value,
		'sortBy': sortBy.value,
		'sortDirection': sortDirection.value
	})
		.then((response: any) => {
			isLoading.value = false;
			userList.value = response.data;
			totalPage.value = Math.floor(response.pagination.total / rowPerPage.value) + 1;
			totalUser.value = response.pagination.total;
		})
		.catch((error) => {
			isLoading.value = false;
			isSnackbarVisibileDialog.value = true;
			snackBarMessage.value = 'internal-server-error';
			snackbarClass.value = 'delete-snackbar';
		})
}


watchEffect(() => {
	fetchAllUserOfClient();
})


// 👉 Computing pagination data
const paginationData = computed(() => {
	const firstIndex = userList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
	const lastIndex = userList.value.length + ((currentPage.value - 1) * rowPerPage.value)

	return t('pagination.data_count', { firstIndex, lastIndex, totalData: totalUser.value })
})


// 👉 getting status word
const statusWord = (stat: any) => {
	if (stat === '0')
		return 'inactive'
	if (stat === '1')
		return 'active'
	if (stat === '2')
		return 'blocked'
	return 'primary'
}


// 👉 Deleting Advisory
const handleDelete = () => {

	clientUserStore.deleteClientUser(deletedUserID.value)
		.then((response: any) => {
			// 👉 setting snackbar variable
			isSnackbarVisibileDialog.value = true;
			snackBarMessage.value = response.message;
			snackbarClass.value = 'success-snackbar';

			// for reload
			fetchAllUserOfClient();
		})
		.catch((error) => {
			console.log('Some Internal Server Error Occured')
			isSnackbarVisibileDialog.value = true;
			snackBarMessage.value = 'internal-server-error';
			snackbarClass.value = 'delete-snackbar';
		})

}


// 👉 Deleting Advisory
const openConfirmDialog = (advisory: any) => {

	deletedUserID.value = advisory.id;
	expectedValue.value = advisory.title;

	// 👉 setting dialogue box variable
	isConfirmDialogOpen.value = true;


}


const breadcrumbsData = ref({
	page_name: 'user_management',
	name: 'user_management',
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
											<VBtn prepend-icon="tabler-plus"
												:to="{ name: 'user-management-add-id', params: { 'id': route.params.id } }">
												{{ $t('add_user') }}
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

							<VTable class="table-background">
								<thead class="text-uppercase">
									<tr>
										<th class="sorting">{{ $t("user_name") }}
											<VIcon :size="16" icon="tabler-arrow-narrow-up" color="secondary"
												class="sorting-icon" />
										</th>
										<th class="text-center sorting">{{ $t("email") }}
											<VIcon :size="16" icon="tabler-arrow-narrow-up" color="secondary"
												class="sorting-icon" />
										</th>
										<th class="text-center sorting">{{ $t("status") }}
											<VIcon :size="16" icon="tabler-arrow-narrow-up" color="secondary"
												class="sorting-icon" />
										</th>
										<th class="text-center">{{ $t("action") }}</th>
									</tr>
								</thead>
								<tbody>



									<!-- Making Dynamic -->
									<tr v-for="user in userList" :key="user.id">
										<td>
											<div class="d-flex ">
												<VAvatar :size="40" variant="tonal" class="mr-2">
													<VImg :src="user.profile_img ? user.profile_img : avatar" />

												</VAvatar>
												<div class="d-flex flex-column">
													<h6 class="text-base">
														{{ user.first_name + ' ' + user.last_name }}
													</h6>
													<small>{{ user.designation }}</small>
												</div>
											</div>
										</td>
										<td class="text-center">{{ user.email }}</td>

										<td class="text-center">
											<VChip label
												:color="user.status == '1' ? 'success' : (user.status == '0' ? 'warning' : (user.status == '2' ? 'secondary' : 'primary'))"
												size="small" class="text-capitalize">
												<span>{{ $t(statusWord(user.status)) }}</span>
											</VChip>
										</td>

										<td class="text-center">
											<VBtn icon variant="text" color="default" size="x-small">
												<VIcon :size="22" icon="tabler-dots-vertical" />
												<VMenu activator="parent">
													<VList>
														<VListItem>
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-eye" />
															</template>
															<VListItemTitle>{{ $t('view') }}</VListItemTitle>
														</VListItem>

														<VListItem>
															<template #prepend>
																<VIcon size="24" class="me-3" icon="tabler-pencil" />
															</template>
															<VListItemTitle>{{ $t('edit_user') }}</VListItemTitle>
														</VListItem>

														<VListItem>
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

								<tfoot v-show="!userList.length">
									<tr>
										<td colspan="6" class="text-center">
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
					</VCol>
				</VRow>
			</VCard>


			<!-- <ConfirmDialog  v-model:isDialogVisible="isConfirmDialogOpen"  v-model:expected-value="expectedValue"
            @confirm="handleDelete()" /> -->

		</section>

	</div>
</template>