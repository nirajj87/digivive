<script lang="ts" setup>
// eslint-disable-next-line @typescript-eslint/consistent-type-imports
import type { VForm } from 'vuetify/components'
import { emailValidator, requiredValidator } from '@validators'

const title = ref('')
const is_parent = ref('')
const parent_name = ref('')

const refForm = ref<VForm>();
    const items = ['Yes', 'No']
	const inlineRadio = ref('radio-1')
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
        md="6"
      >
        <VTextField
          v-model="title"
          label="Title"
          :rules="[requiredValidator]"
        />
      </VCol>


	  <VCol
			cols="12"
			md="6"
		>
		<div class="box-border">
            <VRow no-gutters>
                
                
                <VCol cols="12"
					md="4">
                <label>Parent Module</label>
                </VCol>
                <VCol cols="12"
					md="8">
                <VRadioGroup
                v-model="inlineRadio"
                inline
                >
                    <VRadio
                        label="Yes"
                        value="radio-1"
                    />
                    <VRadio
                        label="No"
                        value="radio-2"
                    />
                </VRadioGroup>
                </VCol>
				
            </VRow>
		</div>
      	</VCol>

      <VCol cols="12" class="text-right">
		<VBtn
            type="submit"
            color="secondary mr-3"
        >
          {{$t("can_btn")}}
        </VBtn>
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
  .box-border{
    border: 1px solid;
    border-color: #B1B0B5;
    border-radius: 6px;
	padding: 0 16px;
  }
  .box-border label{
	display: flex;
    align-items: center;
    height: 100%;
  }
</style>