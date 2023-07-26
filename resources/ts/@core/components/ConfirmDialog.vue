<script setup lang="ts">
import { requiredValidator , stringMatchValidator} from '@validators'

interface Props {
  isDialogVisible: boolean
  expectedValue: string 
}

interface Emit {
  (e: 'update:isDialogVisible', value: boolean): void
  (e: 'confirm', value: boolean): void
}

const props = defineProps<Props>()

const emit = defineEmits<Emit>()

const enteredValue = ref('');
const correctSpelling = ref(false);

const updateModelValue = (val: boolean) => {
  emit('update:isDialogVisible', val)
}

const onConfirmation = () => {
  if (enteredValue.value == props.expectedValue.trim().replaceAll(' ','_')) {
    correctSpelling.value = true ;
    emit('confirm', true)
    updateModelValue(false);
    enteredValue.value = '' ;
  }else{
    enteredValue.value = '' ;
    correctSpelling.value = true ;
  }


}

const onCancel = () => {
  // emit('confirm', false)
  emit('update:isDialogVisible', false)

  //  if he write something and then press cancel button
  enteredValue.value = '' ;
}
</script>

<template>
  	<!-- 👉 Confirm Dialog -->
  	<VDialog
		max-width="600"
		:model-value="props.isDialogVisible"
		@update:model-value="updateModelValue"
		persistent
	>
    <VCard>
		<div class="light_bg">
			<VCardText>
				<h5>
					<VIcon 
					icon="tabler-alert-triangle" />
					Delete {{expectedValue}}?
				</h5>
				<VBtn variant="text" color="secondary" class="popup_close" @click="onCancel">
				<VIcon 
				 	icon="tabler-x"
					color="black"
				/>
				</VBtn>
			</VCardText>
		</div>
      	<VCardText>
			
        	<p>Delete <b>{{expectedValue}}</b> permanently? This will also delete all its {{expectedValue}} data, security credentials and inline policies.</p>
			<p>This action cannot be undone.</p>
			<VDivider />
			<p class="mt-4">To confirm deletion, enter the <b> {{ expectedValue.trim().replaceAll(' ','_')}}</b> in the text input field.</p>
        	<VTextField :label="$t('delete_confirmation') + '*'" v-model="enteredValue"   :rules="[stringMatchValidator(expectedValue.trim().replaceAll(' ','_') , enteredValue)  ]"/>
      	</VCardText>
		
      	<VCardText class="light_bg">
			<div class="pt-5 text-right">
				<VBtn
					color="secondary mr-3"    
					@click="onCancel"
				>
					{{$t("can_btn")}}
				</VBtn>
				<VBtn
					color="primary"
					@click="onConfirmation"
				>
					{{$t("confirm")}}
				</VBtn>
			</div>
      	</VCardText>
    </VCard>
  </VDialog>
</template>
