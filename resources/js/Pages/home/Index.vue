<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
import { onMounted, ref } from 'vue';

const products = ref(null);
const getProducts = async () => {
    const res = await axios.post(route('product.home'));
    products.value = res.data;
}

onMounted(() => {
    getProducts();
})
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleHome') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionHome')">
    </Head>
    <MainLayout>
        <div class="flex justify-between flex-wrap">
            <div v-for="product in products">
                <Card :product="product"></Card>
            </div>
        </div>
    </MainLayout>
</template>
