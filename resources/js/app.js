import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Link } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import {useTranslateStore} from "@/storage/lang/translate.js";
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
      .use(pinia)
      .mount(el);
  },
})

