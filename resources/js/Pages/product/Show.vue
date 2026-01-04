<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import Images from '@/Components/product/show/Images.vue'
import Summary from '@/Components/product/show/Summary.vue'

const props = defineProps({product: Object});

onMounted(()=>{
    console.log(props.product);
});
</script>
<template>
    <Head>
        <title>{{ props.product.name }}</title>
        <meta name="description" :content="props.product.description">
    </Head>
    <MainLayout>
        <div class="flex justify-between gap-x-5 w-full items-start">
            <Images :images="props.product.images"></Images>
            <Summary :product="props.product"></Summary>
            <div class="w-full h-125 rounded-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5">
                <div class="text-base font-bold text-center">{{ useTranslateStore().t('relatedProducts') }}</div>
            </div>
        </div>
        <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('description') }}</div>
        <div class="text-base wrap-break-word">{{ props.product.description }}</div>
        <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('aboutProduct') }}</div>
        <div v-for="characteristic in props.product.characteristics" class="mt-1"><span class="text-gray">{{ characteristic.characteristic_key }}</span> {{ characteristic.characteristic_value }}</div>
        <div class="flex items-center gap-x-2.5 mt-5">
            <div class="text-xl font-bold">{{ useTranslateStore().t('productReviews') }}</div>
            <select class="bg-[#263646] rounded-[10px] h-10 focus:outline-none cursor-pointer">
                <option value="">{{ useTranslateStore().t('last') }}</option>
                <option value="">⭐⭐⭐⭐⭐</option>
                <option value="">⭐⭐⭐⭐</option>
                <option value="">⭐⭐⭐</option>
                <option value="">⭐⭐</option>
                <option value="">⭐</option>
            </select>
        </div>
        <div class="flex flex-col gap-y-5">
            <div class="flex flex-col gap-y-1 mt-2.5">
                <div class="flex gap-x-2.5">
                    <div class="text-base">Николай Н.</div>
                    <div class="text-base text-gray">08.12.2025</div>
                    <div class="text-base">⭐⭐⭐⭐⭐</div>
                </div>
                <div>Я считаю это товар говна</div>
            </div>
        </div>
    </MainLayout>
</template>
