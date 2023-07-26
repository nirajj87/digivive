/* eslint-disable import/order */
import '@/@fake-db/db'
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import ability from '@/plugins/casl/ability'
import i18n from '@/plugins/i18n'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import { abilitiesPlugin } from '@casl/vue'
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import { createVPhoneInput } from 'v-phone-input';

import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';



const vPhoneInput = createVPhoneInput({
  countryIconMode: 'svg',
  // displayFormat: 'international',
  defaultCountry: 'IN',
  guessCountry: true
});

loadFonts()

// Create vue app
const app = createApp(App)

// Use plugins
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(layoutsPlugin)
app.use(i18n)
app.use(abilitiesPlugin, ability, {
  useGlobalProperties: true,
})
app.use(vPhoneInput);

app.component('QuillEditor', QuillEditor)




// Mount vue app
app.mount('#app')