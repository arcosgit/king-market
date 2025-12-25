<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
const props = defineProps({text: String});
</script>
<template>
<div class="flex-col gap-y-4 flex items-center justify-center">
    <div class="w-28 h-28 border-8 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
    <div>{{ useTranslateStore().t(props.text) }} <span class="animate-dot-flashing-1">.</span> <span class="animate-dot-flashing-2">.</span> <span class="animate-dot-flashing-3">.</span></div>
</div>
</template>
