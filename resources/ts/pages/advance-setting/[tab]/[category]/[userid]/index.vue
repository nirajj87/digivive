<script setup lang="ts">
import UserEditProfile from '@/views/pages/profile/edit/profile.vue';
import EditSecurity from '@/views/pages/profile/edit/security.vue'
import EditBilling from '@/views/pages/profile/edit/billing.vue'
import EditPermissions from '@/views/pages/profile/edit/permissions.vue'
import EditAdvanceSetting from '@/views/pages/profile/edit/advance-settings.vue'

   
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'

const route = useRoute()
const activeTab = ref(route.params.tab)
const categoryTab = ref(route.params.category)
// alert(categoryTab.value)
  const tabs = [
  { title: 'profile_details', icon: 'tabler-user-check', tab: 'profile-details'},
  { title: 'billing', icon: 'tabler-building-bank', tab: 'billing' },
  { title: 'security', icon: 'tabler-settings', tab: 'security' },
  { title: 'permissions', icon: 'tabler-circle-key', tab: 'permissions'},
  { title: 'advance_setting', icon: 'tabler-settings', tab: 'edit',category:categoryTab.value},
]

let breadcrumbsData = ref<Record<string, string | undefined>>({
        page_name: 'profile_details',
        name: 'profile_details',
    });

    const isAlertVisible = ref(true)
    const userId = Number(route.params.userid) ?? 0;
</script>
<template>
 <section>
  
  <VCard>
   
   <!-- <VAlert
     density="default"
     color="success"
     closable
     class="close_btn_white"
   
   >
     {{ $t("two_steps_enabled") }}
   </VAlert> -->

   <div class="text-center">
   
 </div>
       <VCardText>
         <VTabs
           v-model="activeTab"
           
         >
       <VTab
         v-for="item in tabs"
         :key="item.icon"
         
         :to="!item.category? { name: 'edit-profile-tab-uid', params: {tab: item.tab,uid:userId} } :{ name: 'advance-setting-tab-category-userid', params: {tab: item.tab,category:item.category,userid:userId} }"
         @click="breadcrumbsData.name = item.title;breadcrumbsData.page_name = item.title"
       >
       <VIcon
         size="20"
         start
         :icon="item.icon"
       />
       {{$t(item.title) }}
     </VTab>
   </VTabs>
 </VCardText>
   <VWindow
     v-model="activeTab"
     class="disable-tab-transition"
     :touch="false"
   >
     <!-- Profile -->
     <VWindowItem >
       <!-- <UserEditProfile /> -->
     </VWindowItem>

     <!-- Teams -->
     <VWindowItem >
       <!-- <EditBilling /> -->
     </VWindowItem>

     <VWindowItem >
       <!-- <EditSecurity /> -->
     </VWindowItem>

     <VWindowItem >
       <!-- <EditPermissions /> -->
     </VWindowItem> 


     <VWindowItem >
       <EditAdvanceSetting />
     </VWindowItem>
  
   
     

   </VWindow>
   

 </VCard>
  </section>
  
</template>