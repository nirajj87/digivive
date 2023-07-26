<script setup lang="ts">
    import  breadcrumbs  from '@/pages/breadcrumbs/detail/index.vue'

    let subtitles_arr:any= [1];
    const items = ['Hindi', 'English', 'Bangali', 'Punjabi']
</script>

<template>
    <section>
		<breadcrumbs />

        <VRow class="mb-5">
            <VCol cols="6">
                <VCard>
                    <VCardText>
                        video player
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <VCard class="mb-5">
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12" >
                        <h5>{{$t("full_name")}}</h5>
                        <p class="mb-0">Apaharan.mp4</p>
      	            </VCol>
                    <VCol cols="12" md="6" >
                        <h5>{{$t("duration")}}</h5>
                        <p class="mb-0">2hrs 38 Mins</p>
      	            </VCol>
                    <VCol cols="12" md="6" >
                        <h5>{{$t("file_size")}}</h5>
                        <p class="mb-0">336MB</p>
      	            </VCol>
                    <VCol cols="12" md="6" >
                        <h5>{{$t("upload_date")}}</h5>
                        <p class="mb-0">09-05-2023, 3:51 PM</p>
      	            </VCol>
                    <VCol cols="12" md="6" >
                        <h5>{{$t("last_modified_date")}}</h5>
                        <p class="mb-0">09-05-2023, 3:51 PM</p>
      	            </VCol>
                </VRow>
            </VCardText>
        </VCard>

        <VCard class="mb-5">
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h4>{{$t("subtitle")}}</h4>
                        <VTable class="table-background">
                            <thead>
                                <tr>
                                    <th>{{$t("subtitles_language")}}</th>
                                    <th class="text-center">{{$t("file_name")}}</th>
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
                                        <VFileInput  prepend-icon=""   label="Browse" />
                                    </td>
                                    <td class="text-center">
                                        <VBtn variant="text" color="primary" @click="subtitles_arr.pop();">{{$t("delete")}}</VBtn>
                                        <VBtn variant="text" color="primary">{{$t("save")}}</VBtn>
                                    </td>
					            </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th :colspan="3">
                                        <a href="javascript:void(0);" @click="subtitles_arr.push(1);">{{$t("add_more_subtitle")}}</a>
                                    </th>
                                </tr>
                            </tfoot>
                        </VTable>
                    </VCol>
                </VRow>

            </VCardText>
        </VCard>

        <VCard class="mb-5">
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h4>{{$t("transcoding_details")}}</h4>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <h5>{{$t("profile")}}</h5>
                        <p class="mb-0">360p, 480p, 720p, 1080p</p>
      	            </VCol>
                    <VCol
                        cols="12"
                        md="6"
                    >
                    <h5>{{$t("status")}}</h5>
                        <p class="mb-0"><b>{{$t("initiated")}} :</b> 09-05-2023, 06:00 PM</p>
                        <p><b>{{$t("inprogress")}} :</b> 09-05-2023, 06:20 PM</p>
                        
      	            </VCol>
                    
                </VRow>
            </VCardText>
        </VCard>

        <VCard class="mb-5">
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h4>{{$t("other_info")}}</h4>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <h5>{{$t("resolution")}}</h5>
                        <p class="mb-0">1280 x 720</p>
      	            </VCol>
                    <VCol cols="12" md="6">
                        <h5>{{$t("video_bit_rate")}}</h5>
                        <p class="mb-0">1061 kb/s</p>
                    </VCol>
                    <VCol cols="12" md="6">
                        <h5>{{$t("frame_rate")}}</h5>
                        <p class="mb-0">23.98 fps</p>
                    </VCol>
                    <VCol cols="12" md="6">
                        <h5>{{$t("audio_bit_rate")}}</h5>
                        <p class="mb-0">128 kb/s</p>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>

        <VBtn>
            {{$t("delete")}}
        </VBtn>
    </section>
</template>