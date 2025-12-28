import { defineStore } from "pinia";
export const useBusinessStore = defineStore('business', {
    state: () => ({
        id: null,
        name: null,
        sales: 0,
        products: 0,
        rating: 0,
        reviews: 0,
        profit: 0,
    }),
});
