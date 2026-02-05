<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { onBeforeMount, onMounted, onUnmounted, reactive, watch } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

const filters = reactive({priceFrom: '', priceTo: '', rating: null, enabled: false});
const errors = reactive({priceFrom: false, notFound: false});
const pagination = reactive({number: 1, allProductsLoaded: false, numberFilter: 1});

const getProducts = async () => {
    errors.notFound;
    errors.priceFrom;
    if(pagination.allProductsLoaded) return;
    try{
        const res = await axios.post(route('product.catalog') + `?page=${pagination.number}`, {category_id: useCatalogStore().catagoryId, subcategory_id: useCatalogStore().subcategoryId,nested_subcategory_id: useCatalogStore().nestedSubcategoryId });
        useCatalogStore().products.push(...res.data);
        if(res.data.length <= 0) pagination.allProductsLoaded = true;
    } catch (e){
        alert('server error');
    }

}

const resetFilters = () => {
    filters.priceFrom = '';
    filters.priceTo = '';
    filters.rating = null;
    filters.enabled = false;
    pagination.allProductsLoaded = false;
    pagination.number = 1;
    pagination.numberFilter = 1;
    useCatalogStore().products = [];
    getProducts();
}

const setFilters = async () => {
    errors.notFound = false;
    errors.priceFrom = false;
    if(filters.priceTo != '' && filters.priceFrom != '' && filters.priceFrom > filters.priceTo){
        errors.priceFrom = true;
        return;
    }
    if(pagination.allProductsLoaded) return;
    try{
        const res = await axios.post(route('product.catalog.filter') + `?page=${pagination.numberFilter}`, {
            category_id: useCatalogStore().catagoryId,
            subcategory_id: useCatalogStore().subcategoryId,
            nested_subcategory_id: useCatalogStore().nestedSubcategoryId,
            price_from: filters.priceFrom,
            price_to: filters.priceTo,
            rating: filters.rating
        });
        if(!Array.isArray(res.data.data) && res.data.not_found){
            errors.notFound = true;
            return;
        } else {
            if(!filters.enabled) useCatalogStore().products = [];
            if(!res.data.has_more_page) pagination.allProductsLoaded = true;
            useCatalogStore().products.push(...res.data.data);
        }
        filters.enabled = true;
    } catch(e){
        alert('server error');
    }
}

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight && !filters.enabled){
        pagination.number += 1;
        getProducts();
        return;
    }
    if(scrollTop + clientHeight >= scrollHeight && filters.enabled){
        pagination.numberFilter += 1;
        setFilters();
    }
}

watch(() => [filters.priceFrom, filters.priceTo, filters.rating], ()=>{
    pagination.allProductsLoaded = false;
    pagination.numberFilter = 1;
    filters.enabled = false;
});

onBeforeMount(() => {
    if(useCatalogStore().products.length <= 0) router.visit(route('index'));
});

onMounted(() => {window.addEventListener('scroll', handleScroll);});

onUnmounted(()=>{
    window.removeEventListener('scroll', handleScroll);
    useCatalogStore().products = [];
    useCatalogStore().resetCategory();
});
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('catalog') }}</title>
        <meta name="description" :content="useTranslateStore().t('catalogDescription')">
    </Head>
    <MainLayout>
        <div v-if="useCatalogStore().products.length > 0">
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
                <button @click.prevent="resetFilters" class="flex justify-center items-center border border-amber-500 bg-amber-500 p-2.5 cursor-pointer rounded-[10px] h-10 hover:bg-inherit hover:text-amber-500 transition duration-300">{{ useTranslateStore().t('reset') }}</button>
                <button @click.prevent="router.visit(route('index'))" class="btn-purple h-10">{{ useTranslateStore().t('home') }}</button>
            </div>
            <div class="relative">
                <div v-if="errors.priceFrom" class="text-red-500 absolute -bottom-5.5">{{ useTranslateStore().t('priceFromError') }}</div>
                <div v-if="errors.notFound" class="text-red-500 absolute -bottom-5.5">{{ useTranslateStore().t('noProductsFoundFilter') }}</div>
            </div>
            <div class="w-full h-0.5 bg-white mt-4.5 rounded-full"></div>
            <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
                <div v-for="(product, index) in useCatalogStore().products" :key="index" class="max-md:place-items-center">
                    <Card :product="product"></Card>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
