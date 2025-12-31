<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('createProduct') }}</title>
        <meta name="description" :content="useTranslateStore().t('createProductDescription')">
    </Head>
    <MainLayout>
        <div class="text-center text-xl font-bold uppercase">{{ useTranslateStore().t('createProduct') }}</div>
        <input type="text" class="p-2.25 mt-2.5 w-full h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductName')">
        <textarea class="p-2.25 w-full mt-2.5 min-h-22.5 resize-y max-h-40 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductDescription')"></textarea>
        <input type="number" class="p-2.25 mt-2.5 w-57.5 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductPrice')">
        <div class="text-xl font-bold mt-2.5">{{ useTranslateStore().t('productCharacteristics') }}</div>
        <div class="opacity-70 text-sm">{{ useTranslateStore().t('forExampleCharacteristics') }}</div>
        <div class="flex items-center gap-x-2.5 mt-2.5 ">
            <input type="text" class="p-2.25 w-57.5 h-10 border-2 border-[#2980B9] rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('size')">
            <div class="w-5 h-0.5 bg-white"></div>
            <input type="text" class="p-2.25 w-57.5 h-10 border-2 border-violet-800 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('24sm')">
            <img class="w-10 h-10 cursor-pointer rounded-md hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/delete.svg" alt="delete">
        </div>
        <button class="btn-green mt-2.5">{{ useTranslateStore().t('add') }}</button>
        <div class="text-xl font-bold mt-2.5">{{ useTranslateStore().t('productPhotos') }}</div>
        <div class="opacity-70 text-sm">{{ useTranslateStore().t('rulesForPhotos') }}</div>
        <div class="flex flex-col w-65 mt-2.5">
            <div class="text-center">1</div>
            <img src="/public/img/delete_not_for_project.png" alt="image product">
        </div>
        <button class="btn-green mt-2.5">{{ useTranslateStore().t('add') }}</button>
        <div class="flex gap-x-2.5 mt-2.5 items-center">
            <div>{{ useTranslateStore().t('windowDisplayPhoto') }}</div>
            <select class="bg-white text-black rounded-[10px] focus:outline-none h-10 min-w-7 cursor-pointer">
                <option>1</option>
                <option>2</option>
            </select>
        </div>
        <select class="bg-[#263646] min-w-57.5 mt-2.5 rounded-[10px] focus:outline-none h-10 cursor-pointer">
            <option value="" disabled selected hidden>{{ useTranslateStore().t('choiceCategory') }}</option>
            <option value="1">Вариант 1</option>
            <option value="2">Вариант 2</option>
        </select>
        <div class="flex gap-x-2.5 mt-2.5">
            <button class="btn-blue min-w-40">{{ useTranslateStore().t('createProductBtn') }}</button>
            <button class="btn-purple min-w-40">{{ useTranslateStore().t('productPreview') }}</button>
        </div>
    </MainLayout>
</template>
