import { defineStore } from "pinia";
export const useEditProductStore = defineStore('editProduct', {
    state: () => ({
        id: null,
        name: '',
        description: '',
        price: '',
        characteristics: [],
        images: [],
        category: {categoryId: null, subcategoryId: null, nestedSubcategoryId: null},
        categoryConst: {categoryId: null, subcategoryId: null, nestedSubcategoryId: null},
    }),
    actions: {
        resetData(){
            this.id = null;
            this.name = '';
            this.description = '';
            this.price = '';
            this.characteristics = [];
            this.images = [];
            this.category = {categoryId: null, subcategoryId: null, nestedSubcategoryId: null};
        },

        setConstCategories(category) {
            this.categoryConst.categoryId = category.category_id;
            this.categoryConst.subcategoryId = category.subcategory_id;
            this.categoryConst.nestedSubcategoryId = category.nested_subcategory_id;
        }
    },
});
