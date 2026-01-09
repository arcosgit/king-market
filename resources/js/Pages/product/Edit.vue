<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCreateProductStore} from "@/storage/product/create.js";
import {useEditProductStore} from "@/storage/product/edit.js";
import { Head } from '@inertiajs/vue3';
import Characteristics from '@/Components/product/create/Characteristics.vue';
import Images from '@/Components/product/edit/Images.vue';
import Categories from '@/Components/product/edit/Categories.vue';
import { onMounted, onUnmounted, reactive, ref } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import ImagesPreview from '@/Components/product/show/Images.vue';
import Summary from '@/Components/product/show/Summary.vue';


const props = defineProps({product: Object});
const load = reactive({edit: false});
const initialErrors = {name: null, description: null, price: null, img: null, category: null, characteristic: null};
const errors = reactive({...initialErrors});
const successCreated = ref(false);
const preview = ref(false);


const checkProduct = () => {
    Object.assign(errors, initialErrors);
    if(useEditProductStore().name.trim() == ''){
        errors.name = useTranslateStore().t('productNameError');
        return false;
    }
    if(useEditProductStore().description.trim() == ''){
        errors.description = useTranslateStore().t('productNameError');
        return false;
    }
    if(useEditProductStore().price == ''){
        errors.price = useTranslateStore().t('productNameError');
        return false;
    }
    if(useEditProductStore().images.length == 0){
        errors.img = useTranslateStore().t('onePhotoError');
        return false;
    }
    if(useEditProductStore().category.categoryId == null){
        errors.category = useTranslateStore().t('selectCategory');
        return false;
    }
    if(useEditProductStore().characteristics.length >= 1){
        for(let i = 0; i < useEditProductStore().characteristics.length; i++){
            if(useEditProductStore().characteristics[i].characteristic_key.trim() == '' || useEditProductStore().characteristics[i].characteristic_value.trim() == ''){
                errors.characteristic = useTranslateStore().t('characteristicEmptyError');
                return false;
            }
        }
    }
    return true;
}

const editProduct = async () => {
    if(!checkProduct()) return;
    // load.edit = true;
    try{
        const res = await axios.post(route('product.edit.save', props.product.id), {
            name: useEditProductStore().name.trim(),
            description: useEditProductStore().description.trim(),
            price: useEditProductStore().price,
            characteristics: useEditProductStore().characteristics,
            categories: useEditProductStore().category,
            images: useEditProductStore().images,
        });
        console.log(res);
        // if(res.data.success){
        //     setTimeout(()=>{
        //         load.edit = false;
        //         useCreateProductStore().resetData();
        //         successCreated.value = true;
        //     },1500);
        // }
    } catch(error){
        console.log(error);
        // load.edit = false;
    }
}

const showPreview = () => {
    if(!checkProduct()) return;
    preview.value = true;
}

onMounted(() => {
    useEditProductStore().id = props.product.id;
    useEditProductStore().name = props.product.name;
    useEditProductStore().description = props.product.description;
    useEditProductStore().price = props.product.price;
    useEditProductStore().characteristics.push(...props.product.characteristics);
    useEditProductStore().images.push(...props.product.images);
    useEditProductStore().setConstCategories(props.product.categories);
    console.log(props.product);
});
onUnmounted(()=>{
    useEditProductStore().resetData();
})

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('editProduct') }}</title>
        <meta name="description" :content="useTranslateStore().t('editProduct')">
    </Head>
    <MainLayout>
        <div v-if="!preview">
            <div v-if="!successCreated">
                <div class="text-center text-xl font-bold uppercase">{{ useTranslateStore().t('editProduct') }}</div>
                <input maxlength="255" v-model="useEditProductStore().name" type="text" class="p-2.25 mt-2.5 w-full h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductName')">
                <div v-if="errors.name" class="mt-2.5 text-red-500">{{ errors.name }}</div>
                <textarea maxlength="2000" v-model="useEditProductStore().description" class="p-2.25 w-full mt-2.5 min-h-22.5 resize-y max-h-40 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductDescription')"></textarea>
                <div v-if="errors.description" class="mt-2.5 text-red-500">{{ errors.description }}</div>
                <input v-model="useEditProductStore().price" type="number" class="p-2.25 mt-2.5 w-57.5 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterProductPrice')">
                <div v-if="errors.price" class="mt-2.5 text-red-500">{{ errors.price }}</div>
                <Characteristics :isEdit="true"></Characteristics>
                <div v-if="errors.characteristic" class="mt-2.5 text-red-500">{{ errors.characteristic }}</div>
                <Images></Images>
                <div v-if="errors.img" class="mt-2.5 text-red-500">{{ errors.img }}</div>
                <Categories></Categories>
                <div v-if="errors.category" class="mt-2.5 text-red-500">{{ errors.category }}</div>
                <div class="flex gap-x-2.5 mt-2.5">
                    <div v-if="load.edit" class="min-w-40 h-10 flex justify-center items-center">
                        <div class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
                    </div>
                    <button v-if="!load.edit" @click.prevent="editProduct" class="btn-blue min-w-40 h-10">{{ useTranslateStore().t('editProductBtn') }}</button>
                    <button @click.prevent="showPreview" class="btn-purple min-w-40 h-10">{{ useTranslateStore().t('productPreview') }}</button>
                    <button class="border text-[14px] h-10 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer">{{ useTranslateStore().t('deleteProduct') }}</button>
                </div>
            </div>
            <div v-else class="flex justify-center items-center flex-col gap-y-2.5">
                <div class="text-center text-xl font-bold uppercase text-lime-500">{{ useTranslateStore().t('successCreateProduct') }}</div>
                <button @click.prevent="successCreated = false" class="btn-blue">{{ useTranslateStore().t('createNew') }}</button>
            </div>
        </div>
        <div v-else>
            <div class="flex justify-between gap-x-5 w-full items-start">
                <ImagesPreview :images="useEditProductStore().images"></ImagesPreview>
                <Summary :product="useEditProductStore()" :preview="true"></Summary>
                <div class="w-full h-125 rounded-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5">
                    <div class="text-base font-bold text-center">{{ useTranslateStore().t('relatedProducts') }}</div>
                </div>
            </div>
            <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('description') }}</div>
            <div class="text-base wrap-break-word">{{ useEditProductStore().description }}</div>
            <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('aboutProduct') }}</div>
            <div v-for="(characteristic, index) in useEditProductStore().characteristics" :key="index" class="mt-1"><span class="text-gray">{{ characteristic.characteristic_key }}</span> {{ characteristic.characteristic_value }}</div>
            <div class="text-xl font-bold">{{ useTranslateStore().t('productReviews') }}</div>
            <button @click.prevent="preview = false" class="btn-green mt-2.5">{{ useTranslateStore().t('backСreation') }}</button>
        </div>
    </MainLayout>
</template>
