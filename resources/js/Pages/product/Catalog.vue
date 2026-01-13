<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import Card from '@/Components/product/Card.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { onBeforeMount, onUnmounted } from 'vue';
import { route } from 'ziggy-js';


onBeforeMount(() => {
    if(useCatalogStore().products.length <= 0) router.visit(route('index'));
});
onUnmounted(()=>{useCatalogStore().products = [];});
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('catalog') }}</title>
        <meta name="description" :content="useTranslateStore().t('catalogDescription')">
    </Head>
    <MainLayout>
        <div class="grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
            <div v-for="(product, index) in useCatalogStore().products" :key="index" class="max-md:place-items-center">
                <Card :product="product"></Card>
            </div>
        </div>
    </MainLayout>
</template>
