<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleHome') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionHome')">
    </Head>
    <MainLayout>
        <div class="flex justify-between flex-wrap">
            <Card />
            <Card />
            <Card />
            <Card />
        </div>
    </MainLayout>
</template>
