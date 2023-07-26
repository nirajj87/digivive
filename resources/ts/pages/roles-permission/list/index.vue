<script setup lang="ts">
import type { UserProperties } from '@/@fake-db/types'

import { useRoleModuleStore } from './../view/roleModulesStore';
import type {RoleParams} from './../view/type';

import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'

// 👉 Store

const roleListStore = useRoleModuleStore()
const searchQuery = ref('')
const is_loading= ref(false)

const rolesList = ref<RoleParams[]>([]);
  const isConfirmDialogOpen = ref(false);
  const isSnackbarVisible = ref(false)

const isSuccessDialogVisible = ref(false)
    const successMessage = ref('');
    const snackbarMessage = ref('');
    const confirmMessage = ref('')
    const class_name = ref('')
    const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0)
const expectedValue = ref('');
const dialogueBoxMessage = ref('')

// api twice call if we use const ref
let sort_by = 'id';
let sort_direction = 'desc';

// 👉 Fetching users


const fetchRoles = () => {
  is_loading.value = true;
  const data = {
		page:currentPage.value,	
		perPage:rowPerPage.value,	
		sortBy:sort_by,	
		sortDirection:sort_direction,	
		search:searchQuery.value
	}
  roleListStore.fetchRolesList(data).then(response => {
    rolesList.value = response.data.data;
    totalPage.value = response.data.pagination.last_page
    	currentPage.value = response.data.pagination.current_page;
		totalUsers.value = response.data.pagination.total;
    is_loading.value = false;
  }).catch(error => {
    console.error(error)
  })
}

const search = () => {
	console.log(searchQuery.value);
	fetchRoles();
}
const sorting = (value:any) => {
	sort_by = value;
	sort_direction = sort_direction == 'desc' ? 'asc' :'desc';
	fetchRoles();
}




const status = [
  { title: 'Pending', value: 'pending' },
  { title: 'Active', value: 'active' },
  { title: 'Inactive', value: 'inactive' },
]


const resolveUserStatusVariant = (stat: string) => {
  if (stat === 'pending')
    return 'warning'
  if (stat === 'active')
    return 'success'
  if (stat === 'inactive')
    return 'secondary'

  return 'primary'
}



// 👉 watching current page
watchEffect(() => {
  fetchRoles();
  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})

const delete_role_id = ref(0);
const openConfirmDialog = (user:any) => {
	isConfirmDialogOpen.value = true;
	confirmMessage.value = 'are_sure_you_delete';
	delete_role_id.value = user.id;
  expectedValue.value = user.title;

}


const deleteRole = () => {

  const id = delete_role_id.value;
	roleListStore.deleteRole(id).then((response:any) => {
		 isSnackbarVisible.value = true;
                    snackbarMessage.value =response.data.message;
                    class_name.value = 'success-snackbar';
	  fetchRoles();
	}).catch(error => {
		console.error(error);
	})

}



  // 👉 Computing pagination data
  const paginationData = computed(() => {
  const firstIndex = rolesList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
  const lastIndex = rolesList.value.length + ((currentPage.value - 1) * rowPerPage.value)

  return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})





const breadcrumbsData = ref({
        page_name: 'role_and_permission',
        name: 'role_and_permission',
    });

</script>

