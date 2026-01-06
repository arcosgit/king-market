import { defineStore } from "pinia";
export const useBasketStore = defineStore('basket', {
    state: () => ({
        isOpen: false,
        products: [],
    }),
    actions:{
        deleteFromCart(productId){
            this.products = this.products.filter(product => product.product.id !== productId);
        },
        resetData(){
            this.isOpen = false;
            this.products = [];
        }
    },
    persist: {
        pick: ['products'],
    }
});
