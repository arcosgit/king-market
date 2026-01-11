import { defineStore } from "pinia";
export const useFindProductStore = defineStore('findProduct', {
    state: () => ({
        name: '',
        products: [],
    }),
});
