<script setup>
import Card from '@/Components/product/Card.vue';
import ResetPassword from '@/Components/user/modals/ResetPassword.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import { reactive, ref } from 'vue';
import TopUpBalance from '@/Components/user/modals/TopUpBalance.vue'
import axios from 'axios';
import { route } from 'ziggy-js';
import TopNotification from '@/Widgets/notification/TopNotification.vue';


const btnsChange = reactive({name: false, email: false, password: false, balance: false});
const userData = reactive({name: useUserStore().name, email: useUserStore().email});
const toastTopNotification = reactive({show: false, text: '', textParam: '', hideAfter: null});
const load = ref(false);

const errorChangeNotification = () => {
    load.value = false;
    toastTopNotification.show = true;
    toastTopNotification.textParam = 'text-red-500';
}

const successChangeNotification = () => {
    load.value = false;
    toastTopNotification.show = true;
    toastTopNotification.text = useTranslateStore().t('success');
    toastTopNotification.textParam = 'text-lime-500';
    toastTopNotification.hideAfter = 1500;
}

const changeName = async () => {
    if(userData.name == useUserStore().name){
        return;
    }
    load.value = true;
    toastTopNotification.hideAfter = null;
    try{
        const res = await axios.patch(route('user.change.name'), {name: userData.name});
        setTimeout(() => {
            useUserStore().name = res.data.name;
            successChangeNotification();
            btnsChange.name = false;
        }, 1000);
    } catch (error){
        errorChangeNotification();
        toastTopNotification.text = error.response.data.errors.name[0];
    }
}

const changeEmail = async () => {
    if(userData.email == useUserStore().email){
        return;
    }
    load.value = true;
    toastTopNotification.hideAfter = null;
    try{
        const res = await axios.patch(route('user.change.email'), {email: userData.email});
        setTimeout(() => {
            useUserStore().email = res.data.email;
            successChangeNotification();
            btnsChange.email = false;
        }, 1000);
    } catch (error){
        errorChangeNotification();
        toastTopNotification.text = error.response.data.errors.email[0];
    }
}

const logout = async () => {
    try{
        await axios.post(route('user.logout'));
        useUserStore().resetUser();
    } catch(error){
        alert('server error');
    }
}
</script>
<template>
    <ResetPassword :show="btnsChange.password" @close="btnsChange.password = !btnsChange.password"></ResetPassword>
    <TopUpBalance :show="btnsChange.balance" @close="btnsChange.balance = !btnsChange.balance"></TopUpBalance>
    <TopNotification v-if="toastTopNotification.show" :text="toastTopNotification.text" :textParam="toastTopNotification.textParam" :hideAfter="toastTopNotification.hideAfter" @close="toastTopNotification.show = false"></TopNotification>
    <div class="mt-2.5 font-bold text-[20px]">{{ useTranslateStore().t('personalInformation') }}</div>
    <div class="flex items-center gap-x-2.5 mt-2.5">
        <div v-if="!btnsChange.name" class="flex gap-x-2.5 items-center" >
            <div>{{ useUserStore().name }}</div>
            <img @click.prevent="btnsChange.name = !btnsChange.name" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
        </div>
        <div v-if="btnsChange.name" class="flex gap-x-2.5 items-center">
            <input v-model="userData.name" :readonly="load" type="text" class="p-2.25 w-70 h-7.5 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterLogin')">
            <div v-if="load" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
            <img v-if="!load" @click.prevent="changeName" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/confirm.svg" alt="confirm">
            <img v-if="!load" @click.prevent="btnsChange.name = !btnsChange.name, userData.name = useUserStore().name" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/cancel.svg" alt="cancel">
        </div>
    </div>
    <div class="flex items-center gap-x-2.5 mt-2.5">
        <div v-if="!btnsChange.email" class="flex gap-x-2.5 items-center" >
            <div>{{ useUserStore().email }}</div>
            <img @click.prevent="btnsChange.email = !btnsChange.email" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(41,128,185,1)] transition duration-150" src="/public/img/edit.svg" alt="edit">
        </div>
        <div v-if="btnsChange.email" class="flex gap-x-2.5 items-center">
            <input v-model="userData.email" :readonly="load" type="text" class="p-2.25 w-70 h-7.5 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterEmail')">
            <div v-if="load" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
            <img v-if="!load" @click.prevent="changeEmail" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/confirm.svg" alt="confirm">
            <img v-if="!load" @click.prevent="btnsChange.email = !btnsChange.email, userData.email = useUserStore().email" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/cancel.svg" alt="cancel">
        </div>
    </div>
    <div class="flex items-center gap-x-2.5 mt-2.5">
        <button @click.prevent="btnsChange.password = !btnsChange.password" class="btn-dark-gray h-10">{{ useTranslateStore().t('resetPassword') }}</button>
        <button @click.prevent="logout" class="border text-[14px] h-10 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer">{{ useTranslateStore().t('logout') }}</button>
    </div>
    <div class="flex items-center gap-x-2.5 mt-2.5">
        <div class="font-bold text-[20px]">{{ useTranslateStore().t('balance') }}: <span class="text-violet-800">{{ useUserBalanceStore().balance }}</span></div>
        <img @click.prevent="btnsChange.balance = !btnsChange.balance" class="cursor-pointer rounded-[5px] hover:shadow-[0_0px_15px_0_rgba(138,201,121,1)] transition duration-150" src="/public/img/add.svg" alt="add">
    </div>
    <div class="font-bold text-[20px] mt-2.5">{{ useTranslateStore().t('youLooked') }}</div>
    <div class="flex justify-between flex-wrap mt-2.5">
        <!-- <Card />
        <Card />
        <Card />
        <Card /> -->
    </div>
</template>
