<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import Load from '@/Widgets/icons/Load.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, reactive } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';

const props = defineProps({name: String, id: Number});

const products = reactive({data: [], nextCursor: null, allProductsLoaded: false, isLoading: false});
const find = reactive({name: '', nameConst: '', data: [], nextCursor: null, allProductsLoaded: false, isLoading: false});
const errors = reactive({notFound: null});
const load = reactive({main: true});

const getProducts = async () => {
    if(products.allProductsLoaded || products.isLoading) return;
    products.isLoading = true;
    try{
        const params = products.nextCursor ? { cursor: products.nextCursor } : {};
        const res = await axios.post(route('business.products.from.name', props.id), params);
        products.data.push(...res.data.data);
        products.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            products.allProductsLoaded = true;
        }
    }catch(e){
        alert('server error');
    } finally {
        products.isLoading = false;
    }
}

const findProducts = async () => {
    errors.notFound = null;
    if(find.name.trim() == ''){
        resetFindProductsData();
        return;
    }
    if(find.nameConst.trim() != '' && find.name != find.nameConst) resetFindProductsData();
    if(find.isLoading || find.allProductsLoaded) return;
    find.isLoading = true;
    try{
        const res = await axios.post(route('product.find'), {name: find.name, business_id: props.id, cursor: find.nextCursor});
        if(!Array.isArray(res.data.data) && res.data.not_found){
            errors.notFound = true;
            return;
        }
        if(!Array.isArray(res.data.data) && !res.data.not_found && res.data.id){
            find.allProductsLoaded = true;
            find.nameConst = find.name;
            find.data = [res.data];
            return;
        }
        if(Array.isArray(res.data.data) && !res.data.not_found){
            find.nameConst = find.name;
            find.data.push(...res.data.data);
        }
        find.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            find.allProductsLoaded = true;
        }
    } catch (e){
        alert('error server');
    } finally {
        find.isLoading = false;
    }
}

const resetFindProductsData = () => {
    find.data = [];
    find.allProductsLoaded = false;
    find.nextCursor = null;
    find.nameConst = '';
}

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight && find.data.length == 0){
        getProducts();
    }
    if(scrollTop + clientHeight >= scrollHeight && find.data.length > 0){
        findProducts();
    }
}

onMounted(async()=>{
    await getProducts();
    window.addEventListener('scroll', handleScroll);
    setTimeout(()=>{load.main = false;}, 500);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('productsFrom') + ` ${props.name}` }}</title>
        <meta name="description" :content="useTranslateStore().t('businessProductsDescription')">
    </Head>
    <MainLayout>
        <div v-if="!load.main">
            <div class="text-xl text-center">{{ useTranslateStore().t('productsFrom') + ` ${props.name}` }}</div>
            <div class="flex gap-x-2.5 mt-1">
                <div class="relative w-full">
                    <input v-model="find.name" @keyup.enter.prevent="findProducts" type="text" class="p-2.25 h-10 w-full border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNameProductOrArticle')">
                    <div v-if="errors.notFound" class="text-red-500 absolute bottom-[-20.5px]">{{ useTranslateStore().t('productNotFoundSearch') }}</div>
                </div>
                <button @click.prevent="findProducts" class="btn-green h-10 w-20">{{ useTranslateStore().t('find') }}</button>
            </div>
            <div class="w-full h-0.5 bg-white mt-4.5 rounded-full"></div>
            <div class="grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
                <div v-if="products.data.length > 0 && find.data.length == 0 && !find.isLoading" v-for="(product, index) in products.data" :key="index" class="max-md:place-items-center mt-2.5">
                    <Card :product="product"></Card>
                </div>
                <div v-if="find.data.length > 0" v-for="(product, index) in find.data" :key="index" class="max-md:place-items-center mt-2.5">
                    <Card :product="product"></Card>
                </div>
            </div>
        </div>
        <div v-else class="flex justify-center items-center h-[calc(100%-17px)]">
            <Load text="load"></Load>
        </div>
    </MainLayout>
</template>
