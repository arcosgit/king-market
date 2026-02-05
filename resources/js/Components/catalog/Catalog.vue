<script setup>
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import {useTranslateStore} from "@/storage/lang/translate.js";
import { router } from '@inertiajs/vue3';
import axios from "axios";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";

const choiceCategory = ref(null);

const getCategories = async () => {
    if(useCatalogStore().categories.length > 0) return;
    const res = await axios.post(route('categories'));
    useCatalogStore().categories = res.data;
}

const getProductsByCategory = async (categoryId = null, subcategoryId = null, nestedSubcategoryId = null) => {
    try{
        const res = await axios.post(route('product.catalog') + '?page=1', {category_id: categoryId, subcategory_id: subcategoryId, nested_subcategory_id: nestedSubcategoryId});
        if(res.data.length != 0){
            useCatalogStore().products = res.data;
            useCatalogStore().catagoryId = categoryId;
            useCatalogStore().subcategoryId = subcategoryId;
            useCatalogStore().nestedSubcategoryId = nestedSubcategoryId;
            useCatalogStore().show = false;
            if(window.location.href != route('catalog.show')) router.visit(route('catalog.show'));
        } else {
            alert(useTranslateStore().t('categoryProductsNotFound'));
        }
    } catch (e){
        alert(useTranslateStore().t('categoryDoesntExist'));
    }
}

const openCategory = (index) => {
    choiceCategory.value = useCatalogStore().categories[index];
}

onMounted(async ()=>{
    await getCategories();
    choiceCategory.value = useCatalogStore().categories[0];
});
</script>
<template>
    <div class="flex gap-x-10 h-[calc(100vh-180px)] mt-2.5">
        <div class="min-w-75 max-w-75 bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-r-[20px] p-2 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col">
                <div v-for="(category, index) in useCatalogStore().categories" :key="index">
                    <button @dblclick.prevent="getProductsByCategory(choiceCategory.category_id)" @click.prevent="openCategory(index)" :class="{'text-blue bg-blue-500/8': choiceCategory != null && category.category_id == choiceCategory.category_id}" class="text-xl text-left p-3 rounded-[10px] hover:bg-blue-500/8 mt-2.5 cursor-pointer w-full">{{ category.category_name }}</button>
                </div>
            </div>
        </div>
        <div class="grow bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-l-[20px] p-2 overflow-y-auto custom-scrollbar">
            <div v-if="choiceCategory != null">
                <div class="text-xl text-center">
                    <button class="cursor-pointer" @click.prevent="getProductsByCategory(choiceCategory.category_id)">{{ choiceCategory.category_name }}</button>
                </div>
                <div class="flex flex-wrap gap-x-7.5 mt-2.5">
                    <div v-for="(subcategory, indexSubcategory) in choiceCategory.subcategories" :key="indexSubcategory" class="flex flex-col gap-y-1">
                        <button @click.prevent="getProductsByCategory(null, subcategory.subcategory_id)" class="text-lg text-left cursor-pointer">{{ subcategory.subcategory_name }}</button>
                        <div v-if="subcategory.nested_categories.length > 0">
                            <div class="flex flex-col gap-y-1">
                                <div v-for="(nestedSubcategory, indexNestedSubcategory) in subcategory.nested_categories" :key="indexNestedSubcategory" class="text-gray text-base">
                                    <button class="cursor-pointer text-left" @click.prevent="getProductsByCategory(null, null, nestedSubcategory.nested_category_id)">{{ nestedSubcategory.nested_category_name }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
