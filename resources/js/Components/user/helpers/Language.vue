<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import { watch } from 'vue';
watch(useTranslateStore().currentLang, async (newLang, OldLang) =>{
    useTranslateStore().currentLang = newLang;
    window.axios.defaults.headers.common['X-Lang'] = newLang;
});
</script>
<template>
    <select v-model="useTranslateStore().currentLang" class="bg-white text-black rounded-[10px] focus:outline-none h-10 w-11 cursor-pointer">
        <option value="ru">RU</option>
        <option value="en">EN</option>
    </select>
</template>
