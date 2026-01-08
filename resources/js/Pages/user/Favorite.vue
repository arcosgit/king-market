<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import NoAuth from '@/Components/user/helpers/NoAuth.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/product/Card.vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import { onMounted, onUnmounted, reactive, watch } from 'vue';

const favorite = reactive({data: [], nextCursor: null, allProductsLoaded: false, isLoading: false});
const getFavorite = async () => {
    if(useUserStore().id == null || favorite.allProductsLoaded || favorite.isLoading) return;
    favorite.isLoading = true;
    try{
        const params = favorite.nextCursor ? { cursor: favorite.nextCursor } : {};
        const res = await axios.post(route('user.get.favorite'), params);
        favorite.data.push(...res.data.data);
        favorite.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            favorite.allProductsLoaded = true;
        }
    } catch(e){
        alert('server error');
    } finally {
        favorite.isLoading = false;
    }
}

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight){
        getFavorite();
    }
}

watch(()=>useUserStore().id, (newValue, oldValue)=>{
    if(newValue != null){
        getFavorite();
    }
});
watch(()=>useTranslateStore().currentLang, ()=>{
    favorite.data = [];
    favorite.nextCursor = null;
    favorite.allProductsLoaded = false;
    getFavorite();
});

onMounted(() => {
    getFavorite();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('favoriteProducts') }}</title>
        <meta name="description" :content="useTranslateStore().t('favoriteProductsDescription')">
    </Head>
    <MainLayout>
        <div v-if="useUserStore().id != null">
            <div class="uppercase text-xl text-center">{{ useTranslateStore().t('favoriteProducts') }}</div>
            <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
                <div v-for="(product, index) in favorite.data" :key="index" class="max-md:place-items-center">
                    <Card :product="product"></Card>
                </div>
            </div>
        </div>
        <div v-else>
            <NoAuth></NoAuth>
        </div>
    </MainLayout>
</template>
