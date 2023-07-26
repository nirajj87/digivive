<script lang="ts" setup>
// eslint-disable-next-line @typescript-eslint/consistent-type-imports
import type { VForm } from 'vuetify/components'
import { emailValidator, requiredValidator } from '@validators'

const title = ref('')
const is_parent = ref('')
const parent_name = ref('')

const refForm = ref<VForm>();
    const items = ['Yes', 'No']
</script>

<template >
  
  
  <h5 class="mb-4">{{$t("add_btn")}} {{$t("role_and_permission")}}</h5>
  <VForm
    ref="refForm"
    @submit.prevent="() => {}"
    class="form-border"
  >
    
    <VRow>
      
      <VCol
        cols="12"
        md="4"
      >
        <VTextField
        class="mt-4"
          v-model="title"
          label="Title"
          :rules="[requiredValidator]"
        />
      </VCol>

      <VCol
        cols="12"
        md="4"
      >
      <VSelect
      class="mt-4"
      v-model="is_parent"
    :items="items"
    label="Select"
    
    :rules="[requiredValidator]"
  />
      
      </VCol>

      <VCol
        cols="12"
        md="4"
      >
      <VTextField
          v-model="parent_name"
          label="Parent"
          v-if="is_parent == 'Yes'"
          :rules="[requiredValidator]"
        />
      </VCol>

      <VCol cols="12" class="text-right">
        <VBtn
          type="submit"
          @click="refForm?.validate()"
        >
          {{$t("add_btn")}}
        </VBtn>
      </VCol>
    </VRow>
  </VForm>

  
</template>
<style lang="scss">
  .form-border{
    border: 1px solid;
    border-color: rgba(var(--v-border-color),var(--v-border-opacity));
    padding: 15px;
    border-radius: 6px;
  }
</style>