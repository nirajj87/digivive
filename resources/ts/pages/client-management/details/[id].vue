<script setup lang="ts">
 
    import { useClientStore } from './../view/clientModulesStore';
    import { useRoute } from 'vue-router';
    import type {clientParams} from './../view/type'
    const clientStore = useClientStore();
    const route = useRoute();

    const moduleData =  ref<clientParams>();
    const getUserDetails = (id:number) => {
        clientStore.fetchUserDetails(id)
          .then((response:any) =>{
            console.log("Deti=====>",response.data.data);
           if(response.data.success) {
                moduleData.value = response.data.data;
                // console.log('moduleData',moduleData.value);
           }
          })
          .catch((e) => {
           
          })
    }

    watchEffect(() => {
        let userId = Number(route.params.id) ?? 0;
        if(userId) {
            getUserDetails(userId); 
        }
})
</script>

<template>
    <section>
	

        <VCard class="mb-5">
            <VCardText>
                <VRow>
                    <VCol
                        cols="12"
                        md="3"
                    >
                        <VListItemTitle>{{$t("Email")}}</VListItemTitle>
      	            </VCol>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <VListItemTitle>
                            <span class="text-body-2">{{moduleData?.email}}</span>
                        </VListItemTitle>
      	            </VCol>
                </VRow>
                
                <VRow>
                    <VCol
                        cols="12"
                        md="3"
                    >
                        <VListItemTitle>{{$t("name")}}</VListItemTitle>
      	            </VCol>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <VListItemTitle>
                            <span class="text-body-2">{{moduleData?.company_name}}</span>
                        </VListItemTitle>
      	            </VCol>
                </VRow>

                <VRow>
                    <VCol
                        cols="12"
                        md="3"
                    >
                        <VListItemTitle>{{$t("status")}}</VListItemTitle>
      	            </VCol>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <VListItemTitle>
                            <span class="text-body-2">{{moduleData?.status ? 'Active' : 'Inactive'}}</span>
                        </VListItemTitle>
      	            </VCol>
                </VRow>
            </VCardText>
        </VCard>

        <VCard>
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <VListItemTitle>All Permissions</VListItemTitle>

                        <VTable class="table-background td_first">
                            <thead>
                                <tr>
                                    <th><b>Module Name</b></th>
                                    <th class="text-center">Add</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                          
                        </VTable>

                        <VTable class="child-table table-background td_first" v-for="module,index in moduleData?.permission"
                            :key="index">
                            <tbody>
                                <tr v-for="(value, key) in module.privileges">
                                    <td><b>{{module.title}}</b></td>
                                    <td class="text-center">
                                        <VIcon
                                            :size="22"
                                            :icon="module.privileges[key].is_add ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <VIcon
                                            :size="22"
                                            :icon="module.privileges[key].is_view ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <VIcon
                                            :size="22"
                                            :icon="module.privileges[key].is_edit ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <VIcon
                                            :size="22"
                                            :icon="module.privileges[key].is_delete ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                </tr>
                                <tr v-for="sub_module in module.sub_modules"
                                    :key="sub_module.id">
                                    <td>{{sub_module.title}} </td>
                                 
                                    <td class="text-center"  v-for="(value, key) in sub_module.privileges">
                                        <VIcon
                                            :size="22"
                                            :icon="sub_module.privileges[key].is_add ? 'tabler-check' : 'tabler-x'"
                                        />
                                        
                                    </td>
                                    <td class="text-center" v-for="(value, key) in sub_module.privileges">
                                        <VIcon
                                            :size="22"
                                            :icon="sub_module.privileges[key].is_view ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                    <td class="text-center" v-for="(value, key) in sub_module.privileges">
                                        <VIcon
                                            :size="22"
                                             :icon="sub_module.privileges[key].is_edit ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                    <td class="text-center" v-for="(value, key) in sub_module.privileges">
                                        <VIcon
                                            :size="22"
                                             :icon="sub_module.privileges[key].is_delete ? 'tabler-check' : 'tabler-x'"
                                        />
                                    </td>
                                </tr>
                               
                            </tbody>
                            </VTable>

                        

                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>