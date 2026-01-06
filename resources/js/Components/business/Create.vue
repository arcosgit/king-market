<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { route } from 'ziggy-js';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBusinessStore} from "@/storage/business/business.js";

const load = reactive({createBrand: false});
const errors = reactive({createBrand: null});
const brandName = ref('');

const createBrand = async () => {
    load.createBrand = true;
    try {
        const res = await axios.post(route('business.create'), {name: brandName.value});
        setTimeout(() => {
            load.createBrand = false;
            useBusinessStore().id = res.data.id;
            useBusinessStore().name = res.data.name;
        },1000);
    } catch (error){
        load.createBrand = false;
        errors.createBrand = error.response.data.errors.name[0];
    }
}
</script>
<template>
    <div class="font-bold text-[20px]">{{useTranslateStore().t('noBrand')}}</div>
    <form @submit.prevent="createBrand" class="flex items-center gap-x-2.5">
        <input v-model="brandName" :readonly="load.createBrand" type="text" maxlength="255" class="p-2.25 w-75 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterBrand')" required>
        <button v-if="!load.createBrand" type="submit" class="btn-blue h-10 w-20">{{ useTranslateStore().t('create') }}</button>
        <div v-if="load.createBrand" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
    </form>
    <div v-if="errors.createBrand" class="text-red-500">{{ errors.createBrand }}</div>
</template>
