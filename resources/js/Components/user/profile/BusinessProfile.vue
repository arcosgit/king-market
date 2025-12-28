<script setup>
import axios from 'axios';
import Card from '../../product/Card.vue';
import Load from '@/Widgets/icons/Load.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBusinessStore} from "@/storage/business/business.js";
import { route } from 'ziggy-js';
import { onMounted, reactive, ref } from 'vue';
import TopNotification from '@/Widgets/notification/TopNotification.vue'

const load = reactive({main: true, createBrand: false, changeNameBrand: false});
const brandName = ref('');
const errors = reactive({createBrand: null});
const changeBrandNameFlag = ref(false);
const toastTopNotification = reactive({show: false, text: '', textParam: '', hideAfter: null});

const getBusiness = async () => {
    if(useBusinessStore().id != null){
        load.main = false;
        return;
    }
    try{
        const business = await axios.post(route('business.get'));
        useBusinessStore().name = business.data.name;
        setTimeout(() => {
            load.main = false;
            useBusinessStore().id = business.data.id;
        },1000);
    } catch (error){
        console.log(error);
    }
}

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

const changeNameBrand = async () => {
    if(brandName.value == useBusinessStore().name) return;
    load.changeNameBrand = true;
    toastTopNotification.hideAfter = null;
    try{
        const res = await axios.patch(route('business.change.name'), {name: brandName.value});
        setTimeout(() => {
            load.changeNameBrand = false;
            toastTopNotification.show = true;
            toastTopNotification.text = useTranslateStore().t('success');
            toastTopNotification.textParam = 'text-lime-500';
            toastTopNotification.hideAfter = 1500;
            brandName.value = res.data.name;
            useBusinessStore().name = res.data.name;
            changeBrandNameFlag.value = false;
        },1000);
    } catch (error){
        toastTopNotification.show = true;
        toastTopNotification.text = error.response.data.errors.name[0];
        toastTopNotification.textParam = 'text-red-500';
        load.changeNameBrand = false;
    }

}
onMounted(async () => {
    await getBusiness();
    brandName.value = useBusinessStore().name;
});
</script>
<template>
    <div v-if="toastTopNotification.show">
        <TopNotification :text="toastTopNotification.text" :textParam="toastTopNotification.textParam" :hideAfter="toastTopNotification.hideAfter" @close="toastTopNotification.show = false"></TopNotification>
    </div>
    <div v-if="load.main" class="flex justify-center items-center h-[calc(100%-17px)]">
        <Load text="load"></Load>
    </div>
    <div v-if="!load.main">
        <div v-if="useBusinessStore().id != null">
            <div v-if="!changeBrandNameFlag" class="flex gap-x-2.5 items-center" >
                <div class="font-bold text-[20px]">{{ useTranslateStore().t('yourBrand') }} - <span class="text-violet-800">{{ useBusinessStore().name }}</span></div>
                <img @click.prevent="changeBrandNameFlag = true" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
            </div>
            <div v-if="changeBrandNameFlag" class="flex gap-x-2.5 items-center">
                <input v-model="brandName" :readonly="load.changeNameBrand" type="text" class="p-2.25 w-70 h-7.5 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterBrand')">
                <div v-if="load.changeNameBrand" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
                <img @click.prevent="changeNameBrand" v-if="!load.changeNameBrand" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/confirm.svg" alt="confirm">
                <img @click.prevent="changeBrandNameFlag = false, brandName = useBusinessStore().name" v-if="!load.changeNameBrand" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/cancel.svg" alt="cancel">
            </div>
            <div class="mt-2.5 font-bold text-[20px]">{{ useTranslateStore().t('currentStatistics') }}</div>
            <div class="flex justify-between mt-2.5">
                <div class="flex items-center flex-col gap-y-1.25">
                    <div class="w-32.5 h-32.5 rounded-full border-2 border-lime-500 flex justify-center items-center">{{ useBusinessStore().sales }}</div>
                    <div>{{ useTranslateStore().t('sales') }}</div>
                </div>
                <div class="flex items-center flex-col gap-y-1.25">
                    <div class="w-32.5 h-32.5 rounded-full border-2 border-lime-500 flex justify-center items-center">{{ useBusinessStore().products }}</div>
                    <div>{{ useTranslateStore().t('products') }}</div>
                </div>
                <div class="flex items-center flex-col gap-y-1.25">
                    <div class="w-32.5 h-32.5 rounded-full border-2 border-lime-500 flex justify-center items-center">{{ useBusinessStore().rating }}</div>
                    <div>{{ useTranslateStore().t('rating') }}</div>
                </div>
                <div class="flex items-center flex-col gap-y-1.25">
                    <div class="w-32.5 h-32.5 rounded-full border-2 border-lime-500 flex justify-center items-center">{{ useBusinessStore().reviews }}</div>
                    <div>{{ useTranslateStore().t('reviews') }}</div>
                </div>
                <div class="flex items-center flex-col gap-y-1.25">
                    <div class="w-32.5 h-32.5 rounded-full border-2 border-lime-500 flex justify-center items-center">{{ useBusinessStore().profit }}</div>
                    <div>{{ useTranslateStore().t('profit') }}</div>
                </div>
            </div>
            <div class="mt-2.5 flex gap-x-2.5 items-center">
                <div class="font-bold text-[20px]">{{ useTranslateStore().t('yourProducts') }}</div>
                <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
            </div>
            <div class="mt-2.5 flex justify-between">
                <Card>
                    <div class="mt-1 flex justify-between">
                        <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
                        <img class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(91,33,182,1)] transition duration-150" src="/public/img/statistics.svg" alt="statistic">
                    </div>
                </Card>
            </div>
        </div>
        <div class="mt-2.5 flex flex-col gap-y-2.5" v-else>
            <div class="font-bold text-[20px]">{{useTranslateStore().t('noBrand')}}</div>
            <form @submit.prevent="createBrand" class="flex items-center gap-x-2.5">
                <input v-model="brandName" :readonly="load.createBrand" type="text" maxlength="255" class="p-2.25 w-75 h-10 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterBrand')" required>
                <button v-if="!load.createBrand" type="submit" class="btn-blue h-10 w-20">{{ useTranslateStore().t('create') }}</button>
                <div v-if="load.createBrand" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
            </form>
            <div v-if="errors.createBrand" class="text-red-500">{{ errors.createBrand }}</div>
        </div>
    </div>
</template>
