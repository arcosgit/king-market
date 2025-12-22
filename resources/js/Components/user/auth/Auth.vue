<script setup>
import ModalWindow from "@/Components/modal/ModalWindow.vue";
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from "axios";
import { reactive, ref } from "vue";
import { route } from "ziggy-js";
const props = defineProps({show: Boolean});
const loginSignupFlag = ref(false);
const auth = reactive({login: {email: '', password: ''}, signup: {name: '', email: '', password: '', repeatPassword: ''}});

const store = async () => {
    try {
        const res = await axios.post(route('user.store'), {name: auth.signup.name, email: auth.signup.email,
        password: auth.signup.password, repeatPassword: auth.signup.repeatPassword, lang: useTranslateStore().currentLang});
        console.log(res);
    } catch (error) {
        console.log(error);
    }
}
</script>
<template>
    <ModalWindow :show="props.show" :name="loginSignupFlag ? useTranslateStore().t('signup').toUpperCase() : useTranslateStore().t('loginModal')">
        <form v-if="!loginSignupFlag" class="flex flex-col gap-y-5 mt-2.5">
            <input v-model="auth.login.email" type="email" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterEmail')" autocomplete="email" required>
            <input v-model="auth.login.password" type="password" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterPassword')" autocomplete="current-password" required>
            <div class="flex justify-between items-center">
                <button type="submit" class="btn-blue min-w-20">{{ useTranslateStore().t('login') }}</button>
                <div @click.prevent="loginSignupFlag = !loginSignupFlag" class="underline-text">{{ useTranslateStore().t('signup') }}</div>
            </div>
        </form>
        <form @submit.prevent="store" v-if="loginSignupFlag" class="flex flex-col gap-y-5 mt-2.5">
            <input v-model="auth.signup.name" type="text" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterLogin')" autocomplete="name" required>
            <input v-model="auth.signup.email" type="email" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterEmail')" autocomplete="none" required>
            <input v-model="auth.signup.password" type="password" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterPassword')" autocomplete="none" required>
            <input v-model="auth.signup.repeatPassword" type="password" class="p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('repeatPassword')" autocomplete="none" required>
            <div class="flex justify-between items-center">
                <button type="submit" class="btn-blue min-w-20">{{ useTranslateStore().t('signup') }}</button>
                <div @click.prevent="loginSignupFlag = !loginSignupFlag" class="underline-text">{{ useTranslateStore().t('haveAcc') }}</div>
            </div>
        </form>
    </ModalWindow>
</template>
