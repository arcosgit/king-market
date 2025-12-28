<script setup>
import ModalWindow from '@/Components/modal/ModalWindow.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import axios from 'axios';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import Load from '@/Widgets/icons/Load.vue';
import Success from '@/Widgets/icons/Success.vue';
const props = defineProps({show: Boolean});
const emit = defineEmits(['close']);

const amount = ref(null);
const error = ref(null);
const load = ref(false);
const success = ref(false);

const topUpBalance = async () => {
    error.value = null;
    load.value = true;
    try{
        const res = await axios.patch(route('user.topup.balance'), {amount: amount.value});
        setTimeout(() => {
            load.value = false;
            success.value = true;
            setTimeout(()=>{
                success.value = false;
                useUserBalanceStore().balance = res.data.balance;
                emit('close', true);
            },1500);
        }, 1500);
    } catch(err){
        load.value = false;
        error.value = err.response.data.errors.amount[0];
    }

}
</script>
<template>
    <ModalWindow :show="props.show" :hideTop="load || success" :name="useTranslateStore().t('topUpBalance')" @close="emit('close')">
        <div v-if="load" class="h-34.25 scale-90">
            <Load text="dataProcessing"></Load>
        </div>
        <div v-if="success" class="h-34.25">
            <Success></Success>
        </div>
        <form v-if="!load && !success" @submit.prevent="topUpBalance" class="flex flex-col gap-y-5 mt-2.5">
            <div class="relative">
                <input v-model="amount" min="50" max="100000" type="number" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterAmount')" autocomplete="none" required>
                <div v-if="error" class="absolute text-red-500 text-sm">{{ error }}</div>
            </div>
            <div class="w-full flex justify-center items-center">
                <button type="submit" class="btn-blue min-w-40">{{ useTranslateStore().t('replenish') }}</button>
            </div>
        </form>
    </ModalWindow>
</template>
