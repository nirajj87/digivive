<script setup lang="ts">

import type { UserProperties } from '@/@fake-db/types'
import { useUserListStore } from '@/views/apps/user/useUserListStore'
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'


import tableimg from '@images/pages/1.png'

// 👉 Store
const skill = ref(20)
const userListStore = useUserListStore()
const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalUsers = ref(0)
let subtitles_arr:any= [1];
const users = ref<UserProperties[]>([])

// 👉 Fetching users

const router = useRouter();
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
    	totalPage.value = response.data.totalPage
    	totalUsers.value = response.data.totalUsers
  	}).catch(error => {
    	console.error(error)
 	 })
}

	const addSubtitle = () => {
		subtitles_arr.push(1);
	}

	const redirectToDetail = () => {
		router.replace('/video-library/details/1');
	}
	const openSubtitle = () => {
		isDialogVisible = ref(true);
		subtitles_arr = [1];
	}

// 	data: (instance) => ({
//    selectPackage: instance.propPackage,
// }),


watchEffect(fetchUsers)

const status = [
  	{ title: 'Pending', value: 'pending' },
  	{ title: 'Active', value: 'active' },
  	{ title: 'Inactive', value: 'inactive' },
]

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = users.value.length ? ((currentPage.value - 1) * rowPerPage.value) + 1 : 0
  const lastIndex = users.value.length + ((currentPage.value - 1) * rowPerPage.value)

  return `Showing ${firstIndex} to ${lastIndex} of ${totalUsers.value} entries`
})

let isDialogVisible = ref(false)
const items = ['Hindi', 'English', 'Bangali', 'Punjabi']

const modified =['Today', 'Yesterday', 'This Week', 'Last Week', 'This Month', 'Last Month', 'Custom']
const videostatus =['Transcoding Not Started', 'Transcoding In Progress', 'Transcoding Completed', 'Transcoding Failed', 'Uploading Error']
const videoduration =['Less than 5 minutes', 'Less than 30 minutes', 'Less than 120 minutes', 'More than 120 minutes ']
const videofilesize =['All', 'Less than 100 Mb', 'Less than 500 Mb', 'Less than 1 GB', 'More than 1 GB']

const breadcrumbsData = ref({
        page_name: 'video_library',
        name: 'video_library',
    });
</script>

