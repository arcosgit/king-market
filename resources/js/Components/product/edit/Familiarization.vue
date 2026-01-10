<script setup>
import { ref } from 'vue';
import ModalWindow from '../../modal/ModalWindow.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";

const props = defineProps({show: Boolean});
const emit = defineEmits(['showMore']);
const checkbox = ref(false);
</script>
<template>
    <ModalWindow :show="props.show" :hideTop="true" @close="$emit('close')">
        <div class="wrap-break-word mt-1">{{ useTranslateStore().t('familiarizationStart') }}</div>
        <div class="wrap-break-word mt-1"><span class="text-lime-500">1.</span> {{ useTranslateStore().t('familiarizationTemporary') }}</div>
        <div class="wrap-break-word mt-1"><span class="text-lime-500">2.</span> {{ useTranslateStore().t('familiarizationHide') }}</div>
        <div class="flex gap-x-1 items-center mt-1">
            <input v-model="checkbox" type="checkbox" class="h-4 w-4">
            <div>{{ useTranslateStore().t('dontShowAgain') }}</div>
        </div>
        <div class="flex justify-center items-center w-full">
            <button @click.prevent="$emit('showMore', checkbox)" class="btn-green mt-1 h-10">{{ useTranslateStore().t('close') }}</button>
        </div>
    </ModalWindow>
</template>
