<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
</script>
<template>
<div class="flex-col gap-y-1 flex items-center justify-center">
    <img class="w-28 h-28 animate-scale-in" src="/public/img/success.svg" alt="success">
    <div>{{ useTranslateStore().t('success') }}</div>
</div>
</template>
