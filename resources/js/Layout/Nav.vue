<script setup>
import Auth from "@/Components/user/modals/Auth.vue";
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import { onMounted, onUnmounted, ref } from "vue";
import Language from '../Components/user/helpers/Language.vue'
const showAuthModalFlag = ref(false);
const isMobile = ref(false);

const checkWidth = () => {isMobile.value = window.innerWidth < 1024}
onMounted(() => {
    checkWidth();
    window.addEventListener('resize', checkWidth);
});
onUnmounted(() => {window.removeEventListener('resize', checkWidth)});
</script>
<template>
    <Teleport to="body">
        <Auth :show="showAuthModalFlag" @close="showAuthModalFlag = false"></Auth>
    </Teleport>
    <div class="flex justify-between items-center gap-x-5 max-[359px]:gap-x-2.5">
        <Link :href="route('index')" class="hidden max-lg:block">
            <div class="flex flex-col items-center ">
                <img class="h-5 w-5 block max-[425px]:h-4 max-[425px]:w-4" src="/public/img/home.svg" alt="home">
                <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('main') }}</div>
            </div>
        </Link>
        <Link :href="route('user.favorite.product')" :class="{'hidden': isMobile && useUserStore().id == null}">
            <div class="flex flex-col items-center ">
                <img class="h-5 w-5 block max-[425px]:h-4 max-[425px]:w-4" src="/public/img/favorites.svg" alt="favorites">
                <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('favorites') }}</div>
            </div>
        </Link>
        <div @click.prevent="useBasketStore().isOpen = !useBasketStore().isOpen" class="flex flex-col items-center cursor-pointer ">
            <img class="h-5 w-5 block max-[425px]:h-4 max-[425px]:w-4" src="/public/img/basket.svg" alt="basket">
            <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('basket') }}</div>
        </div>
        <Link :href="route('user.orders')" :class="{'hidden': isMobile && useUserStore().id == null}">
            <div class="flex flex-col items-center ">
                <img class="h-5 w-5 block max-[425px]:h-4 max-[425px]:w-4" src="/public/img/orders.svg" alt="orders">
                <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('orders') }}</div>
            </div>
        </Link>
        <div v-if="isMobile" :class="{'hidden': useUserStore().id != null}">
            <Language></Language>
        </div>
        <Link v-if="useUserStore().id != null" :href="route('user.profile')">
            <div class="flex flex-col items-center ">
                <img class="h-5 w-5 block max-[425px]:h-4 max-[425px]:w-4" src="/public/img/profile.svg" alt="profile">
                <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('profile') }}</div>
            </div>
        </Link>
        <div v-else @click.prevent="showAuthModalFlag = true" class="flex flex-col items-center cursor-pointer">
            <img class="h-5 w-5 block rotate-180 max-[425px]:h-4 max-[425px]:w-4" src="/public/img/login.svg" alt="login">
            <div class="text-[14px] max-[425px]:text-[12px]">{{ useTranslateStore().t('login') }}</div>
        </div>
    </div>
</template>
