<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import Auth from '../modals/Auth.vue';
import { ref } from "vue";
const showAuthModal = ref(false);
</script>
<template>
    <Auth :show="showAuthModal" @close="showAuthModal = false"></Auth>
    <div class="flex flex-col justify-center items-center gap-y-2.5">
        <div class="text-2xl font-bold">{{ useTranslateStore().t('noAuth') }}</div>
        <button @click.prevent="showAuthModal = true" class="btn-blue">{{ useTranslateStore().t('login') }}</button>
    </div>
</template>
