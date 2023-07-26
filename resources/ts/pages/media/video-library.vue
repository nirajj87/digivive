<script setup lang="ts">

import * as demoCode from '@/views/demos/forms/form-elements/select/demoCodeSelect'
import AnalyticsProjectTable from '@/views/dashboards/analytics/AnalyticsProjectTable.vue'

const items = ['Foo', 'Bar', 'Fizz', 'Buzz']


</script>
<template>
    <VRow>
        <VCol cols="12" md="9">
            <VMain>{{$t("Video_Library")}}</VMain>
            <VBreadcrumbs>
                
                <VBreadcrumbsItem>home</VBreadcrumbsItem>
                <VBreadcrumbsItem>Video Management</VBreadcrumbsItem>
            </VBreadcrumbs>
            <!-- <ul>
                <li><a href="">Home</a></li>
                <li>Video Management</li>
            </ul> -->
        </VCol>
        <VCol cols="12" md="3">
            
            <VFileInput label="upload video" />


            <!-- <VBtn color="primary">
                Upload Video
            </VBtn> -->
        </VCol>
    </VRow>

    <VRow>
        <VCol cols="12" md="3">
            <VSelect
                :items="items"
                label="Test"
            />
        </VCol>
    </VRow>

    <VRow>
        <VCol cols="12" md="12">

            <VTable class="text-no-wrap">
                <thead>
                    <tr>
                        <th>
                            <VCheckbox />
                        </th>
                        <th>File Name</th>
                        <th>Status</th>
                        <th>Subtitles</th>
                        <th>Last Modified</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><VCheckbox /></td>
                        <td>Name</td>
                        <td colspan="3">
                            <DemoProgressLinearSlots />
                        </td>
                        <td>
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
                    title="Sub Tittle"
                  />
                </VList>
              </VMenu>
            </VBtn>
          </td>
                    </tr>
                </tbody>
            </VTable>
            <!-- <AnalyticsProjectTable /> -->
        </VCol>
    </VRow>
</template>

<style>
</style>