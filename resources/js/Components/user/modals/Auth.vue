<script setup>
import ModalWindow from "@/Components/modal/ModalWindow.vue";
import Load from '@/Widgets/icons/Load.vue';
import Success from '@/Widgets/icons/Success.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import axios from "axios";
import { reactive, ref } from "vue";
import { route } from "ziggy-js";

const props = defineProps({show: Boolean});
const emit = defineEmits(['close']);
const auth = reactive({login: {email: '', password: ''}, signup: {name: '', email: '', password: '', repeatPassword: ''}});
const errors = reactive({login: {email: '', password: ''}, signup: {name: '', email: '', password: '', repeatPassword: ''}});
const loginSignupFlag = ref(false);
const load = ref(false);
const success = ref(false);


const setUser = (user) => {
    load.value = false;
    success.value = true;
    useUserStore().setUser(user);
    setTimeout(() => {
        success.value = false;
        emit('close', true);
    }, 2000);
}

const store = async () => {
    errors.signup = {};
    if(auth.signup.password != auth.signup.repeatPassword){
        errors.signup.repeatPassword = useTranslateStore().t('notSamePass');
        return;
    }
    load.value = true;
    try {
        const user = await axios.post(route('user.store'), {name: auth.signup.name, email: auth.signup.email,
        password: auth.signup.password, repeatPassword: auth.signup.repeatPassword, lang: useTranslateStore().currentLang});
        setTimeout(() => {
            setUser(user.data);
        }, 1000);
    } catch (error) {
        load.value = false;
        errors.signup.email = error.response.data.errors.email[0];
    }
}

const login = async () => {
    errors.login = {};
    load.value = true;
    try {
        const user = await axios.post(route('user.login'), {email: auth.login.email, password: auth.login.password, lang: useTranslateStore().currentLang});
        if(user.data.error_password){
            load.value = false;
            errors.login.password = user.data.error_password;
        } else {
            setTimeout(() => {
                setUser(user.data);
            }, 1000);
        }
    } catch (error) {
        load.value = false;
        errors.login.email = error.response.data.errors.email[0];
    }
}
</script>
<template>
    <ModalWindow :show="props.show" :hideTop="load || success" :name="loginSignupFlag ? useTranslateStore().t('signup').toUpperCase() : useTranslateStore().t('loginModal')" @close="$emit('close')">
        <div v-if="load" :class="loginSignupFlag ? 'h-79.25' : 'h-49.25'" class="flex justify-center items-center"><Load text="dataProcessing"></Load></div>
        <div v-if="success" :class="loginSignupFlag ? 'h-79.25' : 'h-49.25'" class="flex justify-center items-center"><Success /></div>
        <form @submit.prevent="login" v-if="!loginSignupFlag && !load && !success" class="flex flex-col gap-y-5 mt-2.5">
            <div class="relative">
                <input v-model="auth.login.email" type="email" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterEmail')" autocomplete="email" required>
                <div class="absolute text-red-500 text-sm">{{ errors.login.email }}</div>
            </div>
            <div class="relative">
                <input v-model="auth.login.password" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterPassword')" autocomplete="current-password" required>
                <div v-if="errors.login.password" class="absolute text-red-500 text-sm">{{ errors.login.password }}</div>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="btn-blue min-w-20">{{ useTranslateStore().t('login') }}</button>
                <div @click.prevent="loginSignupFlag = !loginSignupFlag" class="underline-text">{{ useTranslateStore().t('signup') }}</div>
            </div>
        </form>
        <form @submit.prevent="store" v-if="loginSignupFlag && !load && !success" class="flex flex-col gap-y-5 mt-2.5">
            <input v-model="auth.signup.name" maxlength="100" type="text" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterLogin')" autocomplete="name" required>
            <div class="relative">
                <input v-model="auth.signup.email" maxlength="255" type="email" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterEmail')" autocomplete="none" required>
                <div v-if="errors.signup.email" class="absolute text-red-500 text-sm">{{ errors.signup.email }}</div>
            </div>
            <input v-model="auth.signup.password" minlength="8" maxlength="255" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterPassword')" autocomplete="none" required>
            <div class="relative">
                <input v-model="auth.signup.repeatPassword" minlength="8" maxlength="255" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('repeatPassword')" autocomplete="none" required>
                <div v-if="errors.signup.repeatPassword" class="absolute text-red-500 text-sm">{{ errors.signup.repeatPassword }}</div>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="btn-blue min-w-20">{{ useTranslateStore().t('signup') }}</button>
                <div @click.prevent="loginSignupFlag = !loginSignupFlag" class="underline-text">{{ useTranslateStore().t('haveAcc') }}</div>
            </div>
        </form>
    </ModalWindow>
</template>
