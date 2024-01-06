/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import store from '@/store'
import '@core/scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'
import '@/assets/css/core.css'
import { createPinia } from 'pinia'
import { createApp } from 'vue'

loadFonts()

const isAuthenticated = localStorage.getItem('isAuthenticated')
if (isAuthenticated !== null) {
  store.commit('setAuthentication', JSON.parse(isAuthenticated))
}


// Create vue app
const app = createApp(App)


// Use plugins
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(store)

// Mount vue app
app.mount('#app')

