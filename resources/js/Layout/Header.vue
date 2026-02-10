<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useFindProductStore} from "@/storage/product/find.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import { onMounted, onUnmounted, reactive, ref, watch } from "vue";
import { router } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import axios from "axios";
import Nav from './Nav.vue';
import Language from '../Components/user/helpers/Language.vue';


const find = reactive({name: '', nextCursor: null, allProductsLoaded: false, isLoading: false});
const productNotFound = ref(null);

const findProduct = async () => {
    productNotFound.value = null;
    if(find.name.trim() == '') return;
    if(find.name != useFindProductStore().name){
        useFindProductStore().resetData();
        find.nextCursor = null;
        find.allProductsLoaded = false;
    }
    if(find.isLoading || find.allProductsLoaded) return;
    find.isLoading = true;
    try{
        const res = await axios.post(route('product.find'), {name: find.name, business_id: null, cursor: find.nextCursor});
        if(!Array.isArray(res.data.data) && res.data.not_found){
            productNotFound.value = true;
            return;
        }
        if(!Array.isArray(res.data.data) && !res.data.not_found){
            useFindProductStore().products = [];
            router.visit(route('product.show', res.data.id));
            return;
        }
        if(Array.isArray(res.data.data) && !res.data.not_found){
            useFindProductStore().show = true;
            useFindProductStore().name = find.name;
            useFindProductStore().products.push(...res.data.data);
        }
        find.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            find.allProductsLoaded = true;
        }
    } catch(error){
        alert('error server');
    } finally {
        find.isLoading = false;
    }
}

const handleScroll = () => {
    if(!useFindProductStore().filtersEnabled){
        const scrollTop = window.scrollY;
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = window.innerHeight;
        if(scrollTop + clientHeight >= scrollHeight){
            findProduct();
        }
    }
}

watch(()=>useFindProductStore().filtersEnabled, (newValue, oldValue)=>{
    if(!newValue && oldValue){
        find.nextCursor = null;
        find.allProductsLoaded = false;
        useFindProductStore().products = [];
        findProduct();
    }
})

onMounted(() => {window.addEventListener('scroll', handleScroll);});

onUnmounted(() => {window.removeEventListener('scroll', handleScroll);});
</script>
<template>
    <header class="px-2.5 min-h-20 max-h-20 shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-b-[20px] flex items-center">
        <div class="flex w-full items-center gap-x-5">
            <Link :href="route('index')" class="max-lg:hidden">
                <img class="block h-10 max-xl:hidden" src="/public/img/logo.svg" alt="logo">
                <img class="min-h-10 hidden max-xl:block " src="/public/img/favicon.svg" alt="logo">
            </Link>
            <div class="flex items-center gap-2.5 grow">
                <button @click.prevent="useCatalogStore().show = !useCatalogStore().show" class="btn-dark-gray h-10 w-20">{{ !useCatalogStore().show ? useTranslateStore().t('catalog') : 'X' }}</button>
                <div class="grow relative">
                    <input @keyup.enter.prevent="findProduct" v-model="find.name" type="text" class="p-2.25 h-10 w-full border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNameProductOrArticle')">
                    <div v-if="productNotFound" class="text-red-500 text-sm absolute">{{ useTranslateStore().t('productNotFoundSearch') }}</div>
                </div>
                <button v-if="!find.isLoading" @click.prevent="findProduct" class="btn-green h-10 w-20">{{ useTranslateStore().t('find') }}</button>
                <div v-else class="flex justify-center items-center w-20 h-10">
                    <div class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
                </div>
            </div>
            <div class="max-sm:hidden">
                <Language></Language>
            </div>
            <div class="block max-lg:hidden">
                <Nav></Nav>
            </div>
        </div>
    </header>
</template>
