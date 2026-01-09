<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useEditProductStore} from "@/storage/product/edit.js";
import axios from "axios";
import { reactive } from "vue";
import { route } from "ziggy-js";

const errors = reactive({fileSize: null, maxImages: null});
const load = reactive({img: false});

const saveImg = async (event) => {
    errors.fileSize = null;
    errors.maxImages = null;
    if(useEditProductStore().images.length >= 10){
        errors.maxImages = useTranslateStore().t('maxImgs');
        setTimeout(() => {errors.maxImages = null;},5000);
        return;
    }
    const file = event.target.files[0];
    const maxSize = 5242880;
    if(file.size > maxSize){
        errors.fileSize = useTranslateStore().t('maxSizeFileFive');
        event.target.value = '';
        setTimeout(() => {errors.fileSize = null;},5000);
        return;
    }
    load.img = true;
    try{
        const formData = new FormData();
        formData.append('img', file);
        const res = await axios.post(route('product.save.img', useEditProductStore().id), formData);
        useEditProductStore().images.push({img_id: res.data.img_id, img: res.data.path, hide: res.data.hide});
    } catch(error){
        errors.fileSize = error.response.data.errors.img[0];
    }
    load.img = false;
}

</script>
<template>
    <div class="text-xl font-bold mt-2.5">{{ useTranslateStore().t('productPhotos') }}</div>
    <div class="opacity-70 text-sm">{{ useTranslateStore().t('rulesForPhotos') }}</div>
    <div v-if="useEditProductStore().images.length >= 1" class="flex gap-x-4.5 flex-wrap">
        <div v-for="(img, index) in useEditProductStore().images" :key="index" class="flex flex-col w-65 mt-2.5">
            <div class="text-center text-xl">{{ index + 1 }}</div>
            <div @click.prevent="useEditProductStore().images.splice(index, 1)" class="relative max-w-65 max-h-65 min-w-65 min-h-65 rounded-[10px] overflow-hidden cursor-pointer">
                <img class="w-full h-full object-cover object-center" :src="img.img" alt="image product" />
                <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-200 bg-red-500/30 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <line x1="0" y1="0" x2="100" y2="100" stroke="red" stroke-width="3" />
                        <line x1="0" y1="100" x2="100" y2="0" stroke="red" stroke-width="3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="mt-5 text-xl text-center text-orange-300">{{ useTranslateStore().t('noImg') }}</div>
    <div v-if="!load.img" class="mt-2.5">
        <input @change.prevent="saveImg($event)" type="file" id="file" accept="image/png, image/jpeg, image/jpg" class="hidden">
        <label for="file" class="btn-green w-[86.3px] h-10">{{ useTranslateStore().t('add') }}</label>
    </div>
    <div v-if="load.img" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
    <div v-if="errors.fileSize" class="text-red-500 mt-2.5">{{ errors.fileSize }}</div>
    <div v-if="errors.maxImages" class="text-red-500 mt-2.5">{{ errors.maxImages }}</div>
</template>
