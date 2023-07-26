<script setup lang="ts">
import type { UserProperties } from '@/@fake-db/types'
import { useUserListStore } from '@/views/apps/user/useUserListStore'
import { avatarText } from '@core/utils/formatters'

// 👉 Store
const userListStore = useUserListStore()
const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0)
const users = ref<UserProperties[]>([])

// 👉 Fetching users

const fetchUsers = () => {
  userListStore.fetchUsers({
    q: searchQuery.value,
    status: selectedStatus.value,
    plan: selectedPlan.value,
    role: selectedRole.value,
    perPage: rowPerPage.value,
    currentPage: currentPage.value,
  }).then(response => {
    users.value = response.data.users
   
    console.log("Users=========>",response.data);
    totalPage.value = response.data.totalPage

    totalUsers.value = response.data.totalUsers
  }).catch(error => {
    console.error(error)
  })
}

watchEffect(fetchUsers)

// 👉 watching current page
watchEffect(() => {
  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})

// 👉 search filters
const roles = [
  { title: 'Admin', value: 'admin' },
  { title: 'Author', value: 'author' },
  { title: 'Editor', value: 'editor' },
  { title: 'Maintainer', value: 'maintainer' },
  { title: 'Subscriber', value: 'subscriber' },
]



const status = [
  { title: 'Pending', value: 'pending' },
  { title: 'Active', value: 'active' },
  { title: 'Inactive', value: 'inactive' },
]

const resolveUserRoleVariant = (role: string) => {
  if (role === 'subscriber')
    return { color: 'warning', icon: 'tabler-user' }
  if (role === 'author')
    return { color: 'success', icon: 'tabler-circle-check' }
  if (role === 'maintainer')
    return { color: 'primary', icon: 'tabler-chart-pie-2' }
  if (role === 'editor')
    return { color: 'info', icon: 'tabler-pencil' }
  if (role === 'admin')
    return { color: 'secondary', icon: 'tabler-device-laptop' }
  return { color: 'primary', icon: 'tabler-user' }
}

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
  const firstIndex = users.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
  const lastIndex = users.value.length + ((currentPage.value - 1) * rowPerPage.value)

  return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})

// 👉 Add new user
const addNewUser = (userData: UserProperties) => {
  userListStore.addUser(userData)

  // refetch User
  fetchUsers()
}

// 👉 List
const userListMeta = [
  {
    icon: 'tabler-user',
    color: 'primary',
    title: 'Session',
    stats: '21,459',
    percentage: +29,
    subtitle: 'Total Users',
  },
  {
    icon: 'tabler-user-plus',
    color: 'error',
    title: 'Paid Users',
    stats: '4,567',
    percentage: +18,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-check',
    color: 'success',
    title: 'Active Users',
    stats: '19,860',
    percentage: -14,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-exclamation',
    color: 'warning',
    title: 'Pending Users',
    stats: '237',
    percentage: +42,
    subtitle: 'Last week analytics',
  },
]
</script>

<template>
    <section>
        <VRow>
            <VCol
                key="meta.title"
                cols="12"
                sm="6"
                lg="12"
                >
                <VCard>
                    <VCardText class="d-flex justify-space-between">
                        <div>
                            <div class="d-flex align-center gap-2 my-1">
                                <span class="font-weight-semibold">{{$t("App_Management")}}</span>
                            </div>
                            <span>
                                <VBreadcrumbs class="pl-0">
                                    <VBreadcrumbsItem class="pl-0"><a href="#">{{$t("home")}}</a></VBreadcrumbsItem>
                                    <VBreadcrumbsDivider>
                                        <VIcon
                                            size="20"
                                            icon='tabler-chevron-left'
                                        />
                                    </VBreadcrumbsDivider>
                                    <VBreadcrumbsItem>{{$t("App_Management")}}</VBreadcrumbsItem>
                                </VBreadcrumbs>
                            </span>
                        </div>
                    </VCardText>
                </VCard>
            </VCol>

            <VCol cols="12">
                <VCard>
                    <VCardText>
                        <VRow>
                            <VCol cols="12" sm="2">
                              <div
                                class="d-flex align-center">
                                  <span class="text-no-wrap me-3">Show:</span>
                                    <VSelect
                                      v-model="rowPerPage"
                                      density="compact"
                                      :items="[10, 20, 30, 50]"
                                    />
                                </div>
                            </VCol>
                            <VCol
                                cols="12"
                                sm="2"
                            >
                                <VBtn
                                    prepend-icon="tabler-plus"
                                   :to="{name:'app-management-add'}"
                                >
                                    Add 
                                </VBtn>
                            </VCol>
                            <VCol
                                cols="12"
                                sm="3"
                            >
                                <VSelect
                                    :items="status"
                                    label="Status"
                                />
                            </VCol>
                            <VCol
                                cols="12"
                                sm="3"
                            >
                                <VTextField
                                v-model="searchQuery"
                                label="Search"
                                density="compact"
                                />
                            </VCol>
                            
                        </VRow>
                    </VCardText>
                    
                    <VDivider />
                    <VCardText>
                    <VTable class="text-no-wrap table-background">            
                        <thead class="text-uppercase">
                          <tr>
                              <th>{{$t("role title")}}</th>
                              <th>{{$t("slug")}}</th>
                              <th>{{$t("parent")}}</th>
                              <th>{{$t("created by")}}</th>
                              <th style="width:100px;text-align: center;">Status</th>
                              <th style="width:100px;text-align: center;">Actions</th>
                          </tr>
                        </thead>
                        <tbody   >
                        <tr v-for="user in users"
                            :key="user.id" 
                                                     
                        >
                            <td><VListItemTitle class="text-base font-weight-medium"><h6 class="text-base font-weight-medium">{{ user.fullName }}</h6></VListItemTitle></td>
                            <td><VListItemTitle>{{ user.role }}</VListItemTitle></td>
                            <td><VListItemTitle>{{ user.currentPlan }}</VListItemTitle></td>
                            <td><VListItemTitle>{{ user.billing }}</VListItemTitle></td>
                            <td class="text-center">
                              
                                <VChip
                                    
                                    :color="resolveUserStatusVariant(user.status)"
                                    size="small"
                                    class="text-capitalize"
                                    
                                >
                                <VIcon size="18" icon="tabler-circle-check" />
                                    {{ user.status }}
                                </VChip>
                            </td>
                            <td class="text-center">
                              <VBtn
                                icon
                                variant="text"
                                color="default"
                                size="x-small"
                                :to="{ name: 'app-management-edit-id', params: { id: user.id } }"
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
                                
                              >
                                <VIcon
                                  icon="tabler-trash"
                                  :size="22"
                                />
                              </VBtn>
                                    
                               
                            </td>
                        </tr>

                        </tbody>
                            <tfoot v-show="!users.length">
                                <tr>
                                    <td
                                    colspan="6"
                                    class="text-center"
                                    >
                                    No data available
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

    <!-- 👉 Add New User -->
    <!-- <AddNewUserDrawer
      v-model:isDrawerOpen="isAddNewUserDrawerVisible"
      @user-data="addNewUser"
    /> -->
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
