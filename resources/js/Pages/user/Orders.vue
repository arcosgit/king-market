<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import { Head } from '@inertiajs/vue3';
import NoAuth from '@/Components/user/helpers/NoAuth.vue';
import { onMounted, onUnmounted, reactive, watch } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import Card from '@/Components/product/Card.vue';
import Review from '@/Components/order/modals/Review.vue';


const orders = reactive({data: [], nextCursor: null, allOrdersLoaded: false, isLoading: false});
const reviewModal = reactive({show: false, product: null, orderId: null});

const getOrders = async () => {
    if(useUserStore().id == null || orders.allOrdersLoaded || orders.isLoading) return;
    orders.isLoading = true;
    try{
        const params = orders.nextCursor ? { cursor: orders.nextCursor } : {};
        const res = await axios.post(route('user.get.orders'), params);
        for (const order of res.data.data) {
            order.show = false;
        }
        orders.data.push(...res.data.data);
        orders.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            orders.allOrdersLoaded = true;
        }
    } catch(error){
        alert('error server');
    } finally {
        orders.isLoading = false;
    }
}

const openReviewModal = (product, orderId) => {
    reviewModal.product = product;
    reviewModal.show = true;
    reviewModal.orderId = orderId;
};

const closeReviewModal = () => {
    reviewModal.show = false;
    reviewModal.product = null;
    reviewModal.orderId = null;
};

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight){
        getOrders();
    }
}

const newReview = (event) => {
    for (const order of orders.data) {
        for (const product of order.products){
            if(product.id === event.product_id){
                product.review_text = event.review_text;
                product.review_rating = event.review_rating;
            }
        }
    }
}

watch(()=>useUserStore().id, (newValue, oldValue) => {
    if(newValue != null){
        orders.data = [];
        orders.nextCursor = null;
        orders.allOrdersLoaded = false;
        getOrders();
    }
});

onMounted(() =>{
    getOrders();
    window.addEventListener('scroll', handleScroll);
});
onUnmounted(()=>{window.removeEventListener('scroll', handleScroll);});
</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('orders') }}</title>
        <meta name="description" :content="useTranslateStore().t('ordersDescription')">
    </Head>
    <Review :show="reviewModal.show" :product="reviewModal.product" :orderId="reviewModal.orderId" @close="closeReviewModal" @new_review="newReview($event)"></Review>
    <MainLayout>
        <div v-if="useUserStore().id != null">
            <div class="uppercase text-xl text-center">{{ useTranslateStore().t('orders') }}</div>
            <div v-for="(order, indexOrder) in orders.data" :key="indexOrder" class="mt-5 transition-all duration-300 bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-[10px]">
                <div class="px-2.5 py-3.75 flex justify-between items-center">
                    <div class="text-xl max-w-303.75 wrap-break-word">{{ useTranslateStore().t('order') }} - <span class="text-violet-800">{{ order.id }}</span></div>
                    <img @click.prevent="order.show = !order.show" :class="{ 'rotate-180': order.show }" class="cursor-pointer block transition duration-150" src="/public/img/arrow.svg" alt="open"/>
                </div>
                <div :class="['px-2.5 overflow-hidden', order.show ? 'grid grid-rows-[1fr] transition-all duration-300' : 'grid grid-rows-[0fr] transition-all duration-300']">
                    <div class="overflow-hidden">
                        <div class="grid gap-x-13.5 grid-cols-5 max-2xl:grid-cols-4 max-2xl:gap-x-18.5 max-xl:grid-cols-3 max-xl:gap-x-27.75 max-lg:grid-cols-2 max-lg:gap-x-57 max-md:grid-cols-1">
                            <div v-for="(product, indexProduct) in order.products" :key="indexProduct" class="max-md:place-items-center">
                                <div v-if="!product.no_product" class="mt-2.5">
                                    <Card :product="product">
                                        <div class="text-blue">{{ useTranslateStore().t('quantity') }}: {{ product.quantity }}</div>
                                        <button @click.prevent="openReviewModal(product, order.id)" class="btn-purple mt-1 h-7.5">{{ product.review_text == null ? useTranslateStore().t('leaveFeedback'): useTranslateStore().t('editReview') }}</button>
                                    </Card>
                                </div>
                                <div v-else class="flex flex-col justify-center items-center max-w-65 w-65 min-h-65">
                                    <img class="w-65 h-65" src="/public/img/no_product.png" alt="no product">
                                    <div>{{ useTranslateStore().t('productNoExists') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2.5 mb-2.5">
                            <div class="text-lime-500">{{ useTranslateStore().t('totalAmount') }}: <span class="text-lime-500">{{ order.total_cost }}₽</span></div>
                            <div class="text-gray">{{ useTranslateStore().t('date') }}: {{ order.created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <NoAuth></NoAuth>
        </div>
    </MainLayout>
</template>
