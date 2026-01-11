import { defineStore } from "pinia";
export const useCatalogStore = defineStore('catalog', {
    state: () => ({
        categories: [],
        show: false,
    }),
});
