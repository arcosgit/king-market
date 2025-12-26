<script setup>
import ModalWindow from '@/Components/modal/ModalWindow.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
const props = defineProps({show: Boolean});
const emit = defineEmits(['close']);
</script>
<template>
    <ModalWindow :show="props.show" :name="useTranslateStore().t('topUpBalance')" @close="emit('close')">
        <form class="flex flex-col gap-y-5 mt-2.5">
            <div class="relative">
                <input max="100000" type="number" class="w-full p-2.25 h-10 grow border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterAmount')" autocomplete="none" required>
                <!-- <div v-if="errors.signup.repeatPassword" class="absolute text-red-500 text-sm">{{ errors.signup.repeatPassword }}</div> -->
            </div>
            <div class="w-full flex justify-center items-center">
                <button type="submit" class="btn-blue min-w-40">{{ useTranslateStore().t('replenish') }}</button>
            </div>
        </form>
    </ModalWindow>
</template>
