import { createApp } from 'vue'
import App from './App.vue'
import router from './routes.js'
import { pinia } from './stores/index.js'
import './assets/styles/main.scss'
import 'boxicons/css/boxicons.min.css'

const app = createApp(App)

app.use(router)
app.use(pinia)

app.mount('#app')
