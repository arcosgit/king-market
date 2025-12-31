<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from "axios";
import { reactive } from "vue";
import { route } from "ziggy-js";

const initialСategoryErrors = {create: null, find: null, addSubcategory: null, addNestedSubcategory: null};
const load = reactive({category: false, add: false, deleteSubcategory: false, deleteCategory: false});
const category = reactive({ id: null, findCreateName: '', name: '', subcategories: [], nestedSubcategories: [], activeSubcategoryId: null, createSuccess: false });
const add = reactive({category: ''});
const categoryErrors = reactive({ ...initialСategoryErrors });

const resetData = () => { Object.assign(categoryErrors, initialСategoryErrors); add.category = ''; category.subcategories = []; category.nestedSubcategories = []; category.activeSubcategoryId = null; category.id = null; category.name = ''; category.createSuccess = false;};

const createCategory = async () => {
    load.category = true;
    resetData();
    try{
        await axios.post(route('admin.create.category'), {name: category.findCreateName.trim()});
        setTimeout(() => {
            load.category = false;
            category.createSuccess = true;
            category.findCreateName = '';
        },1500);
    } catch(error){
        load.category = false;
        categoryErrors.create = error.response.data.errors.name[0];
    }
}

const findCategory = async () => {
    load.category = true;
    resetData();
    try{
        const res = await axios.post(route('admin.find.category'), {name: category.findCreateName});
        setTimeout(() => {
            load.category = false;
            category.name = res.data.category_name;
            category.id = res.data.category_id;
            category.subcategories = res.data.subcategories;
        },1000);
    } catch(error){
        load.category = false;
        categoryErrors.find = error.response.data.errors.name[0];
    }
}

const showNestedSubcategories = (subcategory_id) => {
    add.category = '';
    categoryErrors.addSubcategory = null;
    categoryErrors.addNestedSubcategory = null;
    if(category.activeSubcategoryId == subcategory_id){
        category.activeSubcategoryId = null;
        category.nestedSubcategories = [];
        return;
    }
    category.activeSubcategoryId = subcategory_id;
    const found = category.subcategories.find(item => item.subcategory_id === subcategory_id);
    category.nestedSubcategories = found.nested_categories;
}

const addCategoryOrNestedCategory = async () => {
    load.add = true;
    categoryErrors.addSubcategory = null;
    categoryErrors.addNestedSubcategory = null;
    try{
        if(category.activeSubcategoryId != null){
            const res = await axios.patch(route('admin.add.nestedsubcategory'), {subcategory_id: category.activeSubcategoryId, name: add.category});
            setTimeout(()=>{
                load.add = false;
                category.nestedSubcategories.push(res.data);
                add.category = '';
            },1000);
        } else {
            const res = await axios.patch(route('admin.add.subcategory'), {category_id: category.id, name: add.category});
            setTimeout(()=>{
                load.add = false;
                category.subcategories.push(res.data);
                add.category = '';
            },1000);
        }
    } catch (error){
        load.add = false;
        categoryErrors.addSubcategory = error.response.data.subcategory_exist;
        categoryErrors.addNestedSubcategory = error.response.data.nested_subcategory_exist;
    }
}

const deleteNestedSubcategory = async (nestedSubCategoryId, index) => {
    const deleteConfirmation = confirm(useTranslateStore().t('deleteNestedSubcategoryConfirm'));
    if(deleteConfirmation){
        try {
            category.nestedSubcategories.splice(index, 1);
            await axios.delete(route('admin.delete.nestedsubcategory'), {data: {nested_subcategory_id: nestedSubCategoryId}});
        } catch(error){
            alert(error.response.data.errors.nested_subcategory_id[0]);
        }
    }
}

const deleteSubcategory = async () => {
    const deleteConfirmation = confirm(useTranslateStore().t('deleteSubcategoryConfirm'));
    if(deleteConfirmation){
        load.deleteSubcategory = true;
        try{
            await axios.delete(route('admin.delete.subcategory'), {data: {subcategory_id: category.activeSubcategoryId}});
            setTimeout(() => {
                load.deleteSubcategory = false;
                const index = category.subcategories.findIndex(subcategory => subcategory.subcategory_id == category.activeSubcategoryId);
                category.subcategories.splice(index, 1);
                category.activeSubcategoryId = null;
            },1000);
        } catch(error){
            load.deleteSubcategory = false;
            alert(error.response.data.errors.subcategory_id[0]);
        }
    }
}

