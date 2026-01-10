<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from "axios";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";

const categories = ref(null);
const choiceCategory = ref(null);

const getCategories = async () => {
    const res = await axios.post(route('categories'));
    categories.value = res.data;
}

const openCategory = (index) => {
    choiceCategory.value = categories.value[index];
}

onMounted(async ()=>{
    await getCategories();
    choiceCategory.value = categories.value[0];
})
</script>
<template>
    <div class="text-center text-xl">{{ useTranslateStore().t('catalog') }}</div>
    <div class="flex gap-x-10 h-[calc(100vh-208px)] mt-2.5">
        <div class="w-75 bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-r-[20px] p-2 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col">
                <div v-for="(category, index) in categories" :key="index">
                    <button @click.prevent="openCategory(index)" :class="{'text-blue bg-blue-500/8': choiceCategory != null && category.category_id == choiceCategory.category_id}" class="text-xl text-left p-3 rounded-[10px] hover:bg-blue-500/8 mt-2.5 cursor-pointer w-full">{{ category.category_name }}</button>
                </div>
            </div>
        </div>
        <div class="grow bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-l-[20px] p-2">
            <div v-if="choiceCategory != null">
                <div class="text-xl text-center">{{ choiceCategory.category_name }}</div>
                <div class="flex flex-wrap gap-x-7.5 mt-2.5">
                    <div v-for="(subcategory, indexSubcategory) in choiceCategory.subcategories" :key="indexSubcategory" class="flex flex-col gap-y-1">
                        <div class="text-lg">{{ subcategory.subcategory_name }}</div>
                        <div v-if="subcategory.nested_categories.length > 0">
                            <div class="flex flex-col">
                                <div v-for="(nestedSubcategory, indexNestedSubcategory) in subcategory.nested_categories" :key="indexNestedSubcategory" class="text-gray text-base">
                                    <div>{{ nestedSubcategory.nested_category_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
