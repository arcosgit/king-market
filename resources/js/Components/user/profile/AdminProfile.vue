<script setup>
import { reactive } from 'vue';
import CategoryManagement from '@/Components/admin/CategoryManagement.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";

const show = reactive({category: false, products: false})
</script>
<template>
    <div class="mt-5 transition-all duration-300 bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-[10px]">
        <div class="px-2.5 py-3.75 flex justify-between items-center">
            <div class="text-xl">{{ useTranslateStore().t('categoryManagement') }}</div>
            <img @click.prevent="show.category = !show.category" :class="{ 'rotate-180': show.category }" class="cursor-pointer block transition duration-150" src="/public/img/arrow.svg" alt="open"/>
        </div>
        <div :class="['px-2.5 overflow-hidden', show.category ? 'grid grid-rows-[1fr] transition-all duration-300' : 'grid grid-rows-[0fr] transition-all duration-300']">
            <div class="overflow-hidden">
                <CategoryManagement></CategoryManagement>
            </div>
        </div>
    </div>
</template>
