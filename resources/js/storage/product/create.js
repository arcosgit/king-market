import { defineStore } from "pinia";
export const useCreateProductStore = defineStore('createProduct', {
    state: () => ({
        name: '',
        description: '',
        price: '',
        characteristics: [{characteristic_key: '', characteristic_value: ''}],
        images: [],
        category: {categoryId: null, subcategoryId: null, nestedSubcategoryId: null}
    }),
    persist: {
       pick: ['name', 'description', 'price', 'characteristics', 'images']
    }
});
