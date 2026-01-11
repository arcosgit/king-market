<script setup>
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useFindProductStore} from "@/storage/product/find.js";
import axios from 'axios';
import { reactive, ref } from 'vue';
import { route } from 'ziggy-js';

const filters = reactive({priceFrom: null, priceTo: null, rating: null});
const errors = reactive({priceFrom: false, notFound: false});
const filtersLoad = ref(false);

const setFilters = async () => {
    errors.priceFrom = false;
    errors.notFound = false;
    if(filters.priceFrom == null && filters.priceTo == null && filters.rating == null) return;
    if(filters.priceTo != null && filters.priceFrom > filters.priceTo){
        errors.priceFrom = true;
        return;
    }
    try{
        const res = await axios.post(route('product.find.filter'), {name: useFindProductStore().name, price_from: filters.priceFrom, price_to: filters.priceTo, rating: filters.rating});
        console.log(res);
        if(!Array.isArray(res.data) && res.data.not_found){
            errors.notFound = true;
            return;
        } else {
            useFindProductStore().products = res.data;
        }
    } catch(e){
        alert('error server');
    } finally {
        filtersLoad.value = false;
    }
}
</script>
<template>
    <div class="text-xl text-center">{{ useTranslateStore().t('filters') }}</div>
    <div class="flex gap-x-2.5 mt-1">
        <div class="flex items-center gap-x-2.5">
            <div class="text-lg">{{ useTranslateStore().t('price') }}</div>
            <input v-model="filters.priceFrom" type="number" class="p-2.25 w-50 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('priceFrom')">
            <input v-model="filters.priceTo" type="number" class="p-2.25 w-50 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('priceTo')">
        </div>
        <div class="flex items-center gap-x-2.5">
            <div class="text-lg">{{ useTranslateStore().t('rating') }}</div>
            <select v-model="filters.rating" class="bg-[#263646] rounded-[10px] h-10 focus:outline-none cursor-pointer">
                <option :value="null">{{ useTranslateStore().t('ratingDoNotMatter') }}</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>
        </div>
        <button @click.prevent="setFilters" class="btn-blue h-10">{{ useTranslateStore().t('apply') }}</button>
        <button @click.prevent="useFindProductStore().products = []" class="btn-purple h-10">{{ useTranslateStore().t('close') }}</button>
    </div>
    <div class="relative">
        <div v-if="errors.priceFrom" class="text-red-500 absolute -bottom-5.5">{{ useTranslateStore().t('priceFromError') }}</div>
        <div v-if="errors.notFound" class="text-red-500 absolute -bottom-5.5">{{ useTranslateStore().t('noProductsFoundFilter') }}</div>
    </div>
    <div class="w-full h-0.5 bg-white mt-4.5 rounded-full"></div>
    <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
        <div v-for="(product, index) in useFindProductStore().products" :key="index" class="max-md:place-items-center">
            <Card :product="product"></Card>
        </div>
    </div>
</template>
