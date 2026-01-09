<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useEditProductStore} from "@/storage/product/edit.js";
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
    useEditProductStore().category.nestedSubcategoryId = null;
    useEditProductStore().category.subcategoryId = null;
    categories.choiceSubcategoryIndex = '';
    categories.nestedSubcategories = [];
    const subcategories = categories.all[categories.choiceCategoryIndex].subcategories;
    categories.subcategories = subcategories;
    useEditProductStore().category.categoryId = categories.all[categories.choiceCategoryIndex].category_id;
});

watch(()=>categories.choiceSubcategoryIndex, (newValue, oldValue) =>{
    if(newValue === '') return;
    useEditProductStore().category.nestedSubcategoryId = null;
    const nestedSubcategories = categories.all[categories.choiceCategoryIndex].subcategories[categories.choiceSubcategoryIndex].nested_categories;
    categories.nestedSubcategories = nestedSubcategories;
    useEditProductStore().category.subcategoryId = categories.all[categories.choiceCategoryIndex].subcategories[categories.choiceSubcategoryIndex].subcategory_id;
});

const setCategories = () => {
    const indexCategory = categories.all.findIndex(c => c.category_id === useEditProductStore().categoryConst.categoryId);
    if(indexCategory == -1) return;
    categories.choiceCategoryIndex = indexCategory;
    const indexSubcategory = categories.all[indexCategory].subcategories.findIndex(s => s.subcategory_id === useEditProductStore().categoryConst.subcategoryId);
    if(indexCategory == -1) return;
    setTimeout(()=>{
        categories.choiceSubcategoryIndex = indexSubcategory;
    },100);
    setTimeout(()=>{
        if(useEditProductStore().categoryConst.nestedSubcategoryId != null) useEditProductStore().category.nestedSubcategoryId = useEditProductStore().categoryConst.nestedSubcategoryId;
    },100);
}

onMounted(async ()=>{
    await getCategories();
    setCategories();
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
            <select v-model="useEditProductStore().category.nestedSubcategoryId" class="bg-[#263646] min-w-57.5 mt-2.5 rounded-[10px] focus:outline-none h-10 cursor-pointer">
                <option :value="null" disabled selected hidden>{{ useTranslateStore().t('choiceNestedSubcategory') }}</option>
                <div v-for="(category, index) in categories.nestedSubcategories" :key="index">
                    <option :value="category.nested_category_id">{{ category.nested_category_name }}</option>
                </div>
            </select>
        </div>
    </div>
</template>
