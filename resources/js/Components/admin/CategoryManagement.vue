<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from "axios";
import { reactive } from "vue";
import { route } from "ziggy-js";

const load = reactive({category: false});
const category = reactive({id: null, findCreateName: '', name: '', subcategories: [], nestedCategories: [], createSuccess: false});
const categoryErrors = reactive({create: null, find: null});

const createCategory = async () => {
    load.category = true;
    categoryErrors.create = null;
    category.createSuccess = false;
    try{
        await axios.post(route('admin.create.category'), {name: category.findCreateName.trim()});
        setTimeout(() => {
            category.createSuccess = true;
            load.category = false;
            category.findCreateName = '';
        },1500);
    } catch(error){
        load.category = false;
        categoryErrors.create = error.response.data.errors.name[0];
    }
}

const findCategory = async () => {
    load.category = true;
    categoryErrors.find = null;
    try{
        const res = await axios.post(route('admin.find.category'), {name: category.findCreateName});
        setTimeout(() => {
            load.category = false;
            category.name = res.data.category_name;
            category.id = res.data.category_id;
            category.subcategories = res.data.subcategories;
        },1000);
        console.log(res);
    } catch(error){
        load.category = false;
        categoryErrors.find = error.response.data.errors.name[0];
    }
}

const showNestedCategories = (subcategory_id) => {
    const found = category.subcategories.find(item => item.subcategory_id === subcategory_id);
    category.nestedCategories = found.nested_categories;
    console.log(category.nestedCategories);
    // let nestedCategories = [];
    // for(let i = 0; i < category.subcategories.length; i++){
    //     if(category.subcategories[i].subcategory_id == subcategory_id){
    //         nestedCategories = category.subcategories[i].nested_categories;
    //     }
    // }

}
</script>
<template>
    <div class="pb-3.75">
        <div class="flex items-center gap-x-2.5">
            <input v-model="category.findCreateName" :readonly="load.category" type="text" class="p-2.25 w-75 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterCategory')">
            <button @click.prevent="findCategory" v-if="!load.category" class="btn-blue w-20 h-10">{{ useTranslateStore().t('find') }}</button>
            <button @click.prevent="createCategory" v-if="!load.category" class="btn-purple w-20 h-10">{{ useTranslateStore().t('create') }}</button>
            <div v-if="load.category" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
        </div>
        <div v-if="categoryErrors.create" class="mt-2.5 text-red-500" >{{ categoryErrors.create }}</div>
        <div v-if="categoryErrors.find" class="mt-2.5 text-red-500" >{{ categoryErrors.find }}</div>
        <div v-if="category.createSuccess" class="mt-2.5 text-lime-500" >{{ useTranslateStore().t('success') }}</div>
        <div v-if="category.id != null">
            <div class="flex items-center gap-x-2.5 mt-2.5">
                <div class="">{{ useTranslateStore().t('category') }} <span class="text-blue">{{ category.name }}</span></div>
                <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
                <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/delete.svg" alt="delete">
            </div>
            <div class="items-center flex flex-wrap gap-x-2.5">
                <div class="mt-2.5">{{ useTranslateStore().t('subcategories') }}</div>
                <div v-for="subcategory in category.subcategories" :key="subcategory.subcategory_id">
                    <button @click.prevent="showNestedCategories(subcategory.subcategory_id)" class="btn-dark-gray mt-2.5">{{ subcategory.subcategory_name }}</button>
                </div>
            </div>
            <div v-if="category.nestedCategories.length > 0" class="items-center flex flex-wrap gap-x-2.5">
                <div class="mt-2.5">{{ useTranslateStore().t('nestedSubcategories') }}</div>
                <div v-for="nestedCategory in category.nestedCategories" :key="nestedCategory.nested_category_id">
                    <button class="p-2.5 mt-2.5 border border-[#263646] cursor-pointer bg-[#263646] rounded-[10px] hover:border-red-500 hover:text-red-500 hover:bg-inherit hover:line-through transition duration-300">{{ nestedCategory.nested_category_name }}</button>
                </div>
            </div>
            <div class="flex mt-2.5 gap-x-2.5">
                <input type="text" class="p-2.25 w-75 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterCategory')">
                <button class="btn-blue w-20 h-10">{{ useTranslateStore().t('add') }}</button>
                <button class="btn-green w-20 h-10">{{ useTranslateStore().t('save') }}</button>
            </div>
        </div>
    </div>
</template>
