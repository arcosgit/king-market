import { defineStore } from "pinia";
export const useCatalogStore = defineStore('catalog', {
    state: () => ({
        categories: [],
        show: false,
        products: [],
        catagoryId: null,
        subcategoryId: null,
        nestedSubcategoryId: null,
    }),
    actions: {
        resetCategory(){
            this.catagoryId = null;
            this.subcategoryId = null;
            this.nestedSubcategoryId = null;
        }
    }
});
