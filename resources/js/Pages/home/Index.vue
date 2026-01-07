<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
import { onMounted, onUnmounted, reactive, watch } from 'vue';

const products = reactive({data: [], nextCursor: null, allProductsLoaded: false, isLoading: false});

const getProducts = async () => {
    if(products.allProductsLoaded || products.isLoading) return;
    products.isLoading = true;
    try{
        const params = products.nextCursor ? { cursor: products.nextCursor } : {};
        const res = await axios.post(route('product.home'), params);
        products.data.push(...res.data.data);
        products.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            products.allProductsLoaded = true;
        }
    } catch(error){
        alert('error server');
    } finally {
        products.isLoading = false;
    }
}

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight){
        getProducts();
    }
}

watch(()=>useTranslateStore().currentLang, ()=>{
    products.data = [];
    products.nextCursor = null;
    products.allProductsLoaded = false;
    getProducts();
});

onMounted(() => {
    getProducts();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleHome') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionHome')">
    </Head>
    <MainLayout>
        <div class="flex justify-between flex-wrap">
            <div v-for="(product, index) in products.data" :key="index">
                <Card :product="product"></Card>
            </div>
        </div>
    </MainLayout>
</template>
