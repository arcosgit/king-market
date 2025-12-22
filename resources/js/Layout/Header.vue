<script setup>
import Auth from "@/Components/user/auth/Auth.vue";
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import axios from "axios";
import { ref, watch } from "vue";
import { route } from "ziggy-js";

const language = ref(useTranslateStore().currentLang);
const showAuthModalFlag = ref(false);
watch(language, async (newLang, OldLang) =>{
    useTranslateStore().currentLang = newLang;
    // const res = await axios.post(route('user.change.lang'), {lang: newLang});
    // console.log(res);
});
</script>
<template>
    <Auth :show="showAuthModalFlag" @close="showAuthModalFlag = false"></Auth>
    <header class="px-2.5 min-h-20 max-h-20 shadow-[0_4px_24px_0_rgba(255,255,255,0.4)] rounded-b-[20px] flex items-center">
        <div class="flex w-full gap-5">
            <img src="/public/img/logo.svg" alt="logo">
            <div class="flex gap-2.5 grow">
                <button class="btn-dark-gray h-10 w-20">{{ useTranslateStore().t('catalog') }}</button>
                <input type="text" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNameProductOrArticle')">
                <button class="btn-green h-10 w-20">{{ useTranslateStore().t('find') }}</button>
            </div>
            <select v-model="language" class="bg-white text-black rounded-[10px] focus:outline-none h-10 w-11 cursor-pointer">
                <option value="ru">RU</option>
                <option value="en">EN</option>
            </select>
            <div class="flex flex-col items-center w-18.75">
                <img class="h-5 w-5 block" src="/public/img/favorites.svg" alt="favorites">
                <div class="text-[14px]">{{ useTranslateStore().t('favorites') }}</div>
            </div>
            <div class="flex flex-col items-center cursor-pointer w-18.75">
                <img class="h-5 w-5 block" src="/public/img/basket.svg" alt="basket">
                <div class="text-[14px]">{{ useTranslateStore().t('basket') }}</div>
            </div>
            <div class="flex flex-col items-center w-18.75">
                <img class="h-5 w-5 block" src="/public/img/orders.svg" alt="orders">
                <div class="text-[14px]">{{ useTranslateStore().t('orders') }}</div>
            </div>
            <div v-if="useUserStore().id != null" class="flex flex-col items-center w-18.75 cursor-pointer">
                <img class="h-5 w-5 block" src="/public/img/profile.svg" alt="profile">
                <div class="text-[14px]">{{ useTranslateStore().t('profile') }}</div>
            </div>
            <div v-else @click.prevent="showAuthModalFlag = true" class="flex flex-col items-center w-18.75 cursor-pointer">
                <img class="h-5 w-5 block rotate-180" src="/public/img/login.svg" alt="login">
                <div class="text-[14px]">{{ useTranslateStore().t('login') }}</div>
            </div>
        </div>
    </header>
</template>