<template>
    <section>
      <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VRow>
           

            <VCol cols="12">
                <VCard>
                    <VCardText>
                        <VRow>
                            
                           <VCol cols="12" md="9">
                              <VRow>
                                <VCol cols="12" md="3">
                                  <div
										class="d-flex align-center">
										<span class="text-no-wrap me-3">{{$t('show')}}:</span>
											<VSelect
											v-model="rowPerPage"
											density="compact"
											:items="[10, 20, 30, 40, 50]"
											/>
										</div>
                                </VCol>
                                <VCol cols="12" md="3">
                                 
                                <VBtn
                                    prepend-icon="tabler-plus"
                                   :to="{name:'roles-permission-add'}"
                                >
                                    {{$t('add_role')}}
                                </VBtn>
                                </VCol>
                              </VRow>
                           </VCol>
                            
                            <VCol
                                cols="12"
                                sm="3"
                            >
                                <VTextField
                                v-model="searchQuery"
                                :label="$t('search')"
                                density="compact"
                                @keyup.enter="search()"
                                />
                            </VCol>
                            
                            

                        </VRow>
                    </VCardText>
                    
                    <VDivider />
                    <VCardText class="pt-0 pb-0">
                    <VTable class="text-no-wrap table-background">            
                        <thead class="text-uppercase">
                          <tr>
                              <th @click="sorting('title');" class="sorting">{{$t("role_title")}}
                                <VIcon 
                                  :size="16"
                                  :icon="(sort_by == 'title' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'title' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
                                  color="secondary"
                                  class="sorting-icon"
                                />
                              </th>
                              <th @click="sorting('slug');" class="sorting text-center">{{$t("slug")}}
                                <VIcon 
                                  :size="16"
                                  :icon="(sort_by == 'slug' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'slug' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
                                  color="secondary"
                                  class="sorting-icon"
                                />
                              </th>
                              <th  @click="sorting('is_parent');" class="sorting text-center">{{$t("parent")}}
                                <VIcon 
                                  :size="16"
                                  :icon="(sort_by == 'is_parent' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'is_parent' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
                                  color="secondary"
                                  class="sorting-icon"
                                />
                              </th>
                              <th class="text-center">{{$t("created_by")}}</th>
                              <th style="width:100px;text-align: center;">{{$t('action')}}</th>
                          </tr>
                        </thead>
                        <tbody   >
                        <tr v-for="user in rolesList"
                            :key="user.id" 
                                                     
                        >
                            <td>{{ user.title }}</td>
                            <td class="text-center">{{ user.slug }}</td>
                            <td class="text-center">{{ user.is_parent ? 'Yes' : 'No'}}</td>
                            <td class="text-center">{{ user.updated_at }}</td>
                          
                            <td class="text-center">

                              <VBtn
											icon
											variant="text"
											color="default"
											size="x-small"
										>
											<VIcon
												:size="22"
												icon="tabler-dots-vertical"
											/>
                      <VMenu activator="parent">
												<VList>
													<VListItem :to="{ name: 'roles-permission-edit-id', params: { id: user.id } }">
														<template #prepend >
															<VIcon
																size="24"
																class="me-3"
																icon="tabler-edit"
                                
															/>
														</template>
					  									<VListItemTitle>{{$t('edit')}}</VListItemTitle>
													</VListItem>

                          <VListItem @click="openConfirmDialog(user)">
														<template #prepend>
															<VIcon
																size="24"
																class="me-3"
																icon="tabler-trash"
                                
															/>
														</template>
					  									<VListItemTitle>{{$t('delete')}}</VListItemTitle>
													</VListItem>
                          </VList>
                        </VMenu>
                      </VBtn>
                              <!-- <VBtn
                                icon
                                variant="text"
                                color="default"
                                size="x-small"
                                
                              >
                                <VIcon
                                  icon="tabler-edit"
                                  :size="22"
                                />
                              </VBtn> -->
                              
                              <!-- <VBtn
                                icon
                                variant="text"
                                color="default"
                                size="x-small"
                                
                              >
                                <VIcon
                                  icon="tabler-trash"
                                  :size="22"
                                />
                              </VBtn>
                                     -->
                               
                            </td>
                        </tr>

                        </tbody>
                            <tfoot v-show="!rolesList.length">
                                <tr>
                                    <td
                                    colspan="6"
                                    class="text-center"
                                    >
                                    <div  class="loading-logo">
                                      <PageLoader></PageLoader>
                                    </div>
                                    <span v-show="!rolesList.length && !is_loading">{{$t('no_data_found')}}</span>
                                   
                                   
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

                        <VPagination
                        v-model="currentPage"
                        size="small"
                        :total-visible="5"
                        :length="totalPage"
                        />
                    </VCardText>
                   
                </VCard>
            </VCol>
        </VRow>


        	   <!-- Confirm Dialog -->
             <ConfirmDialog  v-model:isDialogVisible="isConfirmDialogOpen" :confirmation-msg="$t(`${dialogueBoxMessage}`)" v-model:expected-value="expectedValue"
            @confirm="deleteRole()" />

    <!-- SnackBar -->
    <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
    {{$t(snackbarMessage) }}
  </VSnackbar>

    
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
