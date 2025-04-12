import { createApp } from 'vue'
import App from './App.vue'
import router from './routes.js'
import './assets/styles/main.scss'
import 'boxicons/css/boxicons.min.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
