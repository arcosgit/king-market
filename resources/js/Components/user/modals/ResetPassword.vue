<script setup>
import ModalWindow from '@/Components/modal/ModalWindow.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from 'axios';
import { reactive, ref } from 'vue';
import { route } from 'ziggy-js';
import Load from '@/Widgets/icons/Load.vue';
import Success from '@/Widgets/icons/Success.vue';
const props = defineProps({show: Boolean});
const emit = defineEmits(['close']);

const password = reactive({old: '', new: '', repeat: ''});
const errorsPassword = reactive({old: '', new: '', repeat: ''});
const load = ref(false);
const success = ref(false);

const changePassword = async () => {
    errorsPassword.old = '';
    errorsPassword.new = '';
    errorsPassword.repeat = '';
    if(password.new != password.repeat){
        errorsPassword.repeat = useTranslateStore().t('notSamePass');
        return;
    }
    load.value = true;
    try{
        await axios.patch(route('user.change.password'), {oldPassword: password.old, newPassword: password.new, repeatPassword: password.repeat});
        setTimeout(() => {
            load.value = false;
            success.value = true;
            password.old = '';
            password.new = '';
            password.repeat = '';
            setTimeout(() => {
                success.value = false;
                emit('close', true);
            },1500);
        },1000);
    } catch (error) {
        load.value = false;
        errorsPassword.old = error.response.data.error;
    }
}

</script>
<template>
    <ModalWindow :show="props.show" :hideTop="load || success" :name="useTranslateStore().t('resetPassword')" @close="emit('close')">
        <div v-if="load" class="h-64.25 flex justify-center items-center" >
            <Load text="dataProcessing"></Load>
        </div>
        <div v-if="success" class="h-64.25 flex justify-center items-center" >
            <Success></Success>
        </div>
        <form v-if="!load && !success" @submit.prevent="changePassword" class="flex flex-col gap-y-5 mt-2.5">
            <div class="relative">
                <input v-model="password.old" minlength="8" maxlength="255" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterOldPassword')" autocomplete="none" required>
                <div v-if="errorsPassword.old" class="absolute text-red-500 text-sm">{{ errorsPassword.old }}</div>
            </div>
            <input v-model="password.new" minlength="8" maxlength="255" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterNewPassword')" autocomplete="none" required>
            <div class="relative">
                <input v-model="password.repeat" minlength="8" maxlength="255" type="password" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('repeatPassword')" autocomplete="none" required>
                <div v-if="errorsPassword.repeat" class="absolute text-red-500 text-sm">{{ errorsPassword.repeat }}</div>
            </div>
            <div class="w-full flex justify-center items-center">
                <button type="submit" class="btn-blue min-w-40">{{ useTranslateStore().t('change') }}</button>
            </div>
        </form>
    </ModalWindow>
</template>
