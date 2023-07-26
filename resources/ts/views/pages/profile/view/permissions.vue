<script lang="ts" setup>
   import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import type {permissionParams} from './../types'
// 👉 Store
const userProfileStore = useUserProfileStore()

const route = useRoute()
const permissions =  ref<permissionParams>();
const userId = Number(route.params.id) ?? 0;
userProfileStore.getPermissions(userId).then(response => {
    console.log(' response.data', response.data.data);
    permissions.value = response.data.data
})


</script>

<template>
    <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12">
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>{{$t("permissions")}}</h5>
                                </VCol>

                                <VCol cols="12">
                                    <VTable class="table-background td_first">
                                        <thead>
                                            <tr>
                                                <th><b>{{ $t("module_name") }}</b></th>
                                                <th class="w100_table">{{ $t("add") }}</th>
                                                <th class="w100_table">{{ $t("view") }}</th>
                                                <th class="w100_table">{{ $t("edit") }}</th>
                                                <th class="w100_table">{{ $t("delete") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="child-table table-background td_first" v-for="module,index in permissions"  :key="index">
                                            <tr  v-for="(value, key) in module.privileges">
                                                <td colspan="5">
                                                    <VTable>
                                                        <tr>
                                                            <td><b>{{module.title}}</b></td>
                                                            <td class="w100_table">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="module.privileges[key].is_add ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="module.privileges[key].is_view ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="module.privileges[key].is_edit ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="module.privileges[key].is_delete ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                        </tr>
                                                        <tr v-for="sub_module in module.sub_modules" :key="sub_module.id">
                                                            <td>{{sub_module.title}} </td>
                                                            <td class="w100_table"  v-for="(value, key) in sub_module.privileges">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="sub_module.privileges[key].is_add ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table" v-for="(value, key) in sub_module.privileges">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="sub_module.privileges[key].is_view ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table" v-for="(value, key) in sub_module.privileges">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="sub_module.privileges[key].is_edit ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                            <td class="w100_table" v-for="(value, key) in sub_module.privileges">
                                                                <VIcon
                                                                    :size="18"
                                                                    :icon="sub_module.privileges[key].is_delete ? 'tabler-check' : 'tabler-x'"
                                                                />
                                                            </td>
                                                        </tr>
                                                    </VTable>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </VTable>    
                                </VCol>
                                <VCol cols="12">
                                    <VBtn 
                                        prepend-icon="tabler-edit"
                                        :to="{ name: 'edit-profile-tab-uid',
                                        params: {tab:'permissions', uid: userId} }" >
                                        {{$t('edit_permission')}}
                                    </VBtn>
                                </VCol>
                         </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
        </VCardText>
    </section>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}
</style>