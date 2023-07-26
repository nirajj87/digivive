<script lang="ts" setup>
    const rowPerPage = ref(10)
	const route = useRoute();
    const userId = Number(route.params.id) ?? 0;
	
    import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
    const breadcrumbsData = ref({
        page_name: 'email_template',
        name: 'email_template',
    });

</script>

<template>
     <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
            <VRow>
                <VCol cols="12" >
                    
                        <!-- <h4 class="mb-2">{{ $t("sms_settings") }}</h4> -->
                    
                        <VCardText>
                            <VRow>
							    <VCol cols="12" md="9">
								<VRow>
									<VCol cols="12" md="3">
										<div class="d-flex align-center">
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
											:to="{ name: 'notification-sms-add',
                                            }"
										>
										{{$t('add_template')}}
										</VBtn>
									</VCol>
								</VRow>
							</VCol>
                            <VCol
                                cols="12"
                                md="3"
                            >
                                <VTextField
                                v-model="searchQuery"
                                label="Search"
                                density="compact"
								@keyup.enter="search()"
                                />
                            </VCol>
                            
                        </VRow>
                    

                    </VCardText>

                    <VDivider />
                        <VCardText class="pt-0">
                            <VTable class="table-background">
                                <thead>
                                    <tr>
                                        <th>{{ $t("title") }}</th>
                                        <th>{{ $t("template") }}</th>
                                        <th>{{ $t("variables") }}</th>
                                        <th>{{ $t("action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SMS title</td>
                                        <td>SMS template</td>
                                        <td>Email variables</td>
                                        <td>
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
													<VListItem :to="{ name: 'notification-sms-edit-id', params: { id: 1} }">
														<template #prepend>
															<VIcon
																size="24"
																class="me-3"
																icon="tabler-edit"
															/>
														</template>
					  									<VListItemTitle>{{$t('edit')}}</VListItemTitle>
													</VListItem>
													<VListItem>
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
                                        </td>
                                    </tr>
                                </tbody>
                            </VTable>
                        </VCardText>
                    
                    
                </VCol>
            </VRow>
        </VCard>
    </section>
</template>