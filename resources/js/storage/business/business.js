import { defineStore } from "pinia";
export const useBusinessStore = defineStore('business', {
    state: () => ({
        id: null,
        name: null,
        sales: 0,
        productsQuantity: 0,
        rating: 0,
        reviews: 0,
        profit: 0,
        products: [],
    }),
    actions: {
        resetData(){
            this.id = null;
            this.name = null;
            this.sales = 0;
            this.productsQuantity = 0;
            this.rating = 0;
            this.reviews = 0;
            this.profit = 0;
            this.products = [];
        }
    }
});
