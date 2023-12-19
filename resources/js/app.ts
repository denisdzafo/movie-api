import { createApp } from 'vue';
import Index from './Index.vue';
import router from './services/router';
import { createPinia } from 'pinia'
import apiAxiosPlugin from "./services/plugins/apiAxiosPlugin";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

const app = createApp({
    components: { Index },
});
const pinia = createPinia()
app.use(router);
app.use(pinia);
pinia.use(piniaPluginPersistedstate)
app.use(apiAxiosPlugin);

app.mount('#app');
