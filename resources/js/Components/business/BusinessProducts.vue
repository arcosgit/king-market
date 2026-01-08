<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBusinessStore} from "@/storage/business/business.js";
import Card from '@/Components/product/Card.vue';

</script>
<template>
    <div class="mt-2.5 flex gap-x-2.5 items-center">
        <div class="font-bold text-[20px]">{{ useTranslateStore().t('yourProducts') }}</div>
        <Link :href="route('product.create')">
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
        </Link>
    </div>
    <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
        <div v-for="(product, index) in useBusinessStore().products" :key="index" class="max-md:place-items-center">
            <Card :product="product" :turnOffFavorite="true">
                <div class="mt-1 flex justify-between">
                    <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
                    <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(91,33,182,1)] transition duration-150" src="/public/img/statistics.svg" alt="statistic">
                </div>
            </Card>
        </div>
    </div>
</template>
