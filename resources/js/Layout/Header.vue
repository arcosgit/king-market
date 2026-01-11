<script setup>
import Auth from "@/Components/user/modals/Auth.vue";
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import {useFindProductStore} from "@/storage/product/find.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import { reactive, ref, watch } from "vue";
import { router } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import axios from "axios";


const find = reactive({name: '', isLoading: false});
const productNotFound = ref(null);
const language = ref(useTranslateStore().currentLang);
const showAuthModalFlag = ref(false);

const findProduct = async () => {
    productNotFound.value = null;
    if(find.name.trim() == ''){
        useFindProductStore().products = [];
        return;
    };
    find.isLoading = true;
    try{
        const res = await axios.post(route('product.find'), {name: find.name, business_id: null});
        console.log(res);
        if(!Array.isArray(res.data) && res.data.not_found){
            productNotFound.value = true;
            return;
        }
        if(!Array.isArray(res.data) && !res.data.not_found){
            useFindProductStore().products = [];
            router.visit(route('product.show', res.data.id));
        }
        if(Array.isArray(res.data) && !res.data.not_found){
            useFindProductStore().name = find.name;
            useFindProductStore().products = res.data;
        }
    } catch(error){
        alert('error server');
    } finally {
        find.isLoading = false;
    }
}

watch(language, async (newLang, OldLang) =>{
    useTranslateStore().currentLang = newLang;
    window.axios.defaults.headers.common['X-Lang'] = newLang;
});
</script>
<template>
    <Auth :show="showAuthModalFlag" @close="showAuthModalFlag = false"></Auth>
    <header class="px-2.5 min-h-20 max-h-20 shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-b-[20px] flex items-center">
        <div class="flex w-full gap-x-5">
            <Link :href="route('index')">
                <img class="block h-10" src="/public/img/logo.svg" alt="logo">
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
            <select v-model="language" class="bg-white text-black rounded-[10px] focus:outline-none h-10 w-11 cursor-pointer">
                <option value="ru">RU</option>
                <option value="en">EN</option>
            </select>
            <Link :href="route('user.favorite.product')">
                <div class="flex flex-col items-center w-18.75">
                    <img class="h-5 w-5 block" src="/public/img/favorites.svg" alt="favorites">
                    <div class="text-[14px]">{{ useTranslateStore().t('favorites') }}</div>
                </div>
            </Link>
            <div @click.prevent="useBasketStore().isOpen = !useBasketStore().isOpen" class="flex flex-col items-center cursor-pointer w-18.75">
                <img class="h-5 w-5 block" src="/public/img/basket.svg" alt="basket">
                <div class="text-[14px]">{{ useTranslateStore().t('basket') }}</div>
            </div>
            <Link :href="route('user.orders')">
                <div class="flex flex-col items-center w-18.75">
                    <img class="h-5 w-5 block" src="/public/img/orders.svg" alt="orders">
                    <div class="text-[14px]">{{ useTranslateStore().t('orders') }}</div>
                </div>
            </Link>
            <Link v-if="useUserStore().id != null" :href="route('user.profile')">
                <div class="flex flex-col items-center w-18.75">
                    <img class="h-5 w-5 block" src="/public/img/profile.svg" alt="profile">
                    <div class="text-[14px]">{{ useTranslateStore().t('profile') }}</div>
                </div>
            </Link>
            <div v-else @click.prevent="showAuthModalFlag = true" class="flex flex-col items-center w-18.75 cursor-pointer">
                <img class="h-5 w-5 block rotate-180" src="/public/img/login.svg" alt="login">
                <div class="text-[14px]">{{ useTranslateStore().t('login') }}</div>
            </div>
        </div>
    </header>
</template>
