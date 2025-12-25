<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleProfile') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionProfile')">
    </Head>
    <MainLayout>
        <div class="w-full flex justify-center items-center">
            <select class="rounded-[10px] bg-violet-800  p-2 cursor-pointer focus:outline-none">
                <option value="">{{ useTranslateStore().t('userProfile') }}</option>
                <option value="">{{ useTranslateStore().t('businessProfile') }}</option>
                <option value="">{{ useTranslateStore().t('adminPanel') }}</option>
            </select>
        </div>
        <div class="mt-2.5 font-bold text-[20px]">{{ useTranslateStore().t('personalInformation') }}</div>
        <div class="flex items-center gap-x-2.5 mt-2.5">
            <div>Alex</div>
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
        </div>
        <div class="flex items-center gap-x-2.5 mt-2.5">
            <div>email@email.ru</div>
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
        </div>
        <div class="flex items-center gap-x-2.5 mt-2.5">
            <button class="btn-dark-gray h-10">{{ useTranslateStore().t('resetPassword') }}</button>
            <button class="border text-[14px] h-10 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer">{{ useTranslateStore().t('logout') }}</button>
        </div>
        <div class="flex items-center gap-x-2.5 mt-2.5">
            <div class="font-bold text-[20px]">{{ useTranslateStore().t('balance') }}: <span class="text-violet-800">2000</span></div>
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
        </div>
        <div class="font-bold text-[20px] mt-2.5">{{ useTranslateStore().t('youLooked') }}</div>
        <div class="flex justify-between flex-wrap mt-2.5">
            <Card />
            <Card />
            <Card />
            <Card />
        </div>
    </MainLayout>
</template>
