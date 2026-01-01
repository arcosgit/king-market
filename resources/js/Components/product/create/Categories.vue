<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCreateProductStore} from "@/storage/product/create.js";
import axios from "axios";
import { onMounted, reactive, watch } from "vue";
import { route } from "ziggy-js";

const categories = reactive({all: null, subcategories: [], nestedSubcategories: [], choiceCategoryIndex: '', choiceSubcategoryIndex: ''});


const getCategories = async () => {
    try {
        const res = await axios.post(route('categories'));
        categories.all = res.data;
    } catch(error){
        alert(useTranslateStore().t('categoriesLoadError'));
    }
}

watch(()=>categories.choiceCategoryIndex, () =>{
    useCreateProductStore().category.nestedSubcategoryId = null;
    useCreateProductStore().category.subcategoryId = null;
    categories.choiceSubcategoryIndex = '';
    categories.nestedSubcategories = [];
    const subcategories = categories.all[categories.choiceCategoryIndex].subcategories;
    categories.subcategories = subcategories;
    useCreateProductStore().category.categoryId = categories.all[categories.choiceCategoryIndex].category_id;

});

watch(()=>categories.choiceSubcategoryIndex, (newValue, oldValue) =>{
    if(newValue === '') return;
    useCreateProductStore().category.nestedSubcategoryId = null;
    const nestedSubcategories = categories.all[categories.choiceCategoryIndex].subcategories[categories.choiceSubcategoryIndex].nested_categories;
    categories.nestedSubcategories = nestedSubcategories;
    useCreateProductStore().category.subcategoryId = categories.all[categories.choiceCategoryIndex].subcategories[categories.choiceSubcategoryIndex].subcategory_id;
});

onMounted(()=>{
    getCategories();
});
</script>
<template>
    <div class="flex gap-x-2.5 flex-wrap">
        <select v-model="categories.choiceCategoryIndex" class="bg-[#263646] min-w-57.5 mt-2.5 rounded-[10px] focus:outline-none h-10 cursor-pointer">
            <option value="" disabled selected hidden>{{ useTranslateStore().t('choiceCategory') }}</option>
            <div v-for="(category, index) in categories.all" :key="index">
                <option :value="index">{{ category.category_name }}</option>
            </div>
        </select>
        <div v-if="categories.subcategories.length >= 1">
            <select v-model="categories.choiceSubcategoryIndex" class="bg-[#263646] min-w-57.5 mt-2.5 rounded-[10px] focus:outline-none h-10 cursor-pointer">
                <option value="" disabled selected hidden>{{ useTranslateStore().t('choiceSubcategory') }}</option>
                <div v-for="(category, index) in categories.subcategories" :key="index">
                    <option :value="index">{{ category.subcategory_name }}</option>
                </div>
            </select>
        </div>
        <div v-if="categories.nestedSubcategories.length >= 1">
            <select v-model="useCreateProductStore().category.nestedSubcategoryId" class="bg-[#263646] min-w-57.5 mt-2.5 rounded-[10px] focus:outline-none h-10 cursor-pointer">
                <option :value="null" disabled selected hidden>{{ useTranslateStore().t('choiceNestedSubcategory') }}</option>
                <div v-for="(category, index) in categories.nestedSubcategories" :key="index">
                    <option :value="category.nested_category_id">{{ category.nested_category_name }}</option>
                </div>
            </select>
        </div>
    </div>
</template>
