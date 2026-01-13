import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Link } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useFindProductStore} from "@/storage/product/find.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });
    const pinia = createPinia();
    pinia.use(piniaPluginPersistedstate);
    app.component('Link', Link);
    app
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia);
    const translateStore = useTranslateStore();
    let initialLang = 'ru';
    try {
        const stored = localStorage.getItem('translate');
        if(stored) {
            const parsed = JSON.parse(stored);
            if(parsed.currentLang) {
                initialLang = parsed.currentLang;
            }
        }
    } catch(e) {
        initialLang = translateStore.currentLang;
    }
    const lang = translateStore.currentLang || initialLang;
    window.axios.defaults.headers.common['X-Lang'] = lang;
    window.axios.interceptors.request.use(function (config) {
        const store = useTranslateStore();
        if(store && store.currentLang) {
            config.headers['X-Lang'] = store.currentLang;
        }
        return config;
    }, function (error) {
        return Promise.reject(error);
    });
    translateStore.$subscribe((mutation, state) => {
        if(state && state.currentLang) {
            window.axios.defaults.headers.common['X-Lang'] = state.currentLang;
        }
    });
    app.mount(el);
    const inertia = app.config.globalProperties.$inertia;
    inertia.on('navigate', (event) => {
        useFindProductStore().fullResetData();
        useCatalogStore().show = false;
    });
  },

});


