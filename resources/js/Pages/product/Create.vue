<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCreateProductStore} from "@/storage/product/create.js";
import { Head } from '@inertiajs/vue3';
import Characteristics from '@/Components/product/create/Characteristics.vue';
import Images from '@/Components/product/create/Images.vue';
import Categories from '@/Components/product/create/Categories.vue';
import { reactive, ref } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';

const load = reactive({create: false});
const initialErrors = {name: null, description: null, price: null, img: null, category: null, characteristic: null};
const errors = reactive({...initialErrors});
const successCreated = ref(false);


const storeProduct = async () => {
    Object.assign(errors, initialErrors);
    if(useCreateProductStore().name.trim() == ''){
        errors.name = useTranslateStore().t('productNameError');
        return;
    }
    if(useCreateProductStore().description.trim() == ''){
        errors.description = useTranslateStore().t('productNameError');
        return;
    }
    if(useCreateProductStore().price == ''){
        errors.price = useTranslateStore().t('productNameError');
        return;
    }
    if(useCreateProductStore().images.length == 0){
        errors.img = useTranslateStore().t('onePhotoError');
        return;
    }
    if(useCreateProductStore().category.categoryId == null){
        errors.category = useTranslateStore().t('selectCategory');
        return;
    }
    if(useCreateProductStore().characteristics.length >= 1){
        for(let i = 0; i < useCreateProductStore().characteristics.length; i++){
            if(useCreateProductStore().characteristics[i].characteristic_key.trim() == '' || useCreateProductStore().characteristics[i].characteristic_value.trim() == ''){
                errors.characteristic = useTranslateStore().t('characteristicEmptyError');
                return;
            }
        }
    }
    load.create = true;
    try{
        const res = await axios.post(route('product.store'), {
            name: useCreateProductStore().name.trim(),
            description: useCreateProductStore().description.trim(),
            price: useCreateProductStore().price,
            characteristics: useCreateProductStore().characteristics,
            categories: useCreateProductStore().category,
        });
        if(res.data.success){
            setTimeout(()=>{
                load.create = false;
                useCreateProductStore().resetData();
                successCreated.value = true;
            },1500);
        }
    } catch(error){
        load.create = false;
    }
}

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('createProduct') }}</title>
        <meta name="description" :content="useTranslateStore().t('createProductDescription')">
    </Head>
    <MainLayout>
        <div v-if="!successCreated">
            <div class="text-center text-xl font-bold uppercase">{{ useTranslateStore().t('createProduct') }}</div>
            <input maxlength="255" v-model="useCreateProductStore().name" type="text" class="p-2.25 mt-2.5 w-full h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductName')">
            <div v-if="errors.name" class="mt-2.5 text-red-500">{{ errors.name }}</div>
            <textarea maxlength="2000" v-model="useCreateProductStore().description" class="p-2.25 w-full mt-2.5 min-h-22.5 resize-y max-h-40 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductDescription')"></textarea>
            <div v-if="errors.description" class="mt-2.5 text-red-500">{{ errors.description }}</div>
            <input v-model="useCreateProductStore().price" type="number" class="p-2.25 mt-2.5 w-57.5 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductPrice')">
            <div v-if="errors.price" class="mt-2.5 text-red-500">{{ errors.price }}</div>
            <Characteristics></Characteristics>
            <div v-if="errors.characteristic" class="mt-2.5 text-red-500">{{ errors.characteristic }}</div>
            <Images></Images>
            <div v-if="errors.img" class="mt-2.5 text-red-500">{{ errors.img }}</div>
            <Categories></Categories>
            <div v-if="errors.category" class="mt-2.5 text-red-500">{{ errors.category }}</div>
            <div class="flex gap-x-2.5 mt-2.5">
                <div v-if="load.create" class="min-w-40 h-10 flex justify-center items-center">
                    <div class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
                </div>
                <button v-if="!load.create" @click.prevent="storeProduct" class="btn-blue min-w-40 h-10">{{ useTranslateStore().t('createProductBtn') }}</button>
                <button class="btn-purple min-w-40 h-10">{{ useTranslateStore().t('productPreview') }}</button>
            </div>
            <div class="mt-2.5 text-gray-500">{{ useTranslateStore().t('allDataSaveExceptCategory') }}</div>
        </div>
        <div v-else class="flex justify-center items-center flex-col gap-y-2.5">
            <div class="text-center text-xl font-bold uppercase text-lime-500">{{ useTranslateStore().t('successCreateProduct') }}</div>
            <button @click.prevent="successCreated = false" class="btn-blue">{{ useTranslateStore().t('createNew') }}</button>
        </div>
    </MainLayout>
</template>
