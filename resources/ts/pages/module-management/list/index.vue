<script setup lang="ts">
import type { UserProperties } from '@/@fake-db/types'

import { useModuleStore } from './../view/modulesStore';
import { avatarText } from '@core/utils/formatters'
import type {ModulesParams} from './../view/types'
import moment from 'moment';
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
// 👉 Store

const moduleStore = useModuleStore()
const searchQuery = ref('')
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0);

const snackbarMessage = ref('');
    const confirmMessage = ref('')
	const class_name = ref('')

const isConfirmDialogOpen = ref(false)
const isSnackbarVisible = ref(false);

const modulesList = ref<ModulesParams[]>([]);

let sort_by = 'id';
let sort_direction = 'desc';

// 👉 Fetching users


const fetchModules = () => {
  const data = {
		page:currentPage.value,	
		perPage:rowPerPage.value,	
		sortBy:sort_by,	
		sortDirection:sort_direction,	
		search:searchQuery.value
	}
  moduleStore.fetchModulesList(data).then(response => {
    modulesList.value = response.data.data;
    console.log('module list',modulesList);
    
    // totalPage.value = response.data.totalPage
    // totalUsers.value = modulesList.value.length
    totalPage.value = response.data.pagination.last_page
    	currentPage.value = response.data.pagination.current_page;
		totalUsers.value = response.data.pagination.total;
  }).catch(error => {
    console.error(error)
  })
}
const search = () => {
	console.log(searchQuery.value);
	fetchModules();
}
const sorting = (value:any) => {
	sort_by = value;
	sort_direction = sort_direction == 'desc' ? 'asc' :'desc';
	fetchModules();
}


const delete_user_id = ref(0);
const openConfirmDialog = (id:any) => {
	isConfirmDialogOpen.value = true;
	confirmMessage.value = 'are_sure_you_delete';
	delete_user_id.value = id;

}
const deleteModules = (id:number) => {
	console.log(id);
	moduleStore.deleteModules(id).then((response:any) => {
		isSnackbarVisible.value = true;
		snackbarMessage.value = response.data.message;
		class_name.value = 'success-snackbar';
    fetchModules();
	}).catch(error => {
		console.error(error);
	})
	
}



// 👉 watching current page
watchEffect(() => {
  fetchModules();
  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})


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

const isAddNewUserDrawerVisible = ref(false)

// 👉 watching current page
watchEffect(() => {
  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})

  // 👉 Computing pagination data
  const paginationData = computed(() => {
  const firstIndex = modulesList.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
  const lastIndex = modulesList.value.length + ((currentPage.value - 1) * rowPerPage.value)

  return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})

const breadcrumbsData = ref({
        page_name: 'module_management',
        name: 'module_management',
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
                                  <VCol
                                      cols="12"
                                      md="3"
                                  >
                                      <VBtn
                                          prepend-icon="tabler-plus"
                                        :to="{name:'module-management-add'}"
                                      >
                                        {{$t('add_module')}}
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
                    <VCardText>
                    <VTable class="text-no-wrap table-background">            
                        <thead class="text-uppercase">
                          <tr>
                              <th  @click="sorting('title');" class="sorting">{{$t("role_title")}}
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
                              <th @click="sorting('parent_id');" class="sorting text-center">{{$t("parent")}}
                                <VIcon 
                                  :size="16"
                                  :icon="(sort_by == 'parent_id' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'parent_id' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
                                  color="secondary"
                                  class="sorting-icon"
                                />
                              </th>
                              <th @click="sorting('icon');" class="sorting text-center">{{$t("icon")}}
                                <VIcon 
											:size="16"
											:icon="(sort_by == 'icon' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'icon' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
											color="secondary"
											class="sorting-icon"
										/>
                              </th>
                              <th @click="sorting('roles');" class="sorting text-center">{{$t("roles")}}
                                <VIcon 
											:size="16"
											:icon="(sort_by == 'roles' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'roles' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
											color="secondary"
											class="sorting-icon"
										/>
                              </th>
                              <!-- <th>Order</th> -->
                              <th @click="sorting('updated_at');" class="sorting text-center">{{$t("update_time")}}
                                <VIcon 
											:size="16"
											:icon="(sort_by == 'updated_at' && sort_direction == 'desc')?'tabler-arrow-narrow-up' : ((sort_by == 'updated_at' && sort_direction == 'asc') ? 'tabler-arrow-narrow-down' : 'tabler-arrow-narrow-up')"
											color="secondary"
											class="sorting-icon"
										/>
                              </th>
                              <th class="text-center">{{$t("status")}}</th>
                              <th style="width:100px;text-align: center;">{{$t("action")}}</th>
                          </tr>
                        </thead>
                        <tbody   >
                        <tr v-for="module in modulesList"
                            :key="module.id" 
                                                     
                        >
                            <td>{{ module.title}}</td>
                            <td class="text-center">{{ module.slug}}</td>
                            <td class="text-center">{{ module.parent_id ? module.parent_id?.title : '-' }}</td>
                            <td class="text-center">{{ module.icon ? module.icon : '-'}}</td>
                            <td class="text-center">
                              <label v-for="role in module.role"> {{ role.title}}<br/>
                              </label></td>
                            <!-- <td><VListItemTitle>{{ module.order}}</VListItemTitle></td> -->
                            <td class="text-center">{{moment(module.updated_at).format('DD-MM-YYYY')}}</td>
                            <td class="text-center">
                              
                                <VChip
                                    
                                    :color="resolveUserStatusVariant(module.created_by)"
                                    size="small"
                                    class="text-capitalize"
                                    
                                >
                                <VIcon size="18" icon="tabler-circle-check" />
                                    {{ module.status ? $t("active") : $t("inactive")}}
                                </VChip>
                            </td>
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
													          <VListItem :to="{ name: 'module-management-edit-id', params: { id: module.id } }">
                                    <template #prepend >
                                      <VIcon
                                          icon="tabler-edit"
                                          :size="24"
                                          class="me-3"
                                        />
                                    </template>
					  									<VListItemTitle>{{$t('edit')}}</VListItemTitle>
													</VListItem>

                          <VListItem @click="openConfirmDialog(module.id)">
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
                                :to="{ name: 'module-management-edit-id', params: { id: module.id } }"
                              >
                                <VIcon
                                  icon="tabler-pencil"
                                  :size="22"
                                />
                              </VBtn>
                              
                              <VBtn
                                icon
                                variant="text"
                                color="default"
                                size="x-small"
                                @click="deleteModules(module.id)"
                              >
                                <VIcon
                                  icon="tabler-trash"
                                  :size="22"
                                />
                              </VBtn> -->
                                    
                               
                            </td>
                        </tr>

                        </tbody>
                            <tfoot v-show="!modulesList.length">
                                <tr>
                                    <td
                                    colspan="6"
                                    class="text-center"
                                    >
                                    {{$t("no_data_found")}}
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
          <ConfirmDialog
    v-model:isDialogVisible="isConfirmDialogOpen"
    v-model:confirmation-msg="confirmMessage"
	@confirm="deleteModules(delete_user_id)"
  />
 
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
