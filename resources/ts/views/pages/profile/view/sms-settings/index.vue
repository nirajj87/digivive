<script lang="ts" setup>
    const rowPerPage = ref(10)
	const route = useRoute();
    const userId = Number(route.params.id) ?? 0;
	
</script>

<template>
     <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12" >
                    <h4 class="mb-2">{{ $t("sms_settings") }}</h4>
                    <VCard border class="mb-5" >
                        <VCardText>
                        <VRow>
							<VCol cols="12" md="8">
								<VRow>
									<VCol cols="12" md="5">
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
										md="5"
									>
										<VBtn
											prepend-icon="tabler-plus"
											:to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'sms-settings',userid:userId} }"
										>
										{{$t('add_template')}}
										</VBtn>
									</VCol>
								</VRow>
							</VCol>
                            <VCol
                                cols="12"
                                md="4"
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
                        <VCardText>
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
													<VListItem>
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
                    </VCard>
                </VCol>
            </VRow>
        </VCardText>
    </section>
</template>