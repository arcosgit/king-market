<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBusinessStore} from "@/storage/business/business.js";
import Card from '@/Components/product/Card.vue';
import { onMounted, onUnmounted, reactive } from "vue";
import axios from "axios";
import { route } from "ziggy-js";

const find = reactive({name: '', nameConst: '', products: [], nextCursor: null, allProductsLoaded: false, isLoading: false});
const errors = reactive({notFound: null});

const findProducts = async () => {
    errors.notFound = null;
    if(find.name.trim() == ''){
        useBusinessStore().isSearchEnabled = false;
        resetFind();
        return;
    }
    if(find.nameConst.trim() != '' && find.name != find.nameConst) resetFind();
    if(find.isLoading || find.allProductsLoaded) return;
    find.isLoading = true;
    try{
        const res = await axios.post(route('product.find'), {name: find.name, business_id: useBusinessStore().id, cursor: find.nextCursor});
        if(!Array.isArray(res.data.data) && res.data.not_found){
            errors.notFound = true;
            return;
        }
        if(!Array.isArray(res.data.data) && !res.data.not_found && res.data.id){
            useBusinessStore().isSearchEnabled = true;
            find.allProductsLoaded = true;
            find.nameConst = find.name;
            find.products = [res.data];
            return;
        }
        if(Array.isArray(res.data.data) && !res.data.not_found){
            useBusinessStore().isSearchEnabled = true;
            find.nameConst = find.name;
            find.products.push(...res.data.data);
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

const resetFind = () => {
    find.products = [];
    find.nextCursor = null;
    find.allProductsLoaded = false;
}

const handleScroll = () => {
    if(useBusinessStore().isSearchEnabled){
        const scrollTop = window.scrollY;
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = window.innerHeight;
        if(scrollTop + clientHeight >= scrollHeight){
            findProducts();
        }
    }
}

onMounted(() => {window.addEventListener('scroll', handleScroll);});

onUnmounted(() => {
    useBusinessStore().isSearchEnabled = false;
    window.removeEventListener('scroll', handleScroll);
});

</script>
<template>
    <div class="mt-2.5 flex gap-x-2.5 items-center">
        <div class="font-bold text-[20px]">{{ useTranslateStore().t('yourProducts') }}</div>
        <Link :href="route('product.create')">
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
        </Link>
    </div>
    <div v-if="useBusinessStore().products.length > 0">
        <div class="flex gap-x-2.5 mt-2.5">
            <div class="relative w-full">
                <input @keyup.enter.prevent="findProducts" v-model="find.name" type="text" class="p-2.25 h-10 w-full border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNameProductOrArticle')">
                <div v-if="errors.notFound" class="text-red-500">{{ useTranslateStore().t('productNotFoundSearch') }}</div>
            </div>
            <button @click.prevent="findProducts" class="btn-green h-10 w-20">{{ useTranslateStore().t('find') }}</button>
        </div>
        <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
            <div v-for="(product, index) in find.products.length > 0 ? find.products : useBusinessStore().products" :key="index" class="max-md:place-items-center">
                <Card :product="product" :turnOffFavorite="true">
                    <div class="mt-1 flex justify-between">
                        <Link :href="route('product.edit', product.id)">
                            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
                        </Link>
                    </div>
                </Card>
            </div>
        </div>
    </div>
</template>
