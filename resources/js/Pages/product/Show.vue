<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import Images from '@/Components/product/show/Images.vue';
import Summary from '@/Components/product/show/Summary.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import Load from '@/Widgets/icons/Load.vue';
import Card from '@/Components/product/Card.vue';

const props = defineProps({product_id: String});
const product = ref(null);
const similarProducts = ref([]);
const load = ref(true);
const errorProduct = ref(null);
const reviews = reactive({data: [], rating: 'last', nextCursor: null, allReviewsLoaded: false, isLoading: false});

const getProduct = async () => {
    load.value = true;
    try{
        const res = await axios.post(route('product.get'), {product_id: props.product_id});
        setTimeout(()=>{
            product.value = res.data.products;
            similarProducts.value = res.data.similar_product;
            load.value = false;
            console.log(res);
        },500);
    } catch(e){
        errorProduct.value = true;
        load.value = false;
    }
}

const getReviews = async () => {
    if(reviews.allReviewsLoaded || reviews.isLoading) return;
    reviews.isLoading = true;
    try{
        const params = {
            product_id: props.product_id,
            rating: reviews.rating
        };
        if(reviews.nextCursor) {
            params.cursor = reviews.nextCursor;
        }
        const res = await axios.post(route('product.get.reviews'), params);
        if(res.data && res.data.data) {
            reviews.data.push(...res.data.data);
            reviews.nextCursor = res.data.next_cursor;
            if(!res.data.has_more || !res.data.next_cursor) {
                reviews.allReviewsLoaded = true;
            }
        } else {
            reviews.allReviewsLoaded = true;
        }
    } catch(error){
        alert('error server');
    } finally {
        reviews.isLoading = false;
    }
}

watch(()=>useTranslateStore().currentLang, ()=>{
    getProduct();
});
watch(()=>reviews.rating, ()=>{
    reviews.data = [];
    reviews.nextCursor = null;
    reviews.allReviewsLoaded = false;
    getReviews();
});

onMounted(async()=>{
    getProduct();
    getReviews();
})
</script>
<template>
    <Head>
        <title>{{ load ? '' : errorProduct ? useTranslateStore().t('productNotFound') : product.name }}</title>
        <meta name="description" :content="load ? '' : errorProduct ? useTranslateStore().t('productNotFound') : product.description">
    </Head>
    <MainLayout>
        <div v-if="!load && product != null">
            <div class="flex justify-between gap-x-5 w-full items-start">
                <Images :images="product.images"></Images>
                <Summary :product="product"></Summary>
                <div class="w-full h-125 rounded-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5 overflow-y-auto custom-scrollbar">
                    <div class="text-base font-bold text-center">{{ useTranslateStore().t('relatedProducts') }}</div>
                    <div v-if="similarProducts.length > 0" class="flex flex-col gap-y-2.5 my-2.5">
                        <div v-for="(product, index) in similarProducts" :key="index">
                            <Card :product="product" :flexEnabled="true"></Card>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('description') }}</div>
            <div class="text-base wrap-break-word">{{ product.description }}</div>
            <div class="text-xl font-bold mt-5">{{ useTranslateStore().t('aboutProduct') }}</div>
            <div v-for="(characteristic, index) in product.characteristics" :key="index" class="mt-1"><span class="text-gray">{{ characteristic.characteristic_key }}</span> {{ characteristic.characteristic_value }}</div>
            <div class="flex items-center gap-x-2.5 mt-5">
                <div class="text-xl font-bold">{{ useTranslateStore().t('productReviews') }}</div>
                <select v-model="reviews.rating" class="bg-[#263646] rounded-[10px] h-10 focus:outline-none cursor-pointer">
                    <option value="last">{{ useTranslateStore().t('last') }}</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
            </div>
            <div v-if="reviews.data.length > 0" class="flex flex-col gap-y-5">
                <div v-for="(review, index) in reviews.data" :key="index" class="flex flex-col gap-y-1 mt-2.5">
                    <div class="flex items-center gap-x-2.5">
                        <div class="text-base">{{ review.user_name }}</div>
                        <div class="text-base text-gray">{{ review.created_at }}</div>
                        <div class="text-base">{{ review.rating }}</div>
                    </div>
                    <div class="wrap-break-word">{{ review.review }}</div>
                </div>
                <div v-if="!reviews.allReviewsLoaded" class="flex justify-center mt-2.5">
                    <button @click="getReviews" :disabled="reviews.isLoading" class="btn-purple h-10">
                        {{ reviews.isLoading ? useTranslateStore().t('load') + '...' : useTranslateStore().t('loadMore') }}
                    </button>
                </div>
            </div>
            <div v-else-if="!reviews.isLoading" class="text-center mt-2.5 text-xl">{{ useTranslateStore().t('no_reviews') }}</div>
        </div>
        <div v-if="load" class="flex justify-center items-center h-[calc(100%-17px)]">
            <Load text="load"></Load>
        </div>
        <div v-if="errorProduct">
            <div class="text-center text-xl">{{ useTranslateStore().t('productNotFound') }}</div>
            <div class="flex w-full justify-center items-center mt-2.5">
                <Link :href="route('index')">
                    <button class="btn-green h-10">{{ useTranslateStore().t('home') }}</button>
                </Link>
            </div>
        </div>
    </MainLayout>
</template>