const deleteCategory = async () => {
    const deleteConfirmation = confirm(useTranslateStore().t('deleteCategoryConfirm'));
    if(deleteConfirmation) {
        load.deleteCategory = true;
        try{
            await axios.delete(route('admin.delete.category'), {data: {category_id: category.id}});
            setTimeout(() => {
                load.deleteCategory = false;
                resetData();
            },1000);
        } catch (error){
            load.deleteCategory = false;
            alert(error.response.data.errors.category_id[0]);
        }
    }
}
</script>
<template>
    <div class="pb-3.75">
        <div class="flex items-center gap-x-2.5">
            <input @keyup.enter.prevent="findCategory" v-model="category.findCreateName" :readonly="load.category" type="text" class="p-2.25 w-100 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterCategory')">
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
                <img v-if="!load.deleteCategory" @click.prevent="deleteCategory" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/delete.svg" alt="delete">
                <div v-if="load.deleteCategory" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
            </div>
            <div class="items-center flex flex-wrap gap-x-2.5">
                <div class="mt-2.5">{{ useTranslateStore().t('subcategories') }}</div>
                <div v-for="subcategory in category.subcategories" :key="subcategory.subcategory_id">
                    <button @click.prevent="showNestedSubcategories(subcategory.subcategory_id)" :style="category.activeSubcategoryId === subcategory.subcategory_id ? 'background-color: #395169;' : ''" class="btn-dark-gray mt-2.5">{{ subcategory.subcategory_name }}</button>
                </div>
                <div class="text-orange-300 mt-2.5" v-if="category.subcategories.length == 0">{{ useTranslateStore().t('notFound') }}</div>
            </div>
            <div v-if="category.activeSubcategoryId != null" class="items-center flex flex-wrap gap-x-2.5">
                <div class="mt-2.5">{{ useTranslateStore().t('nestedSubcategories') }}</div>
                <div v-for="(nestedCategory, index) in category.nestedSubcategories" :key="nestedCategory.nested_category_id">
                    <button @click.prevent="deleteNestedSubcategory(nestedCategory.nested_category_id, index)" class="p-2.5 mt-2.5 border border-[#263646] cursor-pointer bg-[#263646] rounded-[10px] hover:border-red-500 hover:text-red-500 hover:bg-inherit hover:line-through transition duration-300">{{ nestedCategory.nested_category_name }}</button>
                </div>
                <div class="text-orange-300 mt-2.5" v-if="category.nestedSubcategories.length == 0">{{ useTranslateStore().t('notFound') }}</div>
            </div>
            <div class="flex items-center mt-2.5 gap-x-2.5">
                <input @keyup.enter.prevent="addCategoryOrNestedCategory" v-model="add.category" type="text" :readonly="load.add" class="p-2.25 w-100 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="category.activeSubcategoryId != null ? useTranslateStore().t('enterNestedSubcategory') : useTranslateStore().t('enterSubcategory')">
                <button v-if="!load.add" @click.prevent="addCategoryOrNestedCategory" class="btn-blue w-20 h-10">{{ useTranslateStore().t('add') }}</button>
                <div v-if="load.add" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
            </div>
            <div v-if="categoryErrors.addSubcategory" class="text-red-500 mt-2.5">{{ categoryErrors.addSubcategory }}</div>
            <div v-if="categoryErrors.addNestedSubcategory" class="text-red-500 mt-2.5">{{ categoryErrors.addNestedSubcategory }}</div>
            <button v-if="category.activeSubcategoryId != null && !load.deleteSubcategory" @click.prevent="deleteSubcategory" class="mt-2.5 border text-[14px] h-10 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer">{{ useTranslateStore().t('deleteSubcategory') }}</button>
            <div v-if="load.deleteSubcategory" class="mt-2.5 w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
        </div>
    </div>
</template>
