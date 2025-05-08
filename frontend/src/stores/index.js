import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

const pinia = createPinia()

// pinia-plugin-persistedstate
pinia.use(piniaPluginPersistedstate)

export { pinia }