<template>
    <section>
		<breadcrumbs :breadcrumbs-data="breadcrumbsData" />

		<VCard class="mb-5">
			<VCardText>
				<VRow>
                    <VCol cols="12">
						<VFileInput  color="primary"  label="Upload Video" />
					</VCol>
					<VCol cols="12" sm="3">
						<div class="d-flex align-center">
							<VImg :src="tableimg" class="table-img" />
							<div>
								<VListItemTitle>Mirzapur.mp4</VListItemTitle>
								<VListItemSubtitle>{{$t("duration")}}: 02:50:45</VListItemSubtitle>
							</div>
						</div>
					</VCol>
					<VCol cols="12" sm="7">
						<VListItemTitle class="text-right">{{$t("uploading")}} (34%) - 31 {{$t("mb")}} {{$t("of")}} 354 {{$t("mb")}}</VListItemTitle>
						<VProgressLinear
							v-model="skill"
							color="primary"
							height="15"
						>
							<template #default="{ value }">
								<!-- <strong>{{ Math.ceil(value) }}%</strong> -->
							</template>
						</VProgressLinear>
					</VCol>
					<VCol cols="12" sm="2">
						<VBtn variant="text" color="error" class="text-medium-emphasis">{{$t("cancel_upload")}}</VBtn>
					</VCol>
				</VRow>
			</VCardText>
		</VCard>
					
		<VCard class="mb-5">
			<VCardText>
				<VRow>
                    <VCol cols="12" sm="3">
						<VSelect
							:items="modified"
							label="Last Modified"
						/>
					</VCol>
					<VCol cols="12" sm="3">
						<v-select
							:items="videostatus"
							label="Status"
							multiple
        				>
							<template v-slot:selection="{ item, index }">
								<v-chip v-if="index < 1">
									<span>{{ item.title }}</span>
								</v-chip>
								<span
									v-if="index === 1"
									class="text-grey text-caption align-self-center"
									>
									(+{{ videostatus.length - 1 }} others)
								</span>
							</template>
						</v-select>
					</VCol>
					<VCol cols="12" sm="3">
						<VSelect
							:items="videoduration"
							label="Duration"
						/>
					</VCol>
					<VCol cols="12" sm="3">
						<VSelect
							:items="videofilesize"
							label="File Size"
						/>
					</VCol>
				</VRow>
				<VRow>
					<VCol cols="12">
						<ul class="filter-nav">
							<li>
								<span>filter name</span>
								<a href="javascript:void(0);">x</a>
							</li>
							<li>
								<span>filter name</span>
								<a href="javascript:void(0);">x</a>
							</li>
							<li>
								<span>filter name</span>
								<a href="javascript:void(0);">x</a>
							</li>
							<li>
								<span>filter name</span>
								<a href="javascript:void(0);">x</a>
							</li>
						</ul>
					</VCol>
				</VRow>
			</VCardText>
		</VCard>

		<VCard>
        	<VRow>
            	<VCol cols="12">
                
                    <VCardText>
                        <VRow>
                            <VCol cols="12" sm="2">
                              <div
                                class="d-flex align-center">
                                  <span class="text-no-wrap me-3">{{$t("show")}}:</span>
                                    <VSelect
                                      v-model="rowPerPage"
                                      density="compact"
                                      :items="[10, 20, 30, 40, 50]"
                                    />
                                </div>
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
							<VCol
                                cols="12"
                                sm="5"
                            >
                                <VBtn
									class="mr-5"
								>
									{{$t("transcode_selected")}}
								</VBtn>
								<VBtn>
									{{$t("delete_selected")}}
								</VBtn>
                            </VCol>
                        </VRow>
                    </VCardText>
                    
                    <VDivider />
					
                    <VCardText>
                    	<VTable class="table-background">            
							<thead class="text-uppercase">
								<tr>
									<th style="width: 40px;"><VCheckbox /></th>
									<th>{{$t("file_name")}}
										<VBtn
											icon
											variant="plain"
											color="default"
											size="x-small"
										>
											<VIcon
												:size="22"
												icon="tabler-filter"
											/>

											<VMenu activator="parent">
												<VList density="compact">
													<VListItem
														title="Title"
														class="table-filter"
													>
														<a href="javascript:void(0);">A-Z</a>
														<VDivider />
														<a href="javascript:void(0);">Z-A</a>
													</VListItem>
													<VListItem
														title="Duration"
														class="table-filter"
														@click="openSubtitle()"
													>
														<a href="javascript:void(0);">Ascending</a>
														<VDivider />
														<a href="javascript:void(0);">Descending</a>
													</VListItem>
													<VListItem
														title="File Size"
														class="table-filter"
													>
														<a href="javascript:void(0);">Ascending</a>
														<VDivider />
														<a href="javascript:void(0);">Descending</a>
													</VListItem>
												</VList>

												
											</VMenu>
										</VBtn>
									</th>
									<th class="text-center">{{$t("status")}}</th>
									<th  class="text-center">{{$t("sub_title")}}</th>
									<th>
										{{$t("last_modified")}}
										
										<VBtn
											icon
											variant="plain"
											color="default"
											size="x-small"
										>
											<VIcon
												:size="22"
												icon="tabler-filter"
											/>

											<VMenu activator="parent">
												<VList density="compact">
													<VListItem
														title="Sort"
														class="table-filter"
													>
														<a href="javascript:void(0);">Newest First</a>
														<VDivider />
														<a href="javascript:void(0);">Oldest First</a>
													</VListItem>
													
													
												</VList>

												
											</VMenu>
										</VBtn>
									</th>
									
									<th style="width:100px;text-align: right;">{{$t("Action")}}</th>
								</tr>
							</thead>
							<tbody>
								<!-- <tr>
									<td><VCheckbox /></td>
									<td>
										<div class="d-flex align-center">
											<VImg :src="tableimg" class="table-img" />
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45</VListItemSubtitle>
											</div>
										</div>
									</td>
									<td colspan="3">
										<VListItemTitle class="text-right">Uploading (34%) - 31 MB of 354 MB</VListItemTitle>
										<VProgressLinear
											v-model="skill"
											color="primary"
											height="15"
										>
											<template #default="{ value }">
												
											</template>
										</VProgressLinear>
									</td>
									<td>
										<VBtn variant="plain" color="error" class="text-medium-emphasis">Cancel Upload</VBtn>
									</td>
								</tr> -->

								<tr >
									<td><VCheckbox /></td>

									<td @click="redirectToDetail()">
										<div class="d-flex align-center">
											<div class="video-icon">
												<VImg :src="tableimg" class="table-img" />
												<a href="javascript:void(0);">
													<VIcon
														:size="25"
														icon="tabler-player-play"
													/>
												</a>
											</div>
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45 687MB</VListItemSubtitle>
											</div>
										</div>
									</td>

									<td class="text-center" @click="redirectToDetail()">
										<VListItemSubtitle>Transcoding In Progress</VListItemSubtitle>
										<VListItemSubtitle>ETA - 01:21:45 hrs</VListItemSubtitle>
									</td>

									<td class="text-center" @click="redirectToDetail()">
										<VListItemSubtitle>English, Hindi, Punjabi</VListItemSubtitle>
									</td>

									<td class="text-center" @click="redirectToDetail()"><VListItemSubtitle>22/06/22, 4:00 PM</VListItemSubtitle></td>
									
									<td class="text-right" @click="redirectToDetail()">
										<VBtn
											icon
											variant="plain"
											color="default"
											size="x-small"
										>
											<VIcon
												:size="22"
												icon="tabler-dots-vertical"
											/>

											<VMenu activator="parent">
												<VList density="compact">
													<VListItem
														href="#"
														title="Delete"
													/>
													<VListItem
														@click="isDialogVisible = true;"
														href="javascript:void(0);"
														title="Add Subtitles"
													/>

													
												</VList>

												
											</VMenu>
										</VBtn>
										
          							</td>
									
								</tr>

								<tr>
									<td><VCheckbox /></td>
									<td>
										<div class="d-flex align-center">
											<div class="video-icon">
												<VImg :src="tableimg" class="table-img" />
												<a href="javascript:void(0);">
													<VIcon
														:size="25"
														icon="tabler-player-play"
													/>
												</a>
											</div>
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45 687MB</VListItemSubtitle>
											</div>
										</div>
									</td>
									<td class="text-center">
										<VListItemSubtitle>Transcoding Not Started</VListItemSubtitle>
										
									</td>
									<td class="text-center"><VListItemSubtitle>English, Hindi, Punjabi and
										
										
										
										<VTooltip>
											<template #activator="{ props }">
												<a
													href="javascript:void(0);"
													v-bind="props"
												>												
													4 More
												</a>
											</template>
											<VListItemSubtitle>Telugu</VListItemSubtitle>
											<VListItemSubtitle>Assamese</VListItemSubtitle>
											<VListItemSubtitle>Gujarati</VListItemSubtitle>
											<VListItemSubtitle>Mizo</VListItemSubtitle>
            							</VTooltip>
									
									
									
									</VListItemSubtitle></td>
									<td class="text-center"><VListItemSubtitle>22/06/22, 4:00 PM</VListItemSubtitle></td>
									<td class="text-right">
														<VBtn
															icon
															variant="plain"
															color="default"
															size="x-small"
														>
															<VIcon
																:size="22"
																icon="tabler-dots-vertical"
															/>

										<VMenu activator="parent">
											<VList density="compact">
											<VListItem
												href="#"
												title="Delete"
											/>
											<VListItem
												href="#"
												title="Add Subtitles"
											/>
											</VList>
										</VMenu>
										</VBtn>
          							</td>
								</tr>

								<tr>
									<td><VCheckbox /></td>
									<td>
										<div class="d-flex align-center">
											<div class="video-icon">
												<VImg :src="tableimg" class="table-img" />
												<a href="javascript:void(0);">
													<VIcon
														:size="25"
														icon="tabler-player-play"
													/>
												</a>
											</div>
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45 687MB</VListItemSubtitle>
											</div>
										</div>
									</td>
									<td class="text-center">
										<VListItemSubtitle>Transcoding Completed</VListItemSubtitle>
										
									</td>
									<td class="text-center"><VListItemSubtitle>-</VListItemSubtitle></td>
									<td class="text-center"><VListItemSubtitle>22/06/22, 4:00 PM</VListItemSubtitle></td>
									<td class="text-right">
														<VBtn
															icon
															variant="plain"
															color="default"
															size="x-small"
														>
															<VIcon
																:size="22"
																icon="tabler-dots-vertical"
															/>

										<VMenu activator="parent">
											<VList density="compact">
											<VListItem
												href="#"
												title="Delete"
											/>
											<VListItem
												href="#"
												title="Add Subtitles"
											/>
											</VList>
										</VMenu>
										</VBtn>
          							</td>
								</tr>
								
								<tr>
									<td><VCheckbox /></td>
									<td>
										<div class="d-flex align-center">
											<div class="video-icon">
												<VImg :src="tableimg" class="table-img" />
												<a href="javascript:void(0);">
													<VIcon
														:size="25"
														icon="tabler-player-play"
													/>
												</a>
											</div>
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45 687MB</VListItemSubtitle>
											</div>
										</div>
									</td>
									<td class="text-center">
										<VListItemSubtitle>Uploading Error 
											
											<VBtn variant="text" color="primary">Upload Again</VBtn>
										
										</VListItemSubtitle>
										
									</td>
									<td class="text-center"><VListItemSubtitle>-</VListItemSubtitle></td>
									<td class="text-center"><VListItemSubtitle>22/06/22, 4:00 PM</VListItemSubtitle></td>
									<td class="text-right">
														<VBtn
															icon
															variant="plain"
															color="default"
															size="x-small"
														>
															<VIcon
																:size="22"
																icon="tabler-dots-vertical"
															/>

										<VMenu activator="parent">
											<VList density="compact">
											<VListItem
												href="#"
												title="Delete"
											/>
											<VListItem
												href="#"
												title="Add Subtitles"
											/>
											</VList>
										</VMenu>
										</VBtn>
          							</td>
								</tr>

								<tr>
									<td><VCheckbox /></td>
									<td>
										<div class="d-flex align-center">
											<div class="video-icon">
												<VImg :src="tableimg" class="table-img" />
												<a href="javascript:void(0);">
													<VIcon
														:size="25"
														icon="tabler-player-play"
													/>
												</a>
											</div>
											<div>
												<VListItemTitle>Mirzapur.mp4</VListItemTitle>
												<VListItemSubtitle>Duration: 02:50:45 687MB</VListItemSubtitle>
											</div>
										</div>
									</td>
									<td class="text-center">
										<VListItemSubtitle>Transcoding Failed
											<VBtn variant="text" color="primary">Retry</VBtn>
										</VListItemSubtitle>
										
									</td>
									<td class="text-center"><VListItemSubtitle>-</VListItemSubtitle></td>
									<td class="text-center"><VListItemSubtitle>22/06/22, 4:00 PM</VListItemSubtitle></td>
									<td class="text-right">
														<VBtn
															icon
															variant="plain"
															color="default"
															size="x-small"
														>
															<VIcon
																:size="22"
																icon="tabler-dots-vertical"
															/>

										<VMenu activator="parent">
											<VList density="compact">
											<VListItem
												href="#"
												title="Delete"
											/>
											<VListItem
												href="#"
												title="Add Subtitles"
											/>
											</VList>
										</VMenu>
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
            	</VCol>
        	</VRow>
		</VCard>
		<VDialog
  	v-model="isDialogVisible"
		class="v-dialog-md"
	>
		<!-- Dialog close btn -->
		<VBtn
			icon
			class="v-dialog-close-btn"
			@click="isDialogVisible = !isDialogVisible"
			>
			<VIcon icon="tabler-x" />
		</VBtn>
		<VCard title="Add Subtitles for Anokhi paheli">
									
			<VTable class="table-background">            
				<thead class="text-uppercase">
					<tr>
						<th  class="text-center">{{$t("subtitles_language")}}</th>
						<th  class="text-center">{{$t("file_name")}}</th>
						<th class="text-center">{{$t("action")}}</th>
					</tr>
				</thead>
				<tbody>
					<tr  v-for="subtitle in subtitles_arr">
						<td>
							<VSelect
								
								:items="items"
								label="Select Subtitle"
							/>
						</td>
						<td>
							<VFileInput  prepend-icon=""  variant="solo"  class="file-input" label="Browse" />
						</td>
						<td class="text-center" v-if="subtitles_arr.length > 1">
							<a href="javascript:void(0);" @click="subtitles_arr.pop();">{{$t("delete")}}</a>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th :colspan="2">
							<a href="javascript:void(0);" @click="subtitles_arr.push(1);">{{$t("add_more_subtitle")}}</a>
						</th>
						<th class="text-right"><VBtn color="primary">{{$t("save")}}</VBtn></th>
					</tr>
				</tfoot>
			</VTable>
		</VCard>
	</VDialog>
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
	.v-field.v-field--appended{
		--v-field-padding-end: 0px;
	}
</style>