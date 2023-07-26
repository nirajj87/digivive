<script setup lang="ts">

import { themeConfig } from '@themeConfig';



// set module props
interface Props {
  moduleData:any;
}
const props = defineProps<Props>();
// console.log(props);

</script>


<template >
    <VCardText>
          <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6"
          />
          <h5 class="text-h5 font-weight-semibold mb-1">
            Welcome to {{ themeConfig.app.title }}
          </h5>
          <p class="mb-0">
            {{props.moduleData }}
          </p>
        </VCardText>
  </template>