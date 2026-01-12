import { defineStore } from "pinia";
export const useFindProductStore = defineStore('findProduct', {
    state: () => ({
        show: false,
        name: '',
        products: [],
        filtersEnabled: false,
    }),
    actions:{
        resetData(){
            this.products = [];
            this.filtersEnabled = false;
        },
        fullResetData(){
            this.show = false;
            this.name = '';
            this.resetData();
        }
    }
});
