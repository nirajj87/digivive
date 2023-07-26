<script lang="ts" setup>


import EmailForm from '../view/emailForm.vue';


   
const  toolbar = [
        [{ font: [] }],
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline", "strike"],
        [{ color: [] }, { background: [] }],
        [{ script:  "sub" }, { script:  "super" }],
        ["blockquote", "code-block"],
        [{ list:  "ordered" }, { list:  "bullet" }],
        [{ indent:  "-1" }, { indent:  "+1" }, { align: [] }],
        ["link", "image", "video"],
        ["clean"],
    ];
   

    import { MdEditor } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    const text = ref('# Hello Editor');

    import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
    const breadcrumbsData = ref({
        page_name: 'email_template',
        name: 'email_template',
    });

</script>
<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
            <VCardText>
            <VRow>
              <EmailForm></EmailForm>
            </VRow>
            </VCardText>
        </VCard>
            
        
    </section>
</template>