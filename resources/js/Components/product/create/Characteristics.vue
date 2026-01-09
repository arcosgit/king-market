<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCreateProductStore} from "@/storage/product/create.js";
import {useEditProductStore} from "@/storage/product/edit.js";
import { reactive } from "vue";

const props = defineProps({isEdit: {type: Boolean, default: false}});
const errors = reactive({characteristicsLength: null});

const addCharacteristic = () => {
    errors.characteristicsLength = null;
    if(useCreateProductStore().characteristics.length >= 20 || props.isEdit && useEditProductStore().characteristics.length >=20){
        errors.characteristicsLength = useTranslateStore().t('characteristicsLengthError');
        setTimeout(() => {errors.characteristicsLength = null;},5000);
        return;
    }
    if(!props.isEdit){
        useCreateProductStore().characteristics.push({characteristic_key: '', characteristic_value: ''});
    } else {
        useEditProductStore().characteristics.push({characteristic_key: '', characteristic_value: ''});
    }
}
</script>
<template>
    <div class="text-xl font-bold mt-2.5">{{ useTranslateStore().t('productCharacteristics') }}</div>
    <div class="opacity-70 text-sm">{{ useTranslateStore().t('forExampleCharacteristics') }}</div>
    <div v-for="(characteristic, index) in !props.isEdit ? useCreateProductStore().characteristics : useEditProductStore().characteristics" :key="index" class="flex items-center gap-x-2.5 mt-2.5 ">
        <input maxlength="255" v-model="characteristic.characteristic_key" type="text" class="p-2.25 w-57.5 h-10 border-2 border-[#2980B9] rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('size')">
        <div class="w-5 h-0.5 bg-white"></div>
        <input maxlength="255" v-model="characteristic.characteristic_value" type="text" class="p-2.25 w-57.5 h-10 border-2 border-violet-800 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('24sm')">
        <img @click.prevent="!props.isEdit ? useCreateProductStore().characteristics.splice(index, 1) : useEditProductStore().characteristics.splice(index, 1)" class="w-10 h-10 cursor-pointer rounded-md hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/delete.svg" alt="delete">
    </div>
    <button @click.prevent="addCharacteristic" class="btn-green mt-2.5 h-10">{{ useTranslateStore().t('add') }}</button>
    <div class="text-red-500 mt-2.5" v-if="errors.characteristicsLength">{{ errors.characteristicsLength }}</div>
</template>
