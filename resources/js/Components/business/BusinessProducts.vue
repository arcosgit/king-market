<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBusinessStore} from "@/storage/business/business.js";
import Card from '@/Components/product/Card.vue';
import { reactive } from "vue";
import axios from "axios";
import { route } from "ziggy-js";

const find = reactive({name: '', nextCursor: null, allProductsLoaded: false, isLoading: false});

const findProducts = async () => {
    const res = await axios.post(route('product.find'), {name: find.name, business_id: useBusinessStore().id, cursor: find.nextCursor});
    console.log(res);
}

</script>
<template>
    <div class="mt-2.5 flex gap-x-2.5 items-center">
        <div class="font-bold text-[20px]">{{ useTranslateStore().t('yourProducts') }}</div>
        <Link :href="route('product.create')">
            <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
        </Link>
    </div>
    <div class="flex gap-x-2.5 mt-2.5">
        <div class="relative w-full">
            <input @keyup.enter.prevent="findProducts" v-model="find.name" type="text" class="p-2.25 h-10 w-full border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNameProductOrArticle')">
            <!-- <div class="text-red-500 text-sm">{{ useTranslateStore().t('productNotFoundSearch') }}</div> -->
        </div>
        <button @click.prevent="findProducts" class="btn-green h-10 w-20">{{ useTranslateStore().t('find') }}</button>
    </div>
    <div class="mt-2.5 grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
        <div v-for="(product, index) in useBusinessStore().products" :key="index" class="max-md:place-items-center">
            <Card :product="product" :turnOffFavorite="true">
                <div class="mt-1 flex justify-between">
                    <Link :href="route('product.edit', product.id)">
                        <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
                    </Link>
                </div>
            </Card>
        </div>
    </div>
</template>
